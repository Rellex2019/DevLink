<template>

    <div class="container-repository-block">
        <div class="repository-block">


            <div class="container-files-block">
                <div class="outer-padding-inblock">
                    <div class="files-block" ref="filesBlock" @dragover.prevent="handleDragOver"
                        @dragleave.prevent="handleDragLeave" @drop="handleDrop">
                        <Search class="search-input" placeholderText="Поиск среди файлов..." />
                        <!-- Сделать фото из БД -->
                        <div class="container-person-repository">
                            <div class="container-person-photo"><img class="person-photo" src="@/images/Avatar.png"
                                    alt="Фото пользователя"></div>
                            <div class="person-name">{{ route.params.user }}/</div>
                        </div>
                        <div class="repository-action">
                            <div class="repository-name" :class="{ 'selected': choicedFolder == null }"
                                @click="choicedFolder = null">{{ route.params.repositoryName }}</div>
                            <div class="container-svg">
                                <button class="btn-add" @click="addObjectArea('file')"><img class="file-add-svg"
                                        src="@/svg/file-add.svg" alt="Добавить файл"></button>
                                <button class="btn-add" @click="addObjectArea('folder')"><img class="folder-add-svg"
                                        src="@/svg/folder-add.svg" alt="Создать папку"></button>
                            </div>
                        </div>
                        <div class="repository-files">
                            <file-tree @main-folder="changeChoicedFolder" @choice-folder="changeChoicedFolder"
                                @choice-file="openFile" @store-object="saveObject" @remove-file="removeFile"
                                :choicedFile="selectedFile" :choicedFolder="choicedFolder" :fileTree="fileTree"
                                @choice-element="changeUsedElement" :usedElement="usedElement"
                                @show-context="showContextMenu" :nameElement="nameElement" />
                        </div>
                    </div>
                </div>
            </div>
            <div class="container-active-block">
                <div class="active-block-flex" ref="scrollBlock" @wheel="handleScroll">
                    <File v-for="elem in inActiveBar" :key="elem.id" @select="selectFile(elem.id)"
                        @read-content="contentFileShow" :is-active="selectedFile === elem.id" :file="elem"
                        @close-file="closeFile" :updatedContent="updatedContent[elem.id]" :fileTree="fileTree" />
                </div>

                <div class="container-inner-file">
                    <div class="inner-file">
                        <Content @update-content="contentFileUpdate" :id="selectedFile" :content="contentFile"
                            :language="fileExtension" />
                    </div>
                </div>
            </div>




            <div class="container-team-block" :class="{ 'visible': showTeamBlock }">
                <div class="toggle-team-block-btn" @click="showTeamBlock = !showTeamBlock">
                    {{ showTeamBlock ? '►' : '◄' }}
                </div>
                <div class="outer-padding-inblock">
                    <div class="team-block">
                        <button @click="$router.push(`${$route.path}/team1/tasks`)" style="color: black;">Перейти к задачам ПРОЕКТА</button>
                    </div>
                </div>
            </div>

        </div>
        <Modal :style="{ left: `${mouseX}px`, top: `${mouseY}px` }" :isVisible="showMenu">
            <div class="container-options" ref="containerOptions">
                <div class="option" @click="openInputName">Переименовать</div>
                <div class="option last" @click="removeFile()">Удалить</div>
            </div>
        </Modal>
    </div>


</template>
<script setup>
import Search from '@/js/components/input/Search.vue';
import File from '@/js/components/repository/File.vue';
import Content from '@/js/components/repository/Content.vue';
import FileTree from '@/js/components/repository/FileTree.vue';
import { computed, onMounted, provide, ref, useTemplateRef } from 'vue';
import Modal from '@/js/components/modal/Modal.vue';
import axios from 'axios';
import { useRoute } from 'vue-router';

//В БД БУДЕТ ЕЩЕ ОДНА СТРОКА УКАЗЫВАЮЩАЯ НА ID ПРОЕКТА
const projectId = ref(null);
const files = ref([]);
const inActiveBar = ref([]);


const scrollBlock = ref(null);
const selectedFile = ref(null);
const choicedFolder = ref();
const contentFile = ref();
const updatedContent = ref([]);
const usedElement = ref(null);
const fileTree = ref([]);

const nameElement = ref('');


const showTeamBlock = ref(false);
const containerOptions = ref(null);
let showMenu = ref(false);
let mouseX = ref(0);
let mouseY = ref(0);

const route = useRoute();

const draggedItem = ref(null);
const dropTarget = ref(null);


provide('draggedItem', draggedItem);
provide('dropTarget', dropTarget);

