<template>
    <div class="container-mission">
        <div class="mission">
            <div class="container-search">
                <Search :placeholderText="'Найти задачу'" @write-input="handleSearchInput" />
            </div>
            <div class="container-statuses">
                <MissionPanel v-for="(group, index) in tasksByStatus" :key="index" :tasks="group.tasks"
                    :status="group.name" :statusId="Number(index)" :color="group.color" @task-dropped="handleTaskDrop"
                    @task-add="handleTaskAdd" />



                <div class="container-add-status" @click="handleaddStatus">
                    <svg class="add-status" width="16" height="16" viewBox="0 0 16 16" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path class="add-status" d="M8 1V8M8 15V8M8 8H1M8 8H15" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" />
                    </svg>
                </div>
            </div>

        </div>
        <transition name="modal">
            <div style="z-index: 2;" v-if="Object.keys(taskModal).length">
                <TaskModal :edit='taskEditMode'
                    :status="{ 'name': findStatusName[taskModal.status], 'color': findStatusColor[taskModal.status] }"
                    :taskInfo="taskModal" @save-task="handleSaveTask" @close-modal="handleCloseTask" />
            </div>
        </transition>
        <transition name="modal">
            <div style="z-index: 2;" v-if="Object.keys(taskCreateModal).length">
                <TaskCreateModal
                    :status="{ 'name': findStatusName[taskCreateModal.status], 'color': findStatusColor[taskCreateModal.status] }"
                    :taskInfo="taskCreateModal" @save-task="handleSaveTask" @close-modal="handleCloseTask" />
            </div>
        </transition>
    </div>

    <transition name="modal">
        <div class="status-modal" v-if="showStatusModal">
            <div class="modal-content">
                <h3>Добавить новый статус</h3>

                <div class="form-group">
                    <label>Название статуса</label>
                    <input v-model="statusInfo.name" type="text" placeholder="Введите название">
                </div>

                <div class="form-group">
                    <label>Цвет</label>
                    <input v-model="statusInfo.color" type="color">
                </div>

                <div class="modal-actions">
                    <button @click="saveNewStatus">Сохранить</button>
                    <button @click="showStatusModal = false">Отмена</button>
                </div>
            </div>
        </div>
    </transition>
</template>
<script>
import Search from '../components/input/Search.vue';
import Elipsis from '../components/modal/Elipsis.vue';
import TaskCreateModal from '../components/modal/TaskCreateModal.vue';
import TaskModal from '../components/modal/TaskModal.vue';
import MissionPanel from '../components/TaskCreate/MissionPanel.vue';

