<template>
    <div class="container-profile">
        <component :is="currentComponent" :teamInfo="teamInfo" @refresh="fetchUserInfo" @team-update="handleTeamInfoUpdate"/>
    </div>
</template>
<script>
import axios from 'axios';
import Search from '../components/input/Search.vue';
import { mapGetters } from 'vuex/dist/vuex.cjs.js';
import TeamMembers from '../components/TeamView/TeamMembers.vue';
import TeamRepositories from '../components/TeamView/TeamRepositories.vue';
import TeamSettings from '../components/TeamView/TeamSettings.vue';
import { markRaw } from 'vue';

export default {
    name: 'TeamView',
    data() {
        return {
            teamInfo: {},
            searchQuery: '',
            chapters: {
                'peoples': markRaw(TeamMembers),
                'repository': markRaw(TeamRepositories),
                'settings': markRaw(TeamSettings),
            }
        }
    },
    methods: {
        async fetchUserInfo(name) {
            let team = name? name: this.$route.params.team
            await axios.get(`/team/${team}/get`)
                .then(response => {
                    this.teamInfo = response.data;
                    if (!this.$route.query.chapter) {
                        this.$router.push(`/team/${team}?chapter=peoples`);
                    }

                })
        },
        handleTeamInfoUpdate(newInfo)
        {
            this.fetchUserInfo(newInfo.name);
        }

        // formatDate(dateString) {
        //     const options = { year: 'numeric', month: 'long', day: 'numeric' };
        //     return new Date(dateString).toLocaleDateString('ru-RU', options);
        // },

    },
    mounted() {
        this.fetchUserInfo();
    },
    computed: {
        ...mapGetters('authStore', ['user']),
        currentComponent() {
            const chapter = this.$route.query.chapter || 'peoples';
            return this.chapters[chapter] || TeamMembers;
        }
    },
    components: {
        Search,
        TeamMembers,
        TeamRepositories,
        TeamSettings
    }

}
</script>
<style scoped>
.container-profile {
    background-color: #101112;
    display: flex;
    justify-content: center;
    width: 100vw;
    min-height: calc(100vh - 80px - 37px);
    font-family: 'Montserrat';
    font-size: 20px;

}
</style>