<template>
    <div class="container-task" draggable="true" @dragstart="onDragStart" @dragend="onDragEnd" :data-task-id="task.id">
        <div class="task-actions">
            <div class="name-mission">Gang Crud # {{ task.id_local }}</div>
            <Elipsis :id="task.id" @edit = "openTask(task, true)"/>
        </div>
        <div @click="openTask(task)" class="task-name">
            {{ task.title }}
        </div>
    </div>
</template>
<script>
import Elipsis from '../modal/Elipsis.vue';

export default {
    name: 'Task',
    props: {
        task: {
            required: true
        }
    },
    inject:['openTask'],
    methods: {
        onDragStart(e) {
            e.dataTransfer.setData('text/plain', this.task.id);
            e.dataTransfer.effectAllowed = 'move';
            this.$emit('drag-start', this.task);
        },
        onDragEnd() {
            this.$emit('drag-end');
        }
    },
    components: {
        Elipsis,
    },
}
</script>
<style scoped>
.container-task {
    transition: transform 0.1s ease, opacity 0.1s ease;
    cursor: grab;
    background-color: #161718;
    border: 1px solid #343A40;
    border-radius: 5px;
    padding: 10px 10px;
    display: flex;
    flex-direction: column;
}
.container-task.dragging {
  opacity: 0.5;
  transform: scale(0.95);
}
.task-actions {
    display: flex;
    justify-content: space-between;
}

.name-mission {
    font-size: 13px;
    color: #7B7B7B;
}

.task-name {
    width: 100%;
    overflow: hidden;
    text-overflow: ellipsis;
    user-select: none;
    cursor: pointer;
    margin-top: 5px;
    color: #F8F9FA;
    font-size: 15px;
}

.task-name:hover {
    text-decoration: underline;
}
</style>