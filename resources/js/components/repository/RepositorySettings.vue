<template>
    <div class="settings-container">
        <div class="settings-panel">

            <div class="team-delete-container">
                <div class="danger-container">
                    <div>
                        <p class="title-delete">Переименование репозитория</p>
                        <p class="ps">Изменяет название репозитория. Это влечет за собой репенос адреса для доступа к
                            нему.</p>
                    </div>
                    <button class="delete-team-btn" @click="handleChangeName">Переименовать</button>
                </div>
                <div class="danger-container">
                    <div>
                        <p class="title-delete">Изменение видимости</p>
                        <p class="ps">Изменяет правило приватности этого репозитория.</p>
                    </div>
                    <div style="position: relative;">
                        <button class="delete-team-btn" @click="isVisibilityModalVisible = true">Изменить видимость

                        </button>
                        <transition name="modal">
                            <div v-if="isVisibilityModalVisible" class="container-visibility">
                                <div class="container-custom-select">
                                    <div class="custom-select">
                                        <button @click="changeVisibility(currentVisibility)">Изменить на {{
                                            currentVisibility === 'public' ? 'приватный' : 'публичный'
                                        }}</button>
                                    </div>
                                </div>
                            </div>
                        </transition>
                    </div>
                </div>



                <div class="danger-container">
                    <div>
                        <p class="title-delete">Передача прав владельца</p>
                        <p class="ps">Передайте этот репозиторий другому пользователю.</p>
                    </div>
                    <button class="delete-team-btn" @click="isOwnerModalVisible = true">Передать</button>
                </div>
                <div class="danger-container">
                    <div>
                        <p class="title-delete">Удалить этот репозиторий</p>
                        <p class="ps">После удаления репозитория пути назад уже не будет. Будьте уверены.</p>
                    </div>
                    <button class="delete-team-btn" @click="deleteRepository">Удалить репозиторий</button>
                </div>
            </div>
        </div>
    </div>
    <transition name="modal">


        <div v-if="isNameModalVisible" class="invite-modal-container">
            <div class="invite-modal">
                <div @click="closeNameModal" class="close-cont">
                    <svg width="13" height="13" viewBox="0 0 13 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path class="close-modal" d="M1 1L6.5 6.5M12 12L6.5 6.5M6.5 6.5L1 12M6.5 6.5L12 1"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                    </svg>
                </div>

                <p>Изменение названия репозитория</p>
                <div class="search-with-btn">
                    <input class="new-name-input" v-model="nameInput" type="text">
                    <button @click="changeName" :disabled="checkСonditions" ref="sendInviteBtn">Переименовать</button>
                </div>
                <li class="error">ТОЛЬКО буквы латиницы и цифры</li>
                <li class="error">Длина не меньше 3 символов</li>

            </div>
        </div>
    </transition>


    <transition name="modal">
        <div v-if="isOwnerModalVisible" class="invite-modal-container">
            <div class="invite-modal">
                <div @click="closeNameModal" class="close-cont">
                    <svg width="13" height="13" viewBox="0 0 13 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path class="close-modal" d="M1 1L6.5 6.5M12 12L6.5 6.5M6.5 6.5L1 12M6.5 6.5L12 1"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                    </svg>
                </div>



                <p>Передача прав владельца <span v-if="projectInfo">{{ projectInfo.name }}</span></p>
                <div class="search-with-btn">
                    <Search :svg="false" style="width: 100%;" placeholderText="Имя пользователя"
                        @write-input="searchPeople" @clear-input="currentUser = null" @focus-input="inputInFocus = true"
                        :content="ownerUserName" />
                    <button @click="changeOwner" :disabled="currentUser ? false : true"
                        ref="sendInviteBtn">Пригласить</button>
                </div>
                <div v-if="filteredPeople.length && inputInFocus" class="user-list-container">
                    <div class="user-list">
                        <div class="user" @click="handleSelectPeople(user)" v-for="user in filteredPeople"
                            :key="user.id">
                            <div class="img-cont">
                                <img :src="user.avatar" alt="">
                            </div>
                            <div class="name">{{ user.name }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </transition>

</template>
<script>
import axios from 'axios';
import Search from '../input/Search.vue';
import { mapGetters } from 'vuex/dist/vuex.cjs.js';

export default {
    name: 'RepositorySettings',
    data() {
        return {
            projectId: null,
            projectInfo: null,
            isNameModalVisible: false,
            isVisibilityModalVisible: false,
            isOwnerModalVisible: false,
            nameInput: '',
            currentVisibility: null,

            filteredPeople: [],
            currentUser: null,
            inputInFocus: false,
            ownerUserName: '',
        }
    },
    methods: {

        fetchProjectInfo() {
            axios.get(`/projects/${this.$route.params.repositoryName}`)
                .then(response => {
                    this.projectId = response.data.id;
                    this.projectInfo = response.data;
                    this.currentVisibility = response.data.access;
                })
        },

        handleChangeName() {
            this.isNameModalVisible = true;
        },
        closeNameModal() {
            this.isNameModalVisible = false;
            this.isVisibilityModalVisible = false,
                this.isOwnerModalVisible = false,
                this.filteredPeople = [];
            this.nameInput = '';
            this.currentUser = null;
            this.ownerUserName = '';
        },
        changeName() {
            const success = confirm('Вы уверены, что хотите переименовать на ' + this.nameInput);
            if (success) {
                axios.put(`/project/setting/${this.projectId}/name/change`, { name: this.nameInput })
                    .then(response => {
                        this.$router.push({
                            name: 'repositorySettings', params: {
                                'user': response.data.owner_name,
                                'repositoryName': response.data.name,
                            }
                        });
                        this.$showAlert(`Имя репозитория успешно сменено на ${response.data.name}`, 'accept');
                    })
            }
        },

        changeVisibility(access) {
            if (access === 'public') {
                this.currentVisibility = 'private'
            }
            else {
                this.currentVisibility = 'public'
            }
            axios.put(`/project/setting/${this.projectId}/visibility/change`, { visibility: this.currentVisibility })
                .then(response => {
                    this.currentVisibility = response.data.access;
                    this.$showAlert(`Модификатор видимости репозитория изменён на ${response.data.access}`, 'accept');
                    this.isVisibilityModalVisible = false;
                })
        },
        async searchPeople(text) {
            this.ownerUserName = text;
            this.currentUser = null;

            if (this.ownerUserName.length > 1) {
                try {
                    const response = await axios.get('/user/search', {
                        params: { query: this.ownerUserName }
                    });
                    this.filteredPeople = response.data;
                } catch (error) {
                    console.error('Ошибка при поиске человека:', error);
                }
            } else {
                this.filteredPeople = [];
            }

        },
        handleSelectPeople(user) {
            this.currentUser = user.id;
            this.ownerUserName = user.name;
            this.inputInFocus = false;
            this.filteredPeople = this.filteredPeople.filter(u => {
                return u.id == user.id
            });
        },
        changeOwner() {
            const success = confirm('Вы уверены, что хотите БЕЗВОЗВРАТНО ПЕРЕДАТЬ права владельца репозитория ' + this.$route.params.repositoryName);
            if (success) {
                axios.put(`/project/setting/${this.projectId}/owner/change`, { owner: this.currentUser })
                    .then(response => {
                        this.currentVisibility = response.data.access;
                        this.$showAlert(`Права владельца переданы ${response.data.owner_name}`, 'accept');
                        this.closeNameModal();

                        this.$router.push({
                            name: 'repositorySettings', params: {
                                'user': response.data.owner_name,
                                'repositoryName': response.data.name,
                            }
                        });
                    })
            }

        },
        deleteRepository() {
            axios.delete(`/project/setting/${this.projectId}`)
            .then(response=>{
                this.$showAlert(`Репозиторий был безвозвратно удален`);
                this.$router.push({name: 'profile', params:{
                        'user': this.user.name
                    }
                })
            });
        },

    },
    computed: {
        checkСonditions() {
            const regex = /^[a-z0-9.]+$/i;
            if(this.nameInput.length > 3 && regex.test(this.nameInput)) return false
            else{ return true};
        },
        ...mapGetters('authStore', ['isAuthenticated', 'user']),
    },
    components: {
        Search
    },
    mounted() {
        this.fetchProjectInfo();
    }
}
</script>
<style scoped>
.settings-container {
    font-family: 'Montserrat';
    flex: 1;
    color: #F8F9FA;
    display: flex;
    justify-content: center;
    background-color: #161718;
}

.settings-panel {
    width: 60%;
    max-width: 1600px;
    display: flex;
    flex-direction: column;
    gap: 5%;
    padding: 20px;
}

.team-delete-container {
    color: #F8F8F8;
    border: 1px solid #FF0E0E;
    border-radius: 5px;
    padding: 15px;
    display: flex;
    flex-direction: column;
    gap: 30px;
}

.danger-container {
    display: flex;
    justify-content: space-between;
}

.title-delete {
    font-weight: 500;
}

.ps {
    margin-top: 10px;
    font-size: 17px;
}

.delete-team-btn {
    cursor: pointer;
    white-space: nowrap;
    padding: 10px 15px;
    font-size: 16px;
    font-weight: 500;
    background: none;
    border: 1px solid #FF0E0E;
    border-radius: 5px;
    color: #FF0E0E;
    height: fit-content;
    align-self: center;
}

.delete-team-btn:hover {
    background-color: #FF0E0E20;
}







.invite-modal-container {
    font-family: 'Montserrat';
    background-color: #00000050;
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    display: flex;
    justify-content: center;
    align-items: center;
}

.invite-modal {
    position: relative;
    color: #ddd;
    background-color: #101112;
    border: 1px solid #222325;
    border-radius: 15px;
    padding: 20px 40px;
    padding-top: 50px;
    margin-top: -200px;
    width: 500px;
    height: 185px;
    display: flex;
    flex-direction: column;
    align-items: center;
}
.invite-modal li{
    margin-top: 5px;
}
.invite-modal p {

    margin-top: 10px;
    text-align: center;
    font-weight: 500;
}

.invite-modal p span {
    color: #EDB200;
}

.search-with-btn {
    margin-top: 40px;
    width: 100%;
    display: flex;
}

.search-with-btn button {
    cursor: pointer;
    padding: 10px 20px;
    background-color: #EDB20040;
    color: #ddd;
    border: none;
    border: 1px solid #EDB20090;
}

.search-with-btn button:hover {

    background-color: #EDB20060;

}

.search-with-btn button[disabled] {
    cursor: not-allowed;
    background-color: #EDB20020;
}

.search-with-btn button[disabled]:hover {
    background-color: #EDB20020;
}

.close-cont {
    transition: 0.3s;
    cursor: pointer;
    position: absolute;
    border-radius: 5px;
    display: flex;
    justify-content: center;
    align-items: center;
    width: 30px;
    height: 30px;
    top: 10px;
    right: 10px;
}

.close-cont:hover {
    background-color: #4B4F53;
}

.close-modal {
    transition: 0.3s;
    color: #4B4F53;
}

.close-cont:hover .close-modal {
    color: #ddd;
}

.user-list-container {
    margin-top: 5px;
    align-items: flex-end;
    width: 100%;
    height: 50px;
}

.user-list {
    flex-direction: row;
    width: 50%;
    background-color: #101112;
    border: 1px solid #343A40;
    border-radius: 5px;
}

.user {
    cursor: pointer;
    padding: 7px 20px;
    display: flex;
    gap: 15px;
    align-items: center;
}

.user:hover {
    background-color: #343A40;
}

.img-cont {
    flex: none;
    width: 30px;
    height: 30px;
}

.img-cont img {
    width: 100%;
    height: 100%;
}

.name {
    font-size: 16px;
    overflow: hidden;
    text-overflow: ellipsis;
}

.modal-enter-active,
.modal-leave-active {
    transition: all 0.3s ease;
}

.modal-enter-from,
.modal-leave-to {
    opacity: 0;
}

.modal-enter-active .invite-modal-wrapper,
.modal-leave-active .invite-modal-wrapper {
    transition: all 0.3s ease;
}

.modal-enter-from .invite-modal-wrapper,
.modal-leave-to .invite-modal-wrapper {
    transform: translateY(-20px);
}



.new-name-input {
    width: 100%;
    color: #ddd;
    border-radius: 8px 0px 0px 8px;
    padding: 5px 15px;
    font-size: 16px;
    background: none;
    border: 1px solid #EDB200;
    border-right: 0px;
    font-family: 'Montserrat';
}

.new-name-input:focus {
    outline-width: 0px;
}

.error {
    font-size: 12px;
    color: #FF0E0E;
    align-self: flex-start;
    font-weight: 400 !important;
}







.container-visibility {
    position: absolute;
    right: 0;
    top: 40px;
}

.custom-select {
    display: flex;
    flex-direction: column;
}

.custom-select button {
    cursor: pointer;
    font-family: 'Montserrat';
    border-radius: 15px;
    font-weight: 500;
    font-size: 14px;
    padding: 12px 25px;
    background: #101112;
    border: 1px solid #4B4F53;
    color: #ddd;
}

.custom-select button:hover {
    background: #161718;
}
</style>