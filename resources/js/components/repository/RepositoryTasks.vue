<template>
    <div class="tasks-container">
        <div class="task-panel">
            <div class="select-active-panel">
                <button class="select-panel-btn" :class="{ 'active-btn': activePanel === 'teams' }"
                    @click="activePanel = 'teams'">
                    <svg aria-hidden="true" height="16" viewBox="0 0 16 16" version="1.1" width="16"
                        data-view-component="true" class="octicon octicon-table color-fg-default mr-1">
                        <path fill="#FFFFFF"
                            d="M0 1.75C0 .784.784 0 1.75 0h12.5C15.216 0 16 .784 16 1.75v12.5A1.75 1.75 0 0 1 14.25 16H1.75A1.75 1.75 0 0 1 0 14.25ZM6.5 6.5v8h7.75a.25.25 0 0 0 .25-.25V6.5Zm8-1.5V1.75a.25.25 0 0 0-.25-.25H6.5V5Zm-13 1.5v7.75c0 .138.112.25.25.25H5v-8ZM5 5V1.5H1.75a.25.25 0 0 0-.25.25V5Z">
                        </path>
                    </svg>
                    <div>Задачи</div>
                </button>
                <button class="select-panel-btn" :class="{ 'active-btn': activePanel === 'add-team' }"
                    @click="activePanel = 'add-team'">
                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M8 1V8M8 15V8M8 8H1M8 8H15" stroke="#ffffff" stroke-width="1.5"
                            stroke-linecap="round" />
                    </svg>
                    <div>Добавить команду</div>
                </button>
            </div>

            <div class="teams-container" v-if="activePanel === 'teams'">
                <Search placeholderText="Введите название команды" @write-input="handleSearchInput" />
                <div class="teams">
                    <div class="team" v-for="team in filteredTeams">
                        <div class="task-info">
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <svg aria-hidden="true" height="16" viewBox="0 0 16 16" version="1.1" width="16"
                                    data-view-component="true" class="octicon octicon-table color-fg-default mr-1">
                                    <path fill="#FFFFFF"
                                        d="M0 1.75C0 .784.784 0 1.75 0h12.5C15.216 0 16 .784 16 1.75v12.5A1.75 1.75 0 0 1 14.25 16H1.75A1.75 1.75 0 0 1 0 14.25ZM6.5 6.5v8h7.75a.25.25 0 0 0 .25-.25V6.5Zm8-1.5V1.75a.25.25 0 0 0-.25-.25H6.5V5Zm-13 1.5v7.75c0 .138.112.25.25.25H5v-8ZM5 5V1.5H1.75a.25.25 0 0 0-.25.25V5Z">
                                    </path>
                                </svg>

                                <div class="team-name" @click="goToTask(team.name)">{{ team.name }}</div>
                            </div>
                            <div class="last-update"> Обновлено {{ formatDateTime(team.updated_at)  }}</div>
                        </div>
                        <div class="delete-team-container">
                            <svg @click="deleteTeamFromRep(team)" class="delete-team" width="13" height="13"
                                viewBox="0 0 13 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path class="delete-team" d="M1 1L6.5 6.5M12 12L6.5 6.5M6.5 6.5L1 12M6.5 6.5L12 1"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <div class="team-add" v-if="activePanel === 'add-team'">
                <h3>Добавить команду</h3>
                <div style="display: flex; gap: 15px;">
                    <div style="flex: 1; ">
                        <div class="search-container">
                            <Search placeholderText="Введите название команды" @focus-input="showDropdown = true"
                                @write-input="searchTeams" :class="{ 'focused': showDropdown }" />
                            <!-- Кастомный dropdown -->
                            <div class="custom-datalist" v-if="showDropdown && filteredTeamsForAdd.length > 0">
                                <div class="team-option" v-for="team in filteredTeamsForAdd" :key="team.id"
                                    @click="selectTeam(team)">
                                    <img :src="team.logo" class="team-avatar" alt="team avatar">
                                    <span class="team-name-add">{{ team.name }}</span>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="container-added-teams">
                        <p class="title-teams" v-if="inviteTeamList && inviteTeamList.length">Выбранные команды</p>
                        <p class="title-teams" v-else>Выберите команды для добавления</p>
                        <div class="container-teams">
                            <div class="team-added" v-for="team in inviteTeamList" @click="deleteTeam(team)"
                                :key="team.id">

                                <img :src="team.logo" class="team-avatar" alt="team avatar">
                                <span class="team-name-add">{{ team.name }}</span>

                            </div>
                        </div>
                        <button @click="sendInvite">Отправить приглашение</button>
                    </div>

                </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios';
