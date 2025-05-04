<template>
    <div class="container-mission">
        <div class="mission">
            <div class="container-search">
                <Search :placeholderText="'Найти задачу'" />
            </div>
            <div class="container-statuses">
                <MissionPanel v-for="(tasks, status) in tasksByStatus" :key="status" :tasks="tasks" :status="status"
                    @task-dropped="handleTaskDrop" />
            </div>
        </div>
        <transition name="modal">
            <div style="z-index: 2;" v-if="Object.keys(taskModal).length">
                <TaskModal :taskInfo="taskModal" @save-task="handleSaveTask" @close-modal="handleCloseTask" />
            </div>
        </transition>
    </div>
</template>
<script>
import Search from '../components/input/Search.vue';
import Elipsis from '../components/modal/Elipsis.vue';
import TaskModal from '../components/modal/TaskModal.vue';
import MissionPanel from '../components/TaskCreate/MissionPanel.vue';

export default {
    name: 'TaskCreateView',
    components: {
        Search,
        Elipsis,
        MissionPanel,
        TaskModal
    },
    provide() {
        return {
            deleteTask: this.deleteTaskHandler,
            openTask: this.handleOpenTask
        }
    },
    data() {
        const fixedStatuses = ['todo', 'in_progress', 'review', 'done'];
        const fetchFormDB = [
            {
                "id": 1,
                "project_id": 10,
                "title": "Разработать главную страницу",
                "description": "Создать дизайн и верстку для главной страницы сайта",
                "status": "todo",
                "assigned_to": 5,
                "due_date": "2023-12-15",
                "created_at": "2023-11-01T10:00:00Z",
                "updated_at": "2023-11-01T10:00:00Z"
            },
            {
                "id": 2,
                "project_id": 10,
                "title": "Реализовать API для задач",
                "description": "Создать endpoints для работы с задачами",
                "status": "in_progress",
                "assigned_to": 3,
                "due_date": "2023-12-10",
                "created_at": "2023-11-02T09:30:00Z",
                "updated_at": "2023-11-05T14:15:00Z"
            },
            {
                "id": 3,
                "project_id": 10,
                "title": "Протестировать авторизацию",
                "description": "Провести тестирование системы входа",
                "status": "review",
                "assigned_to": 7,
                "due_date": "2023-12-05",
                "created_at": "2023-11-03T11:20:00Z",
                "updated_at": "2023-11-10T16:45:00Z"
            },
            {
                "id": 4,
                "project_id": 10,
                "title": "Исправить баг с модальным окном",
                "description": "Модальное окно не закрывается при клике вне его области",
                "status": "done",
                "assigned_to": 5,
                "due_date": "2023-11-28",
                "created_at": "2023-11-10T08:15:00Z",
                "updated_at": "2023-11-15T12:30:00Z"
            },
            {
                "id": 5,
                "project_id": 10,
                "title": "Оптимизировать загрузку изображений",
                "description": "Реализовать lazy loading для изображений",
                "status": "todo",
                "assigned_to": 3,
                "due_date": "2023-12-20",
                "created_at": "2023-11-12T13:40:00Z",
                "updated_at": "2023-11-12T13:40:00Z"
            },
            {
                "id": 6,
                "project_id": 10,
                "title": "Оптимизировать загрузку изображений",
                "description": "Реализовать lazy loading для изображений",
                "status": "todo",
                "assigned_to": 3,
                "due_date": "2023-12-20",
                "created_at": "2023-11-12T13:40:00Z",
                "updated_at": "2023-11-12T13:40:00Z"
            },
            {
                "id": 7,
                "project_id": 10,
                "title": "Оптимизировать загрузку изображений",
                "description": "Реализовать lazy loading для изображений",
                "status": "todo",
                "assigned_to": 3,
                "due_date": "2023-12-20",
                "created_at": "2023-11-12T13:40:00Z",
                "updated_at": "2023-11-12T13:40:00Z"
            }
        ]
        return {
            //запрос из БД
            tasks: fetchFormDB,
            fixedStatuses,
            customStatuses: [],
            tasksByStatus: {},



            taskModal: []
        }
    },
    methods: {
        async fetchTasks() {
            try {
                // const response = await axios.get('/api/tasks');
                // this.tasks = response.data;
                this.groupTasksByStatus();
            } catch (error) {
                console.error('Ошибка при загрузке задач:', error);
            }
        },
        groupTasksByStatus() {

            const allStatuses = [...new Set(this.tasks.map(task => task.status))];

            this.customStatuses = allStatuses.filter(
                status => !this.fixedStatuses.includes(status)
            );
            const grouped = {};

            // Сначала добавляем фиксированные статусы (даже если нет задач)
            this.fixedStatuses.forEach(status => {
                grouped[status] = [];
            });

            // Затем добавляем кастомные статусы
            this.customStatuses.forEach(status => {
                grouped[status] = [];
            });

            // Распределяем задачи
            this.tasks.forEach(task => {
                if (grouped[task.status]) {
                    grouped[task.status].push(task);
                }
            });

            this.tasksByStatus = grouped;
        },
        handleTaskDrop({ taskId, newStatus }) {
            // Находим задачу
            const taskIndex = this.tasks.findIndex(t => t.id === taskId);
            if (taskIndex === -1) return;

            // Создаем копию задачи
            const task = { ...this.tasks[taskIndex] };

            // Обновляем статус
            task.status = newStatus;
            task.updated_at = new Date().toISOString();

            // Обновляем массив задач
            this.tasks = [
                ...this.tasks.slice(0, taskIndex),
                task,
                ...this.tasks.slice(taskIndex + 1)
            ];

            // Перегруппируем задачи
            this.groupTasksByStatus();

            // Отправляем на сервер (можно добавить debounce)
            this.updateTaskOnServer(task);
        },

        async updateTaskOnServer(task) {
            try {
                // await axios.put(`/api/tasks/${task.id}`, {
                //   status: task.status
                // });
                console.log('Task status updated on server:', task);
            } catch (error) {
                console.error('Error updating task:', error);
            }
        },
        deleteTaskHandler(taskId) {
            let success = window.confirm('Вы уверены, что хотите удалить задачу?');
            if (!success) return;


            this.tasks = this.tasks.filter(task =>
                task.id != taskId
            );


            this.$showAlert('Вы успешно удалили задачу', 'accept');
            this.handleCloseTask();
            this.groupTasksByStatus();
        },
        handleOpenTask(task) {
            this.taskModal = task;

            // console.log(Object.keys(task).length);
        },
        handleCloseTask() {
            this.taskModal = [];
        },
        handleSaveTask(newTask) {
            this.tasks = this.tasks.map(task => {
                if (task.id == newTask.id) {
                    return newTask;
                }
                else{
                    return task;
                }
            }
            );
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
</style>