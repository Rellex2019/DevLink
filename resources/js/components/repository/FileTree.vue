<template>
    <div class="file-tree">
        <div v-for="(item, index) in fileTreeLocal">
            <button class="item" @contextmenu.prevent.stop="showContextMenu($event, item)" draggable="true"
                @dragstart="handleDragStart($event, item)" @dragover="handleDragOver($event, item)"
                @dragleave="handleDragLeave($event)" @drop="handleDrop($event, item)" @dragend="handleDragEnd($event)">

                <div @click.stop="() => handleClick(item, index)" ref="containerName"
                    :class="{ 'usedElement': item.id == choicedFileLocal, 'choicedFolder': item.id == usedElementLocal }"
                    class="container-name">
                    <div class="container-content">
                        <div class="container-svg">
                            <img ref="arrowSvg" src="@/svg/arrow-folder.svg"
                                v-if="item.type === 'folder' || item.type === 'addfolder' || item.type === 'changefolder'"
                                class="arrow-svg" alt="Стрелка папки">
                            <img src="@/svg/file.svg" class="file-svg"
                                v-if="item.type === 'file' || item.type === 'addfile' || item.type === 'changefile'"
                                alt="Файл">
                            <input class="name input"
                                v-if="item.type === 'addfile' || item.type === 'addfolder' || item.type === 'changefolder' || item.type === 'changefile'"
                                v-focus v-model="inputContent" @keydown.enter.prevent="checkValidation(item)"
                                @focus="inputContent = nameElementLocal" @blur="checkValidation(item)" type="text">
                        </div>
                        <span
                            v-if="item.type !== 'addfolder' && item.type !== 'addfile' && item.type !== 'changefolder' && item.type !== 'changefile'"
                            class="name">{{ item.name
                            }}</span>
                    </div>
                </div>
                <div ref="children" style="display: none;" :class="{ 'folder': item.type === 'folder' }">
                    <file-tree v-if="item.type === 'folder'" :choicedFile="choicedFileLocal"
                        :choicedFolder="choicedFolderLocal" :key="item.id" :fileTree="item.children"
                        @store-object="setobject" @remove-file="removeFile" @choice-folder="handleChoiceFolder"
                        @choice-file="handleChoiceFile" @choice-element="changeUsedElement" :usedElement="usedElement"
                        :nameElement="nameElementLocal" @show-context="showContextMenu" />
                </div>
            </button>
        </div>
    </div>
</template>

<script>
import { inject, ref } from 'vue';