const fileExtension = computed(() => {
    if (!selectedFile.value) return '';

    const currentFile = files.value.find(file => file.id === selectedFile.value);
    if (!currentFile || !currentFile.name) return '';

    const parts = currentFile.name.split('.');
    return parts.length > 1 ? parts.pop().toLowerCase() : '';
});



function fetchFiles() {
    axios.get(`/files/${route.params.user}/${route.params.repositoryName}`)
        .then(response => {
            files.value = response.data.files;
            projectId.value = response.data.project_id;
            fileTree.value = buildFileTree(files.value);
        })
}

function addObjectArea(type) {
    const exists = files.value.some(file => file.type === "addfile" || file.type === "addfolder");
    removeInputs();
    if (!exists) {
        createInput();
    }
    else {
        const deleted = files.value.filter(file => file.type !== "addfile" && file.type !== "addfolder");
        files.value = deleted;
        createInput();
    }
    fileTree.value = buildFileTree(files.value);


    function createInput() {
        if (type === 'file') {
            files.value.push({
                "id": Date.now(),
                'name': '',
                "type": "addfile",
                "parent_id": choicedFolder.value ? choicedFolder.value : null,
            });
        }
        if (type === 'folder') {
            files.value.push({
                'id': Date.now(),
                'name': '',
                "type": "addfolder",
                "parent_id": choicedFolder.value ? choicedFolder.value : null,
            });
        }
        nameElement.value = '';
    }

}
function saveObject({ data, actionType }) {
    console.log('Action type:', actionType); // 'add' или 'change'
    console.log('Object data:', data);

    if (actionType === 'add') {
        axios.post(data.type == 'file' ?
            `/project/${projectId.value}/file/create` :
            `/project/${projectId.value}/folder/create`,
            { ...data, 'project_id': projectId.value, content: '' })
            .then(response => {
                files.value = files.value.map(file => {
                    if (file.id === data.id) {
                        return {
                            ...response.data,
                        };
                    }
                    return file;
                });
                response.data.type == 'file' ? openFile(response.data.id) : changeChoicedFolder(response.data.id);

                fileTree.value = buildFileTree(files.value, null, true);
            })
            .catch(error => {
                console.error('Ошибка сохранения:', error);
                // Откатываем изменения в случае ошибки
                files.value = files.value.filter(file => file.id !== data.id);
                fileTree.value = buildFileTree(files.value);
            });
    }
    // Обработка изменения существующего элемента
    else if (actionType === 'change') {

        axios.put(`/project/${projectId.value}/object/update/${data.id}`, data)
            .then(response => {
                files.value = files.value.map(file => {
                    if (file.id === data.id) {
                        return {
                            ...file,
                            ...data,
                            type: data.type // меняем тип с 'changefile'/'changefolder' на 'file'/'folder'
                        };
                    }
                    return file;
                });

                fileTree.value = buildFileTree(files.value, null, true);
            })
            .catch(error => {
                fileTree.value = buildFileTree(files.value);
            });
    }
    else if(actionType === 'move')
    {
        axios.put(`/project/${projectId.value}/object/move/${data.id}`, data)
            .then(response => {
                files.value = files.value.map(file => {
                    if (file.id === data.id) {
                        return {
                            ...file,
                            ...data,
                            type: data.type // меняем тип с 'changefile'/'changefolder' на 'file'/'folder'
                        };
                    }
                    return file;
                });

                fileTree.value = buildFileTree(files.value, null, true);
            })
            .catch(error => {
                fileTree.value = buildFileTree(files.value);
            });
    }
}

//Удаление
async function removeFile(usedId, empty) {
    const id = usedId ? usedId : usedElement.value;

    if (empty) {
        removeInputs();
        fileTree.value = buildFileTree(files.value, null, false);
        console.log(empty);
    }
    else {
        await axios.delete(`/project/${projectId.value}/delete/${id}`)
            .then(response => {
                files.value = files.value.filter(file => file.id != id);
                fileTree.value = buildFileTree(files.value);
            })
    }

    showMenu.value = false;
}

function openInputName() {

    removeInputs();
    files.value = files.value.map(file => {
        if (file.id === usedElement.value && file.type === 'folder') {
            nameElement.value = file.name;
            return {
                ...file,
                "type": 'changefolder'
            }
        }
        else if (file.id === usedElement.value && file.type === 'file') {
            nameElement.value = file.name;
            return {
                ...file,
                "type": 'changefile'
            }
        }
        return file;
    });
    showMenu.value = false;
    fileTree.value = buildFileTree(files.value, null, false);
}

function removeInputs() {
    files.value = files.value.filter(file =>
        !['changefolder', 'changefile', 'addfile', 'addfolder'].includes(file.type)
    );
}
//

//Создание иерархической  структуры файлов и папок