import Search from '../input/Search.vue';

export default {
    name: 'RepositoryTasks',
    data() {
        return {
            teams: [],
            activePanel: 'teams', // 'teams' или 'add-team'
            inviteTeamName: '',
            searchQuery: '',


            inviteTeamName: '',
            showDropdown: false,
            filteredTeamsForAdd: [],
            inviteTeamList: [],
        }
    },
    methods: {
        fetchTeams() {
            axios.get(`/projects/${this.$route.params.repositoryName}/tasks`)
                .then(response => {
                    this.teams = response.data;
                })
        },
        fetchInvites() {
            axios.get(`/project/${this.$route.params.repositoryName}/teams/invites`)
                .then(response => {
                    response.data.map(invite => {
                        this.inviteTeamList.push(invite.team);
                    })

                })
        },
        formatDateTime(isoString) {
            const date = new Date(isoString);

            if (isNaN(date.getTime())) {
                return "Неверная дата";
            }

            const options = {
                day: 'numeric',
                month: 'long',
                hour: '2-digit',
                minute: '2-digit',
            };

            let formatted = date.toLocaleString('ru-RU', options);
            formatted = formatted.replace(',', ' в');

            return formatted;
        },
        sendInvite() {
            const teamIds = [];
            if (this.inviteTeamList.length) {
                this.inviteTeamList.map(t => {
                    teamIds.push(t.id);
                })
                axios.post(`/project/${this.$route.params.repositoryName}/teams/invite`, { team_ids: teamIds })
                    .then(response => {
                        this.$showAlert('Приглашения успешно отправлены', 'accept');
                        this.currentUser = null;
                    })
                    .catch(e => {
                        this.$showAlert('Ошибка при отправке приглаений. Попробуйте позже', 'error');
                    }
                    )
            }

        },
        goToTask(teamName) {
            this.$router.push(`/${this.$route.params.user}/${this.$route.params.repositoryName}/${teamName}/tasks`);
        },
        handleSearchInput(text) {
            this.searchQuery = text;
        },

        // async loadAllTeams() {
        //     await axios.get('/team/teams')
        //         .then(response => {
        //             this.teams = response.data;
        //         })
        // },

        async searchTeams(text) {
            this.inviteTeamName = text;
            if (this.inviteTeamName.length > 1) {
                try {
                    const response = await axios.get('/teams/search', {
                        params: { query: this.inviteTeamName }
                    });
                    this.filteredTeamsForAdd = response.data;
                } catch (error) {
                    console.error('Ошибка при поиске команд:', error);
                }
            } else {
                this.filteredTeamsForAdd = [];
            }
        },
        selectTeam(team) {
            const isAlreadyAdded = this.inviteTeamList.some(t => t.id === team.id);
            if (!isAlreadyAdded) {
                this.inviteTeamList.push(team);
            }
            this.inviteTeamName = '';

        },
        deleteTeam(teamToDelete) {
            axios.delete(`/project/${this.$route.params.repositoryName}/teams/invite/${teamToDelete.id}`)
                .then(response => {
                    this.inviteTeamList = this.inviteTeamList.filter(team => team.id !== teamToDelete.id);
                })
                .catch(e => {
                    this.inviteTeamList = this.inviteTeamList.filter(team => team.id !== teamToDelete.id);
                })
        },
        deleteTeamFromRep(team) {
            const confirm = window.confirm(`Вы уверены что хотите отозвать права для команды ${team.name}? `);
            if (confirm) {
                axios.delete(`/project/${this.$route.params.repositoryName}/team/delete/${team.id}/name`)
                    .then(response => {
                        this.teams = this.teams.filter(t => {
                            return t.id != team.id
                        })
                    })
            }
        },
        handleClickOutside(e) {
            if (!this.$el.contains(e.target)) {
                this.showDropdown = false;
            }
        }

    },


    mounted() {
        this.fetchTeams();
        this.fetchInvites();

        // this.loadAllTeams();
        document.addEventListener('click', this.handleClickOutside);
    },
    beforeDestroy() {
        document.removeEventListener('click', this.handleClickOutside);
    },
    computed: {
        filteredTeams() {
            if (!this.teams || this.teams.length === 0) return [];

            if (this.searchQuery.trim() === '') {
                return this.teams;
            }

            return this.teams.filter(t =>
                t.name.toLowerCase().includes(this.searchQuery.toLowerCase())
            );
        }
    },
    components: {
        Search,

    }
}
</script>

