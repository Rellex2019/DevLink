<template>

    <div @click.self="closeModal" class="container-modal-task">
        <div @click.stop class="modal-task">
            <div class="container-task-info">
                <div class="back" @click="closeModal">
                    <img class="arrow1" src="@/svg/arrow-folder.svg" alt="">
                    <img class="arrow2" src="@/svg/arrow-folder.svg" alt="">
                </div>
                <div class="task-info">
                    <div class="name">{{ task.title }} #{{ task.id }}</div>
                    <div class="add-info">
                        <div class="container-status">
                            <div class="circle-color" style="border: 1px solid #008230; background-color: #00823025;">
                            </div>
                            <div class="status-name">{{ task.status }}</div>
                        </div>
                        <div class="container-status">
                            <div class="repository-info">User/Repository</div>
                            <div class="access">Public</div>
                        </div>
                    </div>

                </div>
                <div class="actions-task">
                    <button @click="handleChange">{{ editMode ? 'Отмена' : 'Изменить' }}</button>
                    <button class="delete-btn" @click="handleDelete(task.id)">Удалить</button>
                </div>
            </div>
            <div class="container-comments">
                <div class="container-description">
                    <div class="container-avatar">
                        <img src="@/svg/default-avatar.svg" alt="">
                    </div>
                    <div class="container-text">
                        <div class="container-info">
                            <div class="user-name">Имя</div>
                            <div class="date">позавчера</div>
                        </div>
                        <div class="text">
                            {{ task.description }}
                        </div>
                    </div>
                </div>
                <div class="container-comment">

                    <div @click="editMode = true" class="title">{{ editMode ? 'Внести изменения' : 'Добавить комментарий' }}
                    </div>
                    <div class="container-text comment">
                        <div class="container-action">
                            <input v-if="editMode" v-model="task.title" class="input-title">
                            <div v-else style="font-size: 15px;">Написать</div>
                        </div>
                        <textarea v-if="editMode" v-model="task.description" class="text"
                            placeholder="Описание..."></textarea>
                        <textarea v-else class="text"
                            placeholder="Здесь вы можете дополнить задание комментарием"></textarea>
                    </div>
                    <button v-if="editMode" @click="handleSave" class="sent-btn" type="submit">Сохранить</button>
                    <button v-else class="sent-btn" type="submit">Отправить</button>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    name: 'TaskModal',
    inject: ['deleteTask'],
    data() {
        return {
            task: {...this.taskInfo},
            editMode: this.edit ?? false
        }
    },
    props: {
        taskInfo: {
            required: true
        },
        edit: {
            required: false,
            type: Boolean
        }
    },
    methods: {
        closeModal() {
            this.$emit('close-modal');
            if (this.editMode) {
                this.task = {...this.taskInfo};
            }
        },
        handleDelete(taskId) {
            this.deleteTask(taskId);
        },
        handleChange() {
            if (this.editMode) {
                this.task = {...this.taskInfo};
            }
            this.editMode = !this.editMode;

        },
        handleSave()
        {
            this.editMode = false;
            this.$emit('save-task', this.task);
        }

    }
}
</script>
<style scoped>
.container-modal-task {
    position: absolute;
    top: 0;
    bottom: 0;
    left: 0;
    right: 0;
    display: flex;
    justify-content: center;
    align-items: center;
    background-color: #00000040;

}

.modal-task {
    width: 50vw;
    padding: 50px 40px;
    box-shadow: 0px 0px 7px #343A40;
    border-radius: 20px;
    background-color: #161718;

}


.container-task-info {
    position: relative;
    display: flex;
    justify-content: space-between;
    border-bottom: 1px solid #7B7B7B;
    padding-bottom: 15px;
}

.task-info {
    display: flex;
    flex-direction: column;
    gap: 5px;
}

.name {
    font-size: 24px;
    max-width: 650px;
    word-break: break-all;
}

.add-info {
    display: flex;
    align-items: center;
    gap: 15px;
}

.container-status {
    width: fit-content;
    display: flex;
    align-items: center;
    gap: 5px;
    padding: 9px 10px;
    border: 1px solid #656A6F;
    border-radius: 25px;
}

.circle-color {
    width: 15px;
    height: 15px;
    border-radius: 7.5px;
}

.status-name {
    margin-top: -3px;
    font-family: 'Fira Code';
    font-size: 18px;
}

.repository-info {
    font-size: 15px;
}

.access {
    font-family: 'Fira Code';
    font-size: 10px;
    border-radius: 13px;
    padding: 4px 10px;
    background-color: #343A40;
}

.actions-task {
    display: flex;
    gap: 10px;
}

.actions-task button {
    font-family: 'Montserrat';
    cursor: pointer;
    border-radius: 5px;
    padding: 7px 15px;
    height: fit-content;
    background-color: #101112;
    border: 1px solid #343A40;
    font-size: 13px;
    color: #FFF;
}

.delete-btn {
    color: #FF0E0E !important;
    border: 1px solid #FF0E0E !important;
}

.actions-task button:hover {
    background-color: #313436;
}



.back {
    position: absolute;
    top: -35px;
    right: -25px;
    cursor: pointer;
}

.back img {
    transition: 0.3s;
}

.back:hover .arrow1 {
    opacity: 0;
}

.back:hover .arrow2 {
    transform: rotate(0deg);
}

.arrow2 {
    margin-left: -2px;
    transform: rotate(180deg);
}


.container-description {
    margin-top: 25px;
    display: flex;
    gap: 20px;
}

.container-avatar {
    width: 50px;
    height: 50px;
    overflow: hidden;
    border-radius: 25px;
    border: 1px solid #313436;
}

.container-avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.container-text {
    flex: 1;
    border: 1px solid #EDB200;
    border-radius: 10px;
}

.user-name {
    font-size: 15px;
    font-weight: bold;
}

.date {
    font-size: 10px;
    margin-bottom: -10px;
}

.container-info,
.container-action {
    display: flex;
    width: 100%;
    background-color: #EDB20030;
    border-bottom: 1px solid #EDB200;
    border-radius: 10px 10px 0 0;
    gap: 5px;
    min-height: 35px;
    align-items: center;
    padding: 8px 15px;
    box-sizing: border-box;
}

.text {
    box-sizing: border-box;
    background: none;
    width: 100%;
    flex: 1;
    color: white;
    font-size: 13px;
    padding: 10px 10px;
    outline: 0px;
    resize: none;
    border: none;
}

.container-text textarea {
    min-height: 100px;
}

.container-comment {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.container-comments {
    display: flex;
    flex-direction: column;
    gap: 40px;
}

.sent-btn {
    cursor: pointer;
    font-family: 'Montserrat';
    margin-top: -10px;
    padding: 8px 20px;
    color: #FFF;
    font-weight: bold;
    border-radius: 10px;
    border: 1px solid #EDB200;
    background-color: #EDB20020;
    align-self: flex-end;
    width: fit-content;
}

.sent-btn:hover {
    background-color: #EDB20050;
}

.sent-btn:active {
    background-color: #EDB20040;
}

.container-action {
    background-color: #7B7B7B25;
    border-bottom: 1px solid #7B7B7B;
    padding: 8px 10px;
}

.comment {
    border: 1px solid #7B7B7B;
}

.input-title {
    font-size: 15px;
    flex: 1;
    background: none;
    border: none;
    outline: none;
    color: #FFF;
}
</style>