function buildFileTree(filesLocal = files.value, parentId = null, sort = true) {
    const fileTree = [];
    if (sort) {
        filesLocal.sort((a, b) => {
            if (a.type === 'folder' && b.type !== 'folder') {
                return -1;
            }
            if (a.type !== 'folder' && b.type === 'folder') {
                return 1;
            }
            if (a.parent_id === null && b.parent_id !== null) {
                return -1;
            }
            if (a.parent_id !== null && b.parent_id === null) {
                return 1;
            }
            const isAEnglish = /^[a-zA-Z0-9]/.test(a.name);
            const isBEnglish = /^[a-zA-Z0-9]/.test(b.name);

            if (isAEnglish && !isBEnglish) {
                return -1; // a перед b, если a - английское
            }
            if (!isAEnglish && isBEnglish) {
                return 1; // b перед a, если b - английское
            }
            return a.name.localeCompare(b.name);
        });
    }
    filesLocal.forEach(file => {
        if (file.parent_id === parentId) {
            const fileNode = {
                id: file.id,
                name: file.name,
                type: file.type,
                parent_id: file.parent_id,
                children: buildFileTree(files.value, file.id, sort)
            };
            fileTree.push(fileNode);
        }

    });
    return fileTree;
}

//

//Сюда прикручивать сохранение файлов в бэк
function contentFileShow(content, id) {
    contentFile.value = content;
    selectedFile.value = id;
}
async function contentFileUpdate(content, id) {

    await axios.put(`/project/${projectId.value}/file/update/${id}`, { 'content': content })
        .then(response => {
            updatedContent.value[id] = content;
        })
}
//............................................


function changeChoicedFolder(id) {
    choicedFolder.value = id;
}
function changeUsedElement(id) {
    usedElement.value = id;
}
function openFile(id) {


    const findToAdd = files.value.find(file => file.id === id);
    if (findToAdd) {
        const exists = inActiveBar.value.find(file => file.id === id);
        if (exists) {
            selectFile(id);
            contentFile.value = updatedContent.value[id] ? updatedContent.value[id] : findToAdd.content;
        }
        else {
            inActiveBar.value.push(findToAdd);
            selectFile(id);
            contentFile.value = updatedContent.value[id] ? updatedContent.value[id] : findToAdd.content;

        }

    }
    else {
        console.log('ERROR - Repository: func OpenFile()');
    }
}
function closeFile(id) {
    console.log(id);
    inActiveBar.value = inActiveBar.value.filter(file => file.id != id);
}

function selectFile(id) {
    selectedFile.value = id;
}
function handleScroll(event) {
    if (scrollBlock.value) {
        event.preventDefault();
        scrollBlock.value.scrollLeft += event.deltaY;
    }
}






function showContextMenu(event, item) {
    event.preventDefault();
    showMenu.value = true;
    mouseX.value = event.clientX;
    mouseY.value = event.clientY;
    window.addEventListener('mousedown', handleClickOut);
}
function handleClickOut(event) {
    const block = containerOptions.value;
    if (block && !block.contains(event.target)) {
        showMenu.value = false;
        window.removeEventListener('mousedown', handleClickOut);
    }
}



const filesBlock = ref(null);
function handleDragOver(event) {
    event.preventDefault();
    event.currentTarget.style.backgroundColor = '#656a6f63'; 
}

function handleDragLeave(event) {
    event.currentTarget.style.backgroundColor = '';
}

function handleDrop(event) {
    event.preventDefault();
    filesBlock.value.style.backgroundColor = '';
    if (draggedItem.value) {
        if (event.currentTarget === filesBlock.value) {
            const updatedItem = {
                ...draggedItem.value,
                parent_id: null
            };
            saveObject({
                data: updatedItem,
                actionType: 'change'
            });
        }

        draggedItem.value = null; 
        dropTarget.value = null; 
    }
}




onMounted(() => {
    fetchFiles();

});
</script>

<style scoped>
.search-input {
    margin-top: 25px;
}

* {
    color: #F8F9FA;
}

.outer-padding-inblock {
    padding: 0px 50px;
}

.container-repository-block {
    width: 100%;
}

.repository-block {
    width: 100%;
    display: flex;
    background-color: #101112;
}

.container-files-block {
    flex: none;
    width: 23.5%;
    background-color: #161718;
    box-sizing: border-box;
    border-right: 1px solid #656A6F;
}


.container-person-repository {
    margin-top: 15px;
    font-family: Montserrat;
    font-size: 16px;
    display: flex;
    align-items: end;
}

.container-person-photo {
    width: 30px;
    height: 30px;
    border-radius: 15px;
    overflow: hidden;
}