export default {
    name: 'TasksView',
    components: {
        Search,
        Elipsis,
        MissionPanel,
        TaskModal,
        TaskCreateModal
    },
    provide() {
        return {
            deleteTask: this.deleteTaskHandler,
            openTask: this.handleOpenTask,
            editStatus: this.handleEditStatus,
            deleteStatus: this.handleDeleteStatus,
        }
    },
    data() {
        return {
            projectId: [],
            teamId: [],
            statuses: [],
            tasks: [],
            taskEditMode: false,
            customStatuses: [],
            tasksByStatus: {},
            taskModal: [],
            taskCreateModal: [],
            showStatusModal: false,
            statusEdit: false,
            statusInfo: {
                name: '',
                color: '#ffffff'
            },
            search: '',
            showEmptyStatuses: true
        }
    },
    computed: {
        findStatusName() {
            const allStatuses = {};
            this.statuses.forEach(status => {
                allStatuses[status.id] = status.name;
            });
            return allStatuses;
        },
        findStatusColor() {
            const allStatuses = {};
            this.statuses.forEach(status => {
                allStatuses[status.id] = status.color;
            });
            return allStatuses;
        },
    },
    methods: {
        async fetchTasks() {
            try {
                const response = await axios.get(`/projects/${this.$route.params.repositoryName}/owner/${this.$route.params.user}/team/${this.$route.params.team}`);
                this.tasks = response.data.tasks;
                this.statuses = response.data.statuses;
                this.projectId = response.data.project_id;
                this.teamId = response.data.team_id;
                this.groupTasksByStatus();
            } catch (error) {
                console.error('Ошибка при загрузке задач:', error);
            }
        },
        groupTasksByStatus(forceAll = false) {
            if (!forceAll && this.search) {
                this.filterTasks();
                return;
            }

            const sortedStatuses = [...this.statuses].sort((a, b) => a.id - b.id);
            const grouped = {};

            sortedStatuses.forEach(status => {
                grouped[status.id] = {
                    tasks: [],
                    color: status.color,
                    name: status.name,
                    id: status.id
                };
            });

            this.tasks.forEach(task => {
                if (grouped[task.status]) {
                    grouped[task.status].tasks.push(task);
                }
            });

            this.tasksByStatus = grouped;
        },
        handleaddStatus() {
            this.statusInfo = { name: '', color: '#ffffff' };
            this.showStatusModal = true;
        },
        handleEditStatus(statusId) {
            const status = this.statuses.find(status => status.id === statusId);
            this.statusEdit = true;
            this.statusInfo = status;
            this.showStatusModal = true;
        },
        async handleDeleteStatus(statusId) {
            try {
                await axios.delete(`/projects/${this.$route.params.repositoryName}/owner/${this.$route.params.user}/team/${this.$route.params.team}/statuses/${statusId}`)

                this.statuses = this.statuses.filter(status => status.id !== statusId);
                console.log(this.statuses);
                this.groupTasksByStatus();

            } catch (error) {
                console.error('Ошибка при удалении статуса:', error);
            }
        },
        handleTaskAdd(statusId) {
            const newTask = {
                id: Date.now().toString(),
                project_id: this.projectId,
                team_id: this.teamId,
                title: "",
                description: "",
                status: statusId,
                assigned_to: null,
                due_date: null,
                created_at: new Date().toISOString(),
                updated_at: new Date().toISOString()
            };

            this.tasks.push(newTask);
            this.groupTasksByStatus();

            // Открываем модальное окно для редактирования новой задачи
            this.taskCreateModal = newTask;


        },
        async saveTaskToServer(newTask) {
            try {
                const response = await axios.post(`/projects/${this.$route.params.repositoryName}/owner/${this.$route.params.user}/team/${this.$route.params.team}/tasks`, newTask);
                return response.data;
            } catch (error) {
                console.error('Ошибка при сохранении задачи:', error);
                throw error;
            }
        },
        async updateTaskToServer(newTask) {
            try {
                const response = await axios.put(`/projects/${this.$route.params.repositoryName}/owner/${this.$route.params.user}/team/${this.$route.params.team}/tasks/${newTask.id}`, newTask);
                return response.data;
            } catch (error) {
                console.error('Ошибка при изменении задачи:', error);
                throw error;
            }
        },
        handleSearchInput(text) {
            this.search = text;
            this.filterTasks();
        },
        filterTasks() {
            if (!this.search) {
                this.groupTasksByStatus();
                return;
            }
            const searchText = this.search.toLowerCase();
            const filteredTasks = this.tasks.filter(task => {
                return (
                    task.title.toLowerCase().includes(searchText)
                )
            });
            const filteredGroups = {};
            this.statuses.forEach(status => {
                const statusTasks = filteredTasks.filter(task => task.status === status.id);
                if (statusTasks.length > 0 || this.showEmptyStatuses) {
                    filteredGroups[status.id] = {
                        tasks: statusTasks,
                        color: status.color,
                        name: status.name,
                        id: status.id
                    };
                }
            });

            this.tasksByStatus = filteredGroups;
        },



        async saveNewStatus() {
            if (!this.statusInfo.name.trim()) {
                this.$showAlert('Введите название статуса', 'error')
                return;
            }

            if (this.statusEdit) {
                this.updateStatusToServer();
                this.statusEdit = false;
            }
            else {
                await this.saveStatusToServer();
            }
            this.showStatusModal = false;
            this.groupTasksByStatus();


        },

        async saveStatusToServer() {
            try {
                await axios.post(`/projects/${this.$route.params.repositoryName}/owner/${this.$route.params.user}/team/${this.$route.params.team}/statuses`, {
                    project_id: this.projectId,
                    team_id: this.teamId,
                    name: this.statusInfo.name,
                    color: this.statusInfo.color
                })
                    .then(response => {
                        this.statuses.push(response.data)
                    })
            } catch (error) {
                console.error('Ошибка при сохранении статуса:', error);
            }
        },
        async updateStatusToServer() {
            try {
                await axios.put(`/projects/${this.$route.params.repositoryName}/owner/${this.$route.params.user}/team/${this.$route.params.team}/statuses/${this.statusInfo.id}`, {
                    name: this.statusInfo.name,
                    color: this.statusInfo.color
                })
                    .then(response => {
                        this.statuses.push(response.data)
                    })
            } catch (error) {
                console.error('Ошибка при обновлении статуса:', error);
            }
        },


        handleTaskDrop({ taskId, newStatus }) {
            console.log(newStatus);
            const taskIndex = this.tasks.findIndex(t => t.id === taskId);
            if (taskIndex === -1) return;

            const task = { ...this.tasks[taskIndex] };

            task.status = newStatus;
            task.updated_at = new Date().toISOString();
            console.log(task);
            this.tasks = [
                ...this.tasks.slice(0, taskIndex),
                task,
                ...this.tasks.slice(taskIndex + 1)
            ];

            this.groupTasksByStatus();

            this.updateTaskOnServer(task);
        },

        async updateTaskOnServer(task) {

            try {
                await axios.put(`/projects/${this.$route.params.repositoryName}/owner/${this.$route.params.user}/team/${this.$route.params.team}/tasks/${task.id}`,
                    {
                        status: task.status
                    });
                console.log('Задание перенесено:', task);
            } catch (error) {
                console.error('Ошибка в переносе задания:', error);
            }
        },
        async deleteTaskHandler(taskId, sentToDase) {
            let success = window.confirm('Вы уверены, что хотите удалить задачу?');
            if (!success) return;

            if (sentToDase) {
                try {
                    await axios.delete(`/projects/${this.$route.params.repositoryName}/owner/${this.$route.params.user}/team/${this.$route.params.team}/tasks/${taskId}`);
                } catch (error) {
                    console.error('Ошибка при удалении задачи:', error);
                    throw error;
                }
            }
            this.tasks = this.tasks.filter(task =>
                task.id != taskId
            );


            this.$showAlert('Вы успешно удалили задачу', 'accept');
            this.handleCloseTask();

            this.groupTasksByStatus();
        },
        handleOpenTask(task, editMode) {
            this.taskModal = task;
            if (editMode) {
                this.taskEditMode = true;
            }
            else {
                this.taskEditMode = false;
            }
            // console.log(Object.keys(task).length);
        },
        handleCloseTask() {
            this.taskModal = [];
            this.taskCreateModal = [];
        },
        async handleSaveTask(oldTask, action = 'create') {
            if (action == 'create') {
                const newTask = await this.saveTaskToServer(oldTask);
                this.tasks = this.tasks.map(task => {
                    if (task.id === oldTask.id) {
                        return newTask;
                    } else {
                        return task;
                    }
                });
                console.log(newTask);


            }
            else if (action == 'edit') {
                console.log(oldTask);
                const newTask = await this.updateTaskToServer(oldTask);
                this.tasks = this.tasks.map(task => {
                    if (task.id === oldTask.id) {
                        return newTask;
                    } else {
                        return task;
                    }
                });
            }
            this.groupTasksByStatus();
        }

    },
    mounted() {
        this.fetchTasks();
    }

}
</script>
<style scoped>
.container-mission {
    display: flex;
    flex-direction: column;
    padding: 0px 50px;
    background-color: #101112;
    align-items: center;
    min-height: calc(100vh - 80px);
    font-family: 'Montserrat';
    color: #FFFFFF;
}

