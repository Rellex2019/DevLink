<template>

    <div class="container-repository-block">
        <div class="repository-block">


            <div class="container-files-block">
                <div class="outer-padding-inblock">
                    <div class="files-block">
                        <Search class="search-input" placeholderText="Поиск среди файлов..." />
                        <!-- Сделать фото из БД -->
                        <div class="container-person-repository">
                            <div class="container-person-photo"><img class="person-photo" src="@/images/Avatar.png"
                                    alt="Фото пользователя"></div>
                            <div class="person-name">Rellex/</div>
                        </div>
                        <div class="repository-action">
                            <div class="repository-name" :class="{ 'selected': choicedFolder == null }"
                                @click="choicedFolder = null">FLbanksfdsdfsdfdsfsdfsdfs</div>
                            <div class="container-svg">
                                <button class="btn-add" @click="addObjectArea('file')"><img class="file-add-svg"
                                        src="@/svg/file-add.svg" alt="Добавить файл"></button>
                                <button class="btn-add" @click="addObjectArea('folder')"><img class="folder-add-svg"
                                        src="@/svg/folder-add.svg" alt="Создать папку"></button>
                            </div>
                        </div>
                        <div class="repository-files">
                            <file-tree @main-folder="changeChoicedFolder" @choice-folder="changeChoicedFolder"
                                @choice-file="openFile" @store-object="addObject" @remove-file="removeFile"
                                :choicedFile="selectedFile" :choicedFolder="choicedFolder" :fileTree="fileTree"
                                @choice-element="changeUsedElement" :usedElement="usedElement"
                                @show-context="showContextMenu" />
                        </div>
                    </div>
                </div>
            </div>
            <div class="container-active-block">
                <div class="active-block-flex" ref="scrollBlock" @wheel="handleScroll">
                    <File v-for="elem in inActiveBar" :key="elem.id" @select="selectFile(elem.id)"
                        @read-content="contentFileShow" :is-active="selectedFile === elem.id" :file="elem"
                        @close-file="closeFile" :updatedContent="updatedContent[elem.id]" />
                </div>

                <div class="container-inner-file">
                    <div class="inner-file">
                        <Content @update-content="contentFileUpdate" :content="contentFile" :id="selectedFile" />
                    </div>
                </div>
            </div>


            <div class="container-team-block">
                <div class="outer-padding-inblock">
                    <div class="team-block">
                        FILA
                    </div>
                </div>
            </div>

        </div>
        <Modal :style="{ left: `${mouseX}px`, top: `${mouseY}px` }" :isVisible="showMenu">
            <div class="container-options" ref="containerOptions">
                <div class="option" @click="changeFileName">Переименовать</div>
                <div class="option" @click="removeFile">Удалить</div>
            </div>
        </Modal>
    </div>


</template>
<script setup>
import Search from '@/js/components/input/Search.vue';
import File from '@/js/components/repository/File.vue';
import Content from '@/js/components/repository/Content.vue';
import FileTree from '@/js/components/repository/FileTree.vue';
import { onMounted, ref } from 'vue';
import Modal from '@/js/components/modal/Modal.vue';
//В БД БУДЕТ ЕЩЕ ОДНА СТРОКА УКАЗЫВАЮЩАЯ НА ID ПРОЕКТА
const files = ref([
    {
        "id": 1,
        "name": "Проект",
        "type": "folder",
        "parent_id": null
    },
    {
        "id": 2,
        "name": "Папка 1",
        "type": "folder",
        "parent_id": 1
    },
    {
        "id": 25,
        "name": "Папка cor",
        "type": "folder",
        "parent_id": null
    },
    {
        "id": 32,
        "name": "Папка cor",
        "type": "folder",
        "parent_id": null
    },
    {
        "id": 33,
        "name": "Папка cor",
        "type": "folder",
        "parent_id": null
    },
    {
        "id": 3,
        "name": "Файл 1.txt",
        "type": "file",
        "parent_id": 2
    },
    {
        "id": 4,
        "name": "Папка 2",
        "type": "folder",
        "parent_id": 1
    },
    {
        "id": 5,
        "name": "Файл 2.txt",
        "type": "file",
        "content": 'asdassdadad',
        "parent_id": 4
    },
    {
        "id": 19,
        "name": "Файл 1123.txt",
        "type": "file",
        "content": '21312312313213',
        "parent_id": 2
    },
    {
        "id": 22,
        "name": "Файлkk.txt",
        "type": "file",
        "content": '21a#$*@$&@#$&2313213',
        "parent_id": 2
    },
    {
        "id": 30,
        "name": "Файлooo.txt",
        "type": "file",
        "content": '2131asdasdasdads',
        "parent_id": 2
    },
    {
        "id": 18,
        "name": "Файuu.txt",
        "type": "folder",
        "parent_id": 2
    },
    {
        "id": 37,
        "name": "Файuu.txt",
        "type": "folder",
        "parent_id": 18
    },

    {
        "id": 10,
        "name": "Файлdasdad.txt",
        "type": "file",
        "parent_id": 18
    },
    {
        "id": 29,
        "name": "Файuu.txt",
        "type": "folder",
        "parent_id": 4
    },
]);
const inActiveBar = ref([]);