.person-photo {
    width: 50px;
    height: 50px;
    object-position: -30px;
    object-fit: cover;
    background: none;
}

.person-name {
    margin-left: 5px;
}

.repository-action {
    margin-top: 7px;
    display: flex;
    justify-content: space-between;
}

.repository-name {


    margin-right: 15px;
    text-overflow: ellipsis;
    text-wrap: nowrap;
    overflow: hidden;

    user-select: none;
    cursor: pointer;
    font-size: 18px;
    font-family: Montserrat;

    border-radius: 3px;
}

.repository-name:hover {
    background-color: #656A6F;
}

.selected {
    background-color: #656A6F;
}


.repository-files {
    height: 60vh;
    overflow-y: auto;
}

.repository-files::-webkit-scrollbar {
    width: 7px;
    /* Высота горизонтальной полосы прокрутки */
}

.repository-files::-webkit-scrollbar-track {
    background: #101112;
    /* Цвет фона полосы прокрутки */
    border-radius: 10px;
    /* Закругление углов */
}

.repository-files::-webkit-scrollbar-thumb {
    background: #656A6F;
    /* Цвет ползунка */
    border-radius: 10px;
    /* Закругление углов ползунка */
}

.repository-files::-webkit-scrollbar-thumb:hover {
    background: #EDB200;
    /* Цвет ползунка при наведении */
}



.container-svg {
    width: 60px;
    display: flex;
    justify-content: space-between;
}

.btn-add {
    padding: 3px 3px 0px 3px;
    border-radius: 5px;
    cursor: pointer;
    border: none;
    background: none;
}

.btn-add:hover {
    background: #656A6F;
}

.btn-add:active {
    background: #EDB200;
}

.file-add-svg {
    width: 20px;
    height: 20px;
    user-select: none;
}

.folder-add-svg {
    width: 20px;
    height: 20px;
    user-select: none;
}


.container-active-block {
    flex: 1;
    display: flex;
    flex-direction: column;
    box-sizing: border-box;
    border-bottom: 1px solid #656A6F;
    border-right: 1px solid #656A6F;
    width: 49.5%;
    height: calc(100vh - 51px - 80px);

    background-color: #161718;
}

.active-block-flex {

    display: flex;
    overflow-x: auto;
}

.active-block-flex::-webkit-scrollbar {
    height: 5px;
}

.active-block-flex::-webkit-scrollbar-track {
    background: #101112;
    border-radius: 10px;
}

.active-block-flex::-webkit-scrollbar-thumb {
    background: #656A6F;
    border-radius: 10px;
}

.active-block-flex::-webkit-scrollbar-thumb:hover {
    background: #EDB200;
}

.container-options {
    display: flex;
    flex-direction: column;
}

.option {
    font-size: 12px;
    border-bottom: 1px solid #656A6F;
    padding: 5px 0px;
    font-family: 'Montserrat';
}

.option.last {
    border: none
}

/* 
.active-block {
    max-width: 25%;
    min-width: 120px;
    padding: 15px 0px 15px 15px;
    border-right: 1px solid #656A6F;
}

.active-task {
    display: flex;
    justify-content: space-between;
}

.task-text {
    font-family: Fira Code;
    font-size: 16px
}

.container-close {
    margin-top: -9px;
    margin-right: 7px;
    display: flex;
    flex-direction: column;
}

.container-close:hover .close-svg {
    color: #EDB200;
}

.close-svg {
    color: #F8F9FA;
    width: 11px;
    height: 11px;
} */



.container-inner-file {
    flex: 1;
    height: calc(100% - 58px);
    border-top: 1px solid #656A6F;
    width: 100%;
}

.inner-file {
    width: 100%;
    height: 100%;
}






.container-team-block.visible {
    position: relative;
    width: 27%;
    box-sizing: border-box;
    background-color: #101112;
}

.team-block {
    height: calc(100vh - 80px);
}


.toggle-team-block-btn {
    position: absolute;
    right: 0;
    /* Ширина team-block */
    top: 50%;
    transform: translateY(-50%);
    z-index: 10;
    cursor: pointer;
    background-color: #161718;
    padding: 10px 5px;
    border-radius: 5px 0 0 5px;
    border: 1px solid #656A6F;
    border-right: none;
}

.toggle-team-block-btn:hover {
    background-color: #656A6F;
}

.container-team-block {
    width: 0;
    overflow: hidden;
    transition: width 0.3s ease;
    flex-shrink: 0;
}

.container-team-block.visible {
    right: 0;
    /* Показываем */
}

.repository-block {
    width: 100%;
    display: flex;
    position: relative;
    /* Добавьте это */
    overflow: hidden;
    /* Чтобы скрывать team-block за пределами */
}
</style>