.mission {
    width: 100%;
    padding-top: 60px;
}

.container-search {
    width: clamp(200px, 27vw, 520px);
}

.container-statuses {
    overflow-x: auto;
    display: flex;
    gap: 15px;
    margin-top: 40px;
}



.modal-enter-active,
.modal-leave-active {
    transition: opacity 0.3s ease;
}

.modal-enter-from,
.modal-leave-to {
    opacity: 0;
}

.modal-content-enter-active,
.modal-content-leave-active {
    transition: all 0.3s ease;
}

.modal-content-enter-from {
    opacity: 0;
    transform: translateY(-20px);
}

.modal-content-leave-to {
    opacity: 0;
    transform: translateY(20px);
}

.container-add-status {
    flex: none;
    cursor: pointer;
    width: 30px;
    height: 30px;
    border: 1px solid #343A40;
    border-radius: 5px;
    display: flex;
    justify-content: center;
    align-items: center;
}

.add-status {
    width: 15px;
    height: 15px;
    color: #343A40;
}

.container-add-status:hover {
    border-color: #EDB200;
}

.container-add-status:hover .add-status {
    color: #EDB200;
}



.status-modal {
    font-family: 'Montserrat';
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: rgba(0, 0, 0, 0.5);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 1000;
}

.modal-content {
    background-color: #1e1e1e;
    padding: 20px;
    border-radius: 8px;
    width: 400px;
    max-width: 90%;
}

.modal-content h3 {
    margin-top: 0;
    color: #fff;
}

.form-group {
    margin-top: 5px;
    margin-bottom: 15px;
}

.form-group label {
    display: block;
    margin-bottom: 5px;
    color: #ccc;
}

.form-group input[type="text"] {
    box-sizing: border-box;
    width: 100%;
    padding: 8px;
    background: #2d2d2d;
    border: 1px solid #444;
    color: #fff;
    border-radius: 4px;
}

.form-group input[type="color"] {
    width: 50px;
    height: 30px;
    cursor: pointer;
}

.modal-actions {
    display: flex;
    justify-content: flex-end;
    gap: 10px;
    margin-top: 20px;
}

.modal-actions button {
    font-family: 'Montserrat';
    font-weight: 500;
    padding: 8px 16px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
}

.modal-actions button:first-child {
    background-color: #EDB20050;
    border: 1px solid #EDB200;
    box-sizing: border-box;
    color: white;
}

.modal-actions button:last-child {
    background-color: #f44336;
    color: white;
}
</style>