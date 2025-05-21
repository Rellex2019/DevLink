<template>
    <transition name="swipe">
        <button v-if="isVisible" @click="choiceFile" :class="{ active: isActive }" class="active-block"
            ref="activeBlock">
            <div class="container-active-task">
                <!-- Здесь активные задачи -->
                <div class="active-task">

                    <div class="task-text">
                        <img v-if="props.file.type == 'file'" class="file-svg" :src="fileSvg" alt="SVG файла">
                        <div class="name">{{ fileName }}<span :style="{ color: '#EDB200' }">{{ fileExtension }}</span>
                        </div>
                    </div>
                    <div @click.stop="closeFile()" class="container-close">
                        <svg width="13" class="close-svg" height="13" viewBox="0 0 13 13" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path d="M1 1L6.5 6.5M12 12L6.5 6.5M6.5 6.5L1 12M6.5 6.5L12 1" class="close-svg"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                        </svg>
                    </div>
                </div>
                <!--  -->
            </div>
        </button>
    </transition>

</template>

<script setup>
import { onMounted, ref, watch } from 'vue';
import fileSvg from '@/svg/file.svg';
const props = defineProps({
    file: {
        type: Object,
        required: true
    },
    fileTree: {
        type: Array,
        required: true
    },
    isActive: Boolean,
    updatedContent: String,
})
let fileName = ref('');
let fileExtension = ref('');
let isVisible = ref(true);
let activeBlock = ref(null);
const fileData = ref();

const fileTree = ref(props.fileTree);

const updatedScript = ref(props.updatedContent);

watch(() => props.updatedContent, (newContent) => {
    updatedScript.value = newContent;
});
watch(() => props.fileTree, (newContent) => {
    checkExists(fileTree.value, newContent);
    fileTree.value = newContent;
    newContent.map(file => {
        if (file.id == fileData.value.id) {
            fileData.value = file;
        }
    })
});
watch(() => props.file, (newContent) => {
    fileData.value = newContent;
});
watch(()=> fileData.value, (newData)=>{
    splitTitle();
})

const emit = defineEmits(); // Определяем emit
function choiceFile() {
    emit('select', fileData.value.id);

    if (updatedScript.value) {
        emit('read-content', updatedScript.value, fileData.value.id);
    }
    else {
        emit('read-content', fileData.value.content, fileData.value.id);
    }
    // activeBlock.value.style.backgroundColor= "#202123";
}
function closeFile(usedId) {
    isVisible.value = false;
    const id = usedId ? usedId : fileData.value.id;
    setTimeout(() => {
        emit('read-content', '', null);
        emit('close-file', id);
    }, 400)

}


function extractIds(fileTree) {
    const ids = [];
    fileTree.forEach(file => {
        ids.push(file.id);
        if (file.children && file.children.length > 0) {
            ids.push(...extractIds(file.children));
        }
    });
    return ids;
}

function checkExists(oldFileTree, newFileTree) {

    const oldIds = extractIds(oldFileTree);
    const newIds = extractIds(newFileTree);

    const deletedFiles = oldIds.filter(id => !newIds.includes(id));

    deletedFiles.forEach(deletedId => {
        if (deletedId === fileData.value.id) {
            closeFile(fileData.value.id);
        }
    });
}





function splitTitle() {
    let title = fileData.value.name;
    const parts = title.split('.');
    fileName.value = parts.slice(0, -1).join('.');
    if (fileName.value === '') {
        fileName.value = parts[parts.length - 1];
    }
    else {
        fileExtension.value = '.' + parts[parts.length - 1];
    }
}

onMounted(() => {
    fileData.value = props.file;
    splitTitle();;
})
</script>

<style scoped>
.name {
    white-space: nowrap;
}

.file-svg {
    height: 14px;
    width: 13px;
    margin-right: 5px;
}

.active-block {
    height: 51px;
    background: none;
    cursor: pointer;

    padding: 15px 0px 15px 15px;
    border: none;
    border-right: 1px solid #656A6F;
}

.active-block.active {
    background-color: #202123;
}

.active-task {
    display: flex;
    justify-content: space-between;
}

.task-text {
    display: flex;
    align-items: center;
    font-family: Fira Code;
    font-size: 16px;
    padding-right: 10px;
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

}


.swipe-enter-active,
.swipe-leave-active {
    opacity: 0;
    transform: translateX(-15px);
    transition: opacity 0.3s ease, transform 0.3s ease;
}

.swipe-enter,
.swipe-leave-to {
    opacity: 0;
    transform: translateX(-15px);
}

.swipe-enter-to,
.swipe-leave {
    opacity: 1;
    transform: translateX(0px);
}
</style>