const scrollBlock = ref(null);
const selectedFile = ref(null);
const choicedFolder = ref();
const contentFile = ref('Выберите файл');
const updatedContent = ref([]);
const usedElement = ref(null);
const fileTree = ref([]);

const containerOptions = ref(null);
let showMenu = ref(false);
let mouseX = ref(0);
let mouseY = ref(0);


//Добавление, удаление папок и файлов
// КОГДА БУДЕТ БД ОБЯЗАТЕЛЬНО НАДО СДЕЛАТЬ ТАК ЧТОБЫ
// id строка была убрана, а после добавления данных в бд addObject
// запросить новые данные из бд 
function addObjectArea(type) {
    const exists = files.value.some(file => file.type === "addfile" || file.type === "addfolder");
    if (!exists) {
        createInput();
    }
    else {
        const deleted = files.value.filter(file => file.type !== "addfile" && file.type !== "addfolder");
        files.value = deleted;
        createInput();
    }
    fileTree.value = buildFileTree(files.value);


    function createInput()
    {
        if (type === 'file') {
            files.value.push({
                "id": 1000,
                'name': 'new',
                "type": "addfile",
                "parent_id": choicedFolder.value ? choicedFolder.value : null,
            });
        }
        if (type === 'folder') {
            files.value.push({
                'id': 1001,
                'name': 'new',
                "type": "addfolder",
                "parent_id": choicedFolder.value ? choicedFolder.value : null,
            });
        }
    }

}
function addObject(newObject) {
    files.value = files.value.map(file => {
        if (file.id === newObject.id) {
            return {
                ...file,
                ...newObject
            };
        }
        return file;
    });
    fileTree.value = buildFileTree(files.value);
}

//Удаление
function removeFile() {
    const id = usedElement.value;
    files.value = files.value.filter(file => file.id != id);
    fileTree.value = buildFileTree(files.value);
    showMenu.value = false;
}

function changeFileName() {

}

//

//Создание иерархической  структуры файлов и папок


function buildFileTree(filesLocal = files.value, parentId = null) {
    const fileTree = [];


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
        return a.parent_id - b.parent_id;
    });

    filesLocal.forEach(file => {
        if (file.parent_id === parentId) {
            const fileNode = {
                id: file.id,
                name: file.name,
                type: file.type,
                parent_id: file.parent_id,
                children: buildFileTree(files.value, file.id)
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
function contentFileUpdate(content, id) {
    updatedContent.value[id] = content;
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
            selectedFile.value = id;
            contentFile.value = updatedContent.value[id] ? updatedContent.value[id] : findToAdd.content;
        }
        else {
            inActiveBar.value.push(findToAdd);
            selectFile(id);
            selectedFile.value = id;
            contentFile.value = updatedContent.value[id] ? updatedContent.value[id] : findToAdd.content;

        }

    }
    else {
        console.log('ERROR - Repository: func OpenFile()');
    }
}
function closeFile(id) {
    inActiveBar.value = inActiveBar.value.filter(file => file.id !== id);
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



onMounted(() => {
    fileTree.value = buildFileTree(files.value);
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
}

.container-files-block {
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


    max-width: 100px;

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
    height: calc(100% - 58px);
    border-top: 1px solid #656A6F;
    width: 100%;
}

.inner-file {
    width: 100%;
    height: 100%;
}






.container-team-block {
    width: 27%;
    box-sizing: border-box;
    background-color: #101112;
}

.team-block {
    height: calc(100vh - 80px);
}
</style>
