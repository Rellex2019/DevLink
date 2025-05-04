<template>


    <div class="container-status" @dragover.prevent="onDragOver" @dragenter.prevent="onDragEnter"
        @dragleave="onDragLeave" @drop="onDrop" :class="{ 'drag-over': isDragOver }">
        <div class="status-info">
            <div class="container-action">

                <div style="display: flex; gap: 7px; position: relative; align-items: center; width: fit-content;;">
                    <div class="status-color" style=" background-color: #00823025; border: 1px solid #008230;"></div>
                    <div class="status-name">{{ status }}</div>
                    <div class="task-count">0</div>
                </div>


                <Elipsis />

            </div>
            <div class="ps">Задачи которые надо выполнить</div>
        </div>

        <div class="container-tasks" ref="scrollContainer" :style="{ paddingRight: hasScroll ? '7px' : '0' }">
            <Task v-for="task in tasks" :task="task" />
        </div>


        <button class="add-task-btn"><span>+</span> Добавить задачу</button>
    </div>
</template>
<script>
import Elipsis from '../modal/Elipsis.vue';
import Task from './Task.vue';

export default {
    name: 'MissionPanel',
    props: {
        tasks: {
            required: true,
        },
        status: {
            type: String,
            required: true
        }
    },
    components: {
        Elipsis,
        Task
    },
    data() {
        return {
            observer: null,
            isDragOver: false,
            hasScroll: false
        }
    },
    mounted() {
        this.checkScroll();
        window.addEventListener('resize', this.checkScroll);
        this.observer = new MutationObserver(() => {
            this.checkScroll();
        });

        this.observer.observe(this.$refs.scrollContainer, {
            childList: true, 
            subtree: true 
        });
    },
    beforeDestroy() {
        window.removeEventListener('resize', this.checkScroll);
        if (this.observer) {
            this.observer.disconnect();
        }
    },
    methods: {
        checkScroll() {
            const container = this.$refs.scrollContainer;
            this.hasScroll = container.scrollHeight > container.clientHeight;
        },


        onDragOver(e) {
            e.dataTransfer.dropEffect = 'move';
            this.isDragOver = true;
        },
        onDragEnter() {
            this.isDragOver = true;
        },
        onDragLeave() {
            this.isDragOver = false;
        },
        onDrop(e) {
            this.isDragOver = false;
            const taskId = e.dataTransfer.getData('text/plain');
            this.$emit('task-dropped', {
                taskId: parseInt(taskId),
                newStatus: this.status
            });
        }
    }
}

</script>
<style scoped>
.container-status {
    width: clamp(200px, 20vw, 560px);
    padding: 18px 18px;
    padding-bottom: 50px;
    border: 1px solid #343A40;
    border-radius: 5px;
    position: relative;

}

.container-status.drag-over {
    background-color: #1e1f20;
    border: 1px dashed #EDB200;
}

.container-action {
    display: flex;
    justify-content: space-between;
}

.status-color {
    width: 15px;
    height: 15px;
    border-radius: 7.5px;
}

.status-name {
    font-size: 18px;
    font-family: 'Fira Code';
}

.task-count {
    position: absolute;
    padding: 3px 6px;
    background-color: #343A40;
    border: 1px solid #161718;
    border-radius: 15px;
    font-size: 12px;
    right: -30px;
    top: -8px;
}



.ps {
    margin-top: 5px;
    font-size: 16px;
    font-family: 'Fira Code';
    color: #7B7B7B;
}

.container-tasks {
    margin-top: 15px;
    display: flex;
    flex-direction: column;
    gap: 10px;
    overflow-y: auto;
    box-sizing: border-box;
    min-height: 50px;
    max-height: 450px;
}

.container-tasks::-webkit-scrollbar {
    width: 7px;
}

.container-tasks::-webkit-scrollbar-track {
    background-color: #343A40;
    border-radius: 10px;
}

.container-tasks::-webkit-scrollbar-thumb {
    background-color: #161718;
    border-radius: 10px;
}

.container-tasks::-webkit-scrollbar-thumb:hover {
    background-color: #222325;
}


.add-task-btn {
    cursor: pointer;
    width: 100%;
    font-family: 'Fira Code';
    position: absolute;
    bottom: 0;
    left: 0;
    padding: 5px 15px;
    gap: 8px;
    display: flex;
    align-items: center;
    background: none;
    color: #7B7B7B;
    border: none;
    font-size: 18px;
    border-radius: 0 0 5px 5px;
}

.add-task-btn span {
    font-size: 22px;
}

.add-task-btn:hover {
    background-color: #161718;
}

.add-task-btn:active {
    background-color: #0f1011;
}
</style>