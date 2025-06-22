<template>
    <div class="container-search">
        <div class="search-block">
            <div class="search-container">
                <div class="search">
                    <Search :content="searchQuery" :placeholderText="'Найти иформацию'" style="flex: 1;" @write-input="handleSearchInput"
                        @enter-input="fetchInfo" />
                </div>
            </div>
            <div class="container-chaters">
                <div class="infobar-chapter">
                    <div class="container-left-block">
                        <button class="select-panel-btn" @click="accessFilter = 'users'"
                            :class="{ 'active': accessFilter === 'users' }">Пользователи <span
                                v-html="searchInfo.users ? searchInfo.users.length : 0"></span></button>
                        <button class="select-panel-btn" @click="accessFilter = 'repositories'"
                            :class="{ 'active': accessFilter === 'repositories' }">Репозитории <span
                                v-html="searchInfo.repositories ? searchInfo.repositories.length : 0"></span></button>
                        <button class="select-panel-btn" @click="accessFilter = 'teams'"
                            :class="{ 'active': accessFilter === 'teams' }">Команды <span
                                v-html="searchInfo.teams ? searchInfo.teams.length : 0"></span></button>
                    </div>
                </div>
                <div class="infout-chapter">
                    <div class="repository-block" v-if="accessFilter == 'users'">

                        <div v-if="searchInfo.users && searchInfo.users.length" class="container-repository">
                            <div v-for="user in searchInfo.users" :key="user.id">
                                <div class="repository">
                                    <div class="repository-info">
                                        <div class="repository-name" @click="$router.push({
                                            name: 'profile',
                                            params: {
                                                'user': user.name
                                            }
                                        })">
                                            <img class="img-container" :src="user.avatar">
                                            <span>{{ user.name }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div v-else class="empty-message">
                            По вашему запросу, не был найден не один пользователь
                        </div>
                    </div>
                    <div class="repository-block" v-if="accessFilter == 'repositories'">

                        <div v-if="searchInfo.repositories && searchInfo.repositories.length"
                            class="container-repository">
                            <div v-for="repository in searchInfo.repositories" :key="repository.id">
                                <div class="repository">
                                    <div class="repository-info">
                                        <div class="repository-name" @click="$router.push({name: 'repository',
                                            params:{
                                                'user': repository.owner_name,
                                                'repositoryName': repository.name
                                            }
                                        })">{{
                                            repository.owner_name
                                            + '/' }}<span>{{ repository.name }}</span>
                                        </div>
                                        <div class="repository-access">{{ repository.access }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div v-else class="empty-message">
                            По вашему запросу, не был найден не один репозиторий
                        </div>
                    </div>
                    <div class="repository-block" v-if="accessFilter == 'teams'">

                        <div v-if="searchInfo.teams && searchInfo.teams.length" class="container-repository">
                            <div v-for="team in searchInfo.teams" :key="team.id">
                                <div class="repository">
                                    <div class="repository-info">
                                        <div class="repository-name" @click="$router.push({
                                            name: 'team',
                                            params: {
                                                'team': team.name
                                            }
                                        })">
                                            <img class="img-container" :src="team.logo">
                                            <span>{{ team.name }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div v-else class="empty-message">
                            По вашему запросу, не был найден не один пользователь
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import Search from '../components/input/Search.vue';
import router from '../router';

export default {
    name: 'SearchPage',
    data() {
        return {
            searchQuery: '',
            accessFilter: 'users',
            searchInfo: {
                users: [],
                repositories: []
            },
            isLoading: false
        }
    },
    watch: {
        "$route": {
            handler
                (to, from) {
                this.searchQuery = this.$route.query.query;
                this.fetchInfo();
            },
            immediate: true
        }
    },
    methods: {
        handleSearchInput(text) {
            this.searchQuery = text;
        },
        async fetchInfo() {
            this.$router.push({
                name: 'search',
                query: {
                    'query': this.searchQuery
                }
            })
            this.isLoading = true;
            await axios.get('/search/global', {
                params: { query: this.searchQuery }
            })
                .then(response => {
                    this.searchInfo = response.data;
                })
                .finally(promise => {
                    this.isLoading = false;
                })
        },
    },
    components: {
        Search
    }
}
</script>
<style scoped>
.container-search {
    background-color: #101112;
    display: flex;
    justify-content: center;
    width: 100vw;
    min-height: calc(100vh - 81px);
    font-family: 'Montserrat';
    font-size: 20px;
}

.search-block {
    margin-top: clamp(50px, 3vw, 100px);
    color: #FFFFFF;
    background-color: #101112;
    display: flex;
    flex-direction: column;
    width: 70vw;
    max-width: 1100px;
    min-height: 75vh;
    gap: 60px;
}

.container-chaters {
    display: flex;
    gap: 50px;
}

.infobar-chapter {
    width: 20%;
}

.container-left-block {
    width: 100%;
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.select-panel-btn {
    width: 100%;
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

.select-panel-btn.active {
    background-color: #656A6F70;
    border-left: 4px solid #EDB200;
    border-radius: 0px 10px 10px 0px;
    box-shadow: none;
}


.repository-block {
    flex: 1;
    color: #F8F9FA;
}

.container-repository {
    margin-top: 20px;
    display: flex;
    flex-direction: column;
    gap: 10px;
    height: 500px;
    overflow-y: auto;
    padding-right: 5px;
}

.container-repository::-webkit-scrollbar {
    width: 7px;
    background-color: #101112;
    border-radius: 25px;
}

.container-repository::-webkit-scrollbar-thumb {
    background-color: #343A40;
    border-radius: 25px;
}

.repository {
    border-radius: 5px;
    border: 1px solid #343A40;
    padding: 15px 20px;
    color: #F8F9FA;
    display: flex;
    gap: 5px;
    flex-direction: column;
    justify-content: center;

}

.repository.invites-block {
    display: flex;
    justify-content: space-between;
}

.repository-info {

    display: flex;
    gap: 15px;
}

.repository-name {
    letter-spacing: 1px;
    cursor: pointer;
    color: #EDB200;
    font-size: 20px;
    display: flex;
    align-items: center;
}

.repository-name span {
    font-weight: 500;
}

.repository-name:hover {
    text-decoration: underline;
}

.repository-access {
    font-family: 'Fira Code';
    padding: 4px 13px;
    font-size: 13px;
    background: none;
    border: 1px solid #343A40;
    border-radius: 15px;
}

.last-update {
    font-size: 15px;
    color: #F8F9FA60;
}

.container-repository-avatar {
    cursor: pointer;
    width: 50px;
    height: 50px;
    border-radius: 25px;
    overflow: hidden;
    border: 1px solid #343A4050;
}

.repository-avatar {
    width: 100%;
    height: 100%;
}

.search-container {
    display: flex;
    gap: 10px;
}

.search {
    width: 100%;
    display: flex;
    gap: 10px;
    align-items: center;
}

.empty-message {
    padding: 20px;
    text-align: center;
    color: #666;
}

.img-container {
    width: 50px;
    height: 50px;
    border-radius: 25px;
    margin-right: 25px;
    border: 1px solid #343A40;
}

.infout-chapter {
    flex: 1;
}
</style>