<style scoped>
.tasks-container {
    font-family: 'Montserrat';
    flex: 1;
    display: flex;
    justify-content: center;
    background-color: #161718;
}

.task-panel {
    max-width: 1600px;
    width: 83vw;
    display: flex;
    gap: 5%;
    padding: 20px;
}

.select-active-panel {
    width: 20%;
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.select-panel-btn {
    box-sizing: border-box;
    display: flex;
    gap: 15px;
    align-items: center;
    font-family: 'Montserrat';
    font-weight: 500;
    font-size: 16px;
    background: none;
    border-radius: 10px;
    padding: 10px 15px;
    border: none;
    border-left: 4px solid #EDB20000;
    color: #F8F9FA;
    text-align: start;
    cursor: pointer;
    transition: 0.3s;
}

.select-panel-btn:hover {
    background-color: #656A6F50;
}

.select-panel-btn.active-btn {
    background-color: #656A6F70;
    border-left: 4px solid #EDB200;
    border-radius: 0px 10px 10px 0px;
}

.teams-container {
    flex: 1;
}

.team-add {
    flex: 1;
    color: #F8F9FA;
}

.team-add h3 {
    margin-bottom: 20px;
    font-size: 20px;
}



.team-add input.focused {
    border-radius: 5px 5px 0 0;
}





.teams {
    margin-top: 20px;
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.team {
    display: flex;
    justify-content: space-between;
    border: 1px solid #656A6F80;
    padding: 15px;
    border-radius: 5px;
    color: #F8F9FA;
}

.team-name {
    cursor: pointer;
    font-weight: 500;
    font-size: 18px;
}

.team-name:hover {
    text-decoration: underline;
}

.task-info {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.last-update {
    color: #F8F9FA80;
}

.delete-team-container {
    cursor: pointer;
    width: 15px;
    height: 15px;
    color: #7B7B7B;
}

.delete-team {
    width: 100%;
    height: 100%;
    color: #7B7B7B;
}

.delete-team-container:hover .delete-team {
    color: #FF0E0E;
}



.search-container {
    position: relative;
}

.custom-datalist {
    position: absolute;
    top: 100%;
    left: 0;
    right: 0;
    max-height: 300px;
    overflow-y: auto;
    background: #161718;
    border: 1px solid #7B7B7B;
    border-radius: 0px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    z-index: 1000;
}

.team-option {
    display: flex;
    align-items: center;
    background-color: #161718;
    padding: 8px 12px;
    cursor: pointer;
    transition: background-color 0.2s;
}

.team-option:hover {
    background-color: #51545440;
}

.team-avatar {
    width: 30px;
    height: 30px;
    border-radius: 10px;
    margin-right: 10px;
    object-fit: cover;
    background-color: rgba(183, 183, 183, 0.1);
    box-shadow: 0 0px 6px rgba(183, 183, 183, 0.1);
}

.team-name {
    font-size: 14px;
}

.team-name-add {}

.container-added-teams {
    flex: 1;
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.team-add button {
    padding: 10px 15px;
    color: white;
    background: none;
    border: none;
    border: 1px solid #EDB200;
    border-radius: 5px;
    cursor: pointer;
}

.team-add button:hover {
    background-color: #EDB20030;
}

.team-add button:active {
    background-color: #EDB20010;
}

.container-teams {
    display: flex;
    flex-wrap: wrap;
    min-height: 100%;
    gap: 15px;
}

.team-added {
    display: flex;
    align-items: center;
    background-color: #252829;
    height: 30px;
    padding: 5px 10px;
    cursor: pointer;
    border-radius: 5px;
    transition: background-color 0.2s;
}

.title-teams {
    text-align: center;
    font-size: 18px;
}
</style>