export default {
    name: 'FileTree',
    data() {
        return {
            choicedFolderLocal: ref(),
            inputContent: ref(),
            nameElementLocal: ref(),
            choicedFileLocal: ref(),
            usedElementLocal: null,
            fileTreeLocal: ref(),
            isProcessing: false,

            draggedItem: inject('draggedItem'),
            dropTarget: inject('dropTarget')
        };
    },
    watch: {
        fileTree(content) {
            this.fileTreeLocal = content;
        },
        choicedFolder(content) {
            this.choicedFolderLocal = content;
        },
        choicedFile(content) {
            this.choicedFileLocal = content;
        },
        usedElement(content) {
            this.usedElementLocal = content;
        },
        nameElement(content) {
            this.nameElementLocal = content;
        },

    },
    mounted() {
        this.fileTreeLocal = this.fileTree;
    },

    props: {
        fileTree: {
            type: Array,
            required: true
        },
        choicedFolder: {
            required: false
        },
        choicedFile: {
            required: false
        },
        usedElement: {
            required: false
        },
        nameElement: {
            type: String,
            required: false
        }
    },
    methods: {
        handleClick(item, index) {
            if (this.$refs.children && this.$refs.children[index] && this.$refs.arrowSvg && this.$refs.arrowSvg[index]) {
                const childrenRef = this.$refs.children[index];
                const arrowSvgRef = this.$refs.arrowSvg[index];

                if (childrenRef && item.type === 'folder') {
                    // Отправляем ID дочернего элемента
                    this.$emit('choice-folder', item.id);
                    childrenRef.style.display = childrenRef.style.display === 'none' ? 'block' : 'none';
                    if (childrenRef.style.display === 'block') {
                        arrowSvgRef.style.transform = 'rotate(90deg)';
                    } else {
                        arrowSvgRef.style.transform = 'rotate(0deg)';
                    }
                }

            }
            else if (item.type === 'file') {
                this.handleChoiceFile(item.id);
            }
        },


        showContextMenu(event, item) {
            this.changeUsedElement(item.id);
            event.preventDefault();
            this.$emit('show-context', event, item);
        },

        changeUsedElement(id) {
            this.$emit('choice-element', id);
            this.usedElementLocal = id;
        },
        removeFile(id = this.usedElementLocal, empty) {
            this.$emit('remove-file', id, empty);

        },


        handleChoiceFile(id) {
            this.changeUsedElement(null);
            this.$emit('choice-file', id);
            this.choicedFileLocal = id;
        },
        handleChoiceFolder(id) {
            this.changeUsedElement(null);
            this.$emit('choice-folder', id);
            this.choicedFolderLocal = id; // Обновляем выбранную папку
        },

        setobject(object) {
            this.$emit('store-object', object);
        },


        checkValidation(item) {
            if (this.isProcessing) return;
            this.isProcessing = true;

            if (!this.inputContent || this.inputContent.length < 1) {
                this.$showAlert('Длина файла не может быть меньше 1 символа', 'error');
                if (item.type == 'addfile' || item.type == 'addfolder') {
                    this.removeFile(item.id, true);
                }
            } else {
                this.saveObject(item);
            }

            setTimeout(() => { this.isProcessing = false; }, 1000);
        },
        saveObject(item) {
            const type = item.type == "addfile" || item.type == 'changefile' ? 'file' : 'folder';

            const newobject = {
                "id": item.id,
                "name": this.inputContent,
                "type": type,
                "parent_id": item.parent_id
            };

            // Передаем объект и тип операции (add/change) отдельными параметрами
            this.$emit('store-object', {
                data: newobject,
                actionType: item.type.startsWith('add') ? 'add' : 'change'
            });
        },





        handleDragStart(event, item) {
            event.stopPropagation();

            event.dataTransfer.setData('text/plain', JSON.stringify(item));
            this.draggedItem = item;
            event.currentTarget.style.opacity = '0.4';
        },

        handleDragOver(event, item) {
            event.stopPropagation();
            if (this.draggedItem &&
                (item.type === 'folder' || item.type === 'addfolder' || item.type === 'changefolder') &&
                item.id !== this.draggedItem.id) {
                event.preventDefault();
                this.dropTarget = item;
                event.currentTarget.style.backgroundColor = '#656a6f63';
                event.currentTarget.style.opacity = '1';
            }
        },

        handleDragLeave(event) {
            event.currentTarget.style.backgroundColor = '';
        },

        handleDrop(event, item) {
            event.preventDefault();
            event.currentTarget.style.backgroundColor = '';

            if (this.draggedItem && this.dropTarget) {
                // Проверяем, что не пытаемся переместить папку в саму себя или в свою дочернюю папку
                if (this.isValidMove(this.draggedItem, this.dropTarget)) {
                    this.moveItem(this.draggedItem, this.dropTarget);
                } else {
                    this.$showAlert('Невозможно переместить папку в саму себя или в дочернюю папку', 'error');
                }
            }

            this.draggedItem = null;
            this.dropTarget = null;
        },
        // В секции methods
        handleDragEnd(event) {
            event.currentTarget.style.opacity = '1'; // Сбрасываем opacity
            this.draggedItem = null;
            this.dropTarget = null;
        },
        isValidMove(sourceItem, targetItem) {
            // Проверяем, что target не является дочерней папкой source
            if (sourceItem.type === 'folder' && targetItem.type === 'folder') {
                return !this.isChildFolder(sourceItem, targetItem);
            }
            // Для файлов проверяем только что не перемещаем в самого себя
            return sourceItem.id !== targetItem.id;
        },

        isChildFolder(parent, child) {
            // Рекурсивно проверяем, является ли child дочерней папкой parent
            if (!child.children || !child.children.length) return false;
            if (child.children.some(c => c.id === parent.id)) return true;
            for (const c of child.children) {
                if (this.isChildFolder(parent, c)) return true;
            }
            return false;
        },

        moveItem(item, targetFolder) {
            // Создаем обновленный объект с новым parent_id
            const updatedItem = {
                ...item,
                parent_id: targetFolder.id
            };

            // Эмитируем событие изменения
            this.$emit('store-object', {
                data: updatedItem,
                actionType: 'move'
            });

            this.$showAlert(`"${item.name}" перемещен в "${targetFolder.name}"`, 'success');
        }
    }
}
</script>
<style scoped>
.item {
    border: none;
    background: none;
    width: 100%;
}

.name {
    text-wrap: nowrap;
    text-overflow: ellipsis;
    font-size: 1rem;
    font-family: 'Fira Code';
    color: #F8F9FA;
}

.input {
    margin-left: 15px;
    height: 20px;
    outline: none;
    border: 3px solid #202123;
    background-color: #4f5157;
}

.folder {
    margin-left: 20px;
}

.container-content {
    display: flex;
    align-items: center;
}

.container-name {
    padding-left: 5px;
    align-items: center;
    width: calc(100% - 5px);
    cursor: pointer;
    user-select: none;
    display: flex;
    justify-content: space-between;
}

.usedElement {
    background-color: #656a6f63;
}

.choicedFolder {
    background-color: #202123;
    border-top: 1px solid #EDB200;
    border-bottom: 1px solid #EDB200;
}

.container-svg {
    display: flex;
    align-items: center;
    width: 15px;
    margin-right: 5px;
}

.arrow-svg {
    transition: 0.2s;
    width: 6px;
    height: 12px;
}

.file-svg {
    width: 13px;
    height: 14px;
}



.item[draggable="true"] {
    cursor: grab;
}

.item[draggable="true"]:active {
    cursor: grabbing;
}

.drop-target {
    background-color: #656a6f63 !important;
    border: 1px dashed #EDB200;
}
</style>