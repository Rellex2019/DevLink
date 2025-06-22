<template>
    <div class="profile">
        <div class="person-block">
            <div class="container-left-block">
                <button class="select-panel-btn" @click="accessFilter = null"
                    :class="{ 'active': accessFilter === null }">Все</button>
                <button class="select-panel-btn" @click="accessFilter = 'public'"
                    :class="{ 'active': accessFilter === 'public' }">Публичные</button>
                <button class="select-panel-btn" @click="accessFilter = 'private'"
                    :class="{ 'active': accessFilter === 'private' }">Приватные</button>
            </div>
            <button v-if="teamInfo.isOwner" class="select-panel-btn invites" @click="accessFilter = 'invites'"
                :class="{ 'active': accessFilter === 'invites' }">Приглашения</button>
        </div>







        <div class="repository-block"
            v-if="accessFilter == null || accessFilter == 'public' || accessFilter == 'private'">
            <div class="search-container">
                <div class="search">
                    <Search :placeholderText="'Найти репозиторий'" style="flex: 1;" @write-input="handleSearchInput" />
                </div>
                <div class="container-count-repository" v-if="teamInfo.members">
                    {{ teamInfo.projects.length }} {{ pluralize(teamInfo.projects.length, ['репозиторий', 'репозитория',
                        'репозиториев']) }} в команде {{ teamInfo.team.name }}
                </div>
            </div>

            <div v-if="teamInfo.projects && teamInfo.projects.length" class="container-repository">
                <div v-for="repository in filteredRepositories" :key="repository.id">
                    <div class="repository">
                        <div class="repository-info">
                            <div class="repository-name-container">
                                <div class="repository-name" @click="goToRepository(repository)">
                                    {{ repository.owner_name + '/' }}<span>{{ repository.name }}</span>
                                </div>
                                <div class="repository-access">{{ repository.access }}</div>
                            </div>
                            <button class="reject-btn" style="padding: 10px 15px;" @click="deleteRepository(repository)">Удалить</button>
                        </div>
                    </div>
                </div>
            </div>
            <div v-else class="empty-message">
                Нет доступных репозиториев
            </div>
        </div>
        <div class="repository-block" v-if="accessFilter == 'invites'">
            <div class="btn-chapters-container">
                <button @click="inviteChapter = 'received'"
                    :class="{ 'active': inviteChapter === 'received' }">Вам</button>
                <button @click="inviteChapter = 'sent'" :class="{ 'active': inviteChapter === 'sent' }">От вас</button>
            </div>
            <div v-if="inviteChapter == 'received'">
                <div v-if="invites.length > 0" class="invites-block-container">
                    <div v-for="invite in invites" :key="invite.id" class="container-invites">
                        <div class="repository invites-block">
                            <div style="display: flex; justify-content: space-between;">
                                <div class="repository-info">
                                    <div class="repository-name" @click="goToRepository(invite.project)">{{
                                        invite.project.owner_name
                                        + '/' }}<span>{{ invite.project.name }}</span>
                                    </div>
                                    <div class="repository-access">{{ invite.project.access }}</div>
                                </div>
                                <div class="btn-container">
                                    <button class="accept-btn" @click="acceptInvite(invite.id)">Принять</button>
                                    <button class="reject-btn" @click="rejectInvite(invite.id)">Отклонить</button>
                                </div>
                            </div>

                            <div class="extra-info">
                                <div class="sender">
                                    <div>
                                        Отправитель: <span class="sender-only-info"
                                            @click="$router.push(`/${invite.sender.name}`)">{{ invite.sender.name
                                            }}</span>
                                    </div>
                                    <div>
                                        Email: <span class="sender-only-info">{{ invite.sender.email }}</span>
                                    </div>
                                </div>
                                <div class="date">
                                    {{ formatDateTime(invite.updated_at) }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div v-else class="empty-message">
                    У вашей команды нет приглашений
                </div>

            </div>
            <div v-if="inviteChapter == 'sent'">
                <div v-if="sentInvites.length > 0" class="invites-block-container" id="invites-block-container">
                    <div v-for="invite in sentInvites" :key="invite.id" class="container-invites">
                        <div class="repository invites-block">
                            <div style="display: flex; justify-content: space-between;">
                                <div class="repository-info">
                                    <div class="container-img">
                                        <img :src="invite.user.profile.avatar" alt="">
                                    </div>
                                    <div class="user-name" @click="$router.push(`/${invite.user.name}`)"><span>{{
                                        invite.user.name }}</span>
                                    </div>
                                </div>
                                <div class="btn-container">
                                    <button class="reject-btn" @click="cancelInvite(invite.id)">Отменить
                                        приглашение</button>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <div v-else class="empty-message">
                    Вы ещё никого не пригласили в команду
                </div>

            </div>
        </div>
    </div>
</template>
<script>

import axios from 'axios';
import Search from '../input/Search.vue';

export default {
    name: 'TeamRepositories',
    data() {
        return {
            searchQuery: '',
            accessFilter: null,
            invites: [],
            sentInvites: [],
            inviteChapter: 'received'
        }
    },
    props: {
        teamInfo: {
            required: true,
        }
    },
    watch: {
        teamInfo: {
            handler(newVal) {
                if (newVal && newVal.team && newVal.team.id) {
                    this.fetchInvites(newVal.team.id);
                    this.fetchSentInvites(newVal.team.id);

                }
            },
            immediate: true
        }
    },
    methods: {
        deleteRepository(repository)
        {
            const confirm = window.confirm(`Вы уверены что хотите отказаться от прав на редактирование репозитория ${repository.name}? `);
            if(confirm)
        {
            axios.delete(`/project/${repository.id}/team/delete/${this.teamInfo.team.id}`)
            .then(response=>{
                this.teamInfo.projects = this.teamInfo.projects.filter(rep=>{
                    return rep.id != repository.id
                })
            })
        }
        },
        fetchInvites() {
            axios.get(`/team/${this.teamInfo.team.id}/invite`)
                .then(response => {
                    this.invites = response.data;
                })
        },
        fetchSentInvites() {
            axios.get(`/team/${this.teamInfo.team.id}/invited`)
                .then(response => {
                    this.sentInvites = response.data;
                })
        },
        handleSearchInput(text) {
            this.searchQuery = text;
        },
        goToRepository(repo) {
            if (!repo.owner_name || !repo.name) {
                console.error('Ника пользователя или названия репозитория не существует');
                return;
            }

            this.$router.push(`/${repo.owner_name}/${repo.name}`);
        },
        pluralize(count, words) {
            const cases = [2, 0, 1, 1, 1, 2];
            return words[
                count % 100 > 4 && count % 100 < 20 ? 2 : cases[Math.min(count % 10, 5)]
            ];
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
        acceptInvite(inviteId) {
            axios.post(`/invitations/${inviteId}/accept`)
                .then(response => {
                    this.$emit('refresh');
                    this.deleteFromInvites(inviteId);
                })

        },
        rejectInvite(inviteId) {
            axios.post(`/invitations/${inviteId}/reject`)
                .then(response => {
                    this.deleteFromInvites(inviteId);
                })
        },
        cancelInvite(inviteId) {
            axios.delete(`/team/${inviteId}/invite`)
                .then(response => {
                    this.sentInvites = this.sentInvites.filter(i => {
                        return i.id != inviteId
                    })
                })
        },
        deleteFromInvites(id) {
            this.invites = this.invites.filter(i => {
                return i.id != id
            })
        }
    },
    computed: {
        filteredRepositories() {
            if (!this.teamInfo.projects || this.teamInfo.projects.length === 0) return [];

            let filtered = this.teamInfo.projects;

            if (this.accessFilter) {
                filtered = filtered.filter(rep => rep.access === this.accessFilter);
            }
            if (this.searchQuery.trim() !== '') {
                const query = this.searchQuery.toLowerCase();
                filtered = filtered.filter(rep =>
                    rep.name.toLowerCase().includes(query) ||
                    rep.owner_name.toLowerCase().includes(query)
                );
            }

            return filtered;

        }
    },
    components: {
        Search
    }
}
</script>
<style scoped>
.profile {
    display: flex;
    justify-content: space-between;
    gap: clamp(5px, 2.5vw, 40px);
    margin-top: 80px;
    width: 84vw;
    max-width: 1600px;
}

.person-block {
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    height: 80%;
    gap: 10px;
    width: 25%;
    max-width: 500px;
    color: #FFFFFF;
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

.invites {
    box-shadow: 0px 0px 3px #EDB200;
}

.select-panel-btn:hover {
    background-color: #656A6F50;

}

.select-panel-btn.active {
    background-color: #656A6F70;
    border-left: 4px solid #EDB200;
    border-radius: 0px 10px 10px 0px;
    box-shadow: none;
}


.img-avatar {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.img-container {
    position: relative;
    width: 50%;
    padding-top: 50%;
    border-radius: 50%;
    height: 0;
    position: relative;
    overflow: hidden;
    box-shadow: 0px 0px 6px #4B4F53;
    background-color: #101112;
}

.person-name {
    font-size: 24px;
    font-weight: bold;
}

.contacts {
    margin-top: 10px;
    display: flex;
    flex-direction: column;
    gap: 20px;
    font-size: 15px;
}





.person-name::after {
    margin-top: 10px;
    content: '';
    display: block;
    width: 100%;
    height: 1px;
    background-color: #343A40;
}

.location {
    display: flex;
    align-items: center;
    gap: 10px;
}

.svg,
.social-icon {
    margin-top: 2px;
}

.svg img {
    width: 20px;
    height: 20px;
}

.socials {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.social {
    display: flex;
    gap: 10px;
}

.social a {
    text-decoration: none;
    color: inherit;
}

.social a:hover {
    text-decoration: underline;
}

.name-team-block {
    font-size: 20px;
    font-weight: bold;
}

.container-team-img {

    margin-top: 20px;
    display: flex;
    gap: 12px;
    flex-wrap: wrap;
}

.container-team-img a {
    overflow: hidden;
    border-radius: 5vw;
    width: clamp(20px, 3vw, 50px);
    height: clamp(20px, 3vw, 50px);

}

.container-team-img a img {
    width: 100%;
    height: 100%;
}

.person-block textarea {
    background-color: #161718;
    border: 1px solid #343A40;
    color: #ddd;
    padding: 8px 10px;
    box-sizing: border-box;
    width: 100%;
    min-height: 100px;
    font-size: 16px;
    resize: none;
    border-radius: 9px;
}

.location-name input,
.container-social-info input {
    width: 100%;
    background-color: #161718;
    border: 1px solid #343A40;
    color: #ddd;
    padding: 8px 10px;
    border-radius: 9px;
}

.container-btns {
    gap: 10px;
    display: flex;
}

.container-btns button {
    cursor: pointer;
    padding: 6px 10px;
    color: #FFF;
    border-radius: 7px;
}

.save-btn {
    border: 1px solid #EDB200;
    background-color: #edb20033;
}

.save-btn:hover {
    background-color: #edb2004b;
}

.cancel-btn {
    border: 1px solid #4B4F53;
    background: none;
}

.cancel-btn:hover {
    background-color: #4b4f5327;
}

.plus-img {
    top: calc(50% - 35px);
    left: calc(50% - 17px);
    font-size: 60px;
    color: #bebebe;
    position: absolute;
}

.avatar-change {
    cursor: pointer;
    filter: brightness(0.4);
}

.socials-edit {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.social-edit {
    display: flex;
    align-items: flex-start;
    gap: 10px;
}

.container-social-info {
    display: flex;
    flex-direction: column;
    gap: 3px;
}



.empty-message {
    padding: 20px;
    text-align: center;
    color: #666;
}






/* РЕПОЗИТОРИИ */
.repository-block {
    flex: 1;
    color: #F8F9FA;
}

.container-repository {
    margin-top: 20px;
    display: flex;
    flex-direction: column;
    gap: 10px;
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
    justify-content: space-between;
    align-items: center;
    gap: 15px;
    width: 100%;
}
.repository-name-container {
    display: flex;
    align-items: center;
    gap: 15px;
    flex-grow: 1;
}
.repository-name {
    letter-spacing: 1px;
    cursor: pointer;
    color: #EDB200;
    font-size: 20px;
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
    width: 50%;
    max-width: 500px;
    display: flex;
    gap: 10px;
    align-items: center;
}

.leave-team-btn {
    cursor: pointer;
    background: none;
    border: 1px solid #FF0E0E;
    padding: 5px 10px;
    border-radius: 5px;
    color: #FF0E0E;
}

.leave-team-btn:hover {
    color: #F8F9FA;
    background-color: #FF0E0E;
}

.container-count-repository {
    color: #F8F9FA;
    font-size: 16px;
    border: 1px solid #343A40;
    border-radius: 15px;
    padding: 5px 10px;
}

.invites-block-container {
    display: flex;
    flex-direction: column;
    gap: 29px;
}

.invites-block-name {
    font-size: 18px;
    font-weight: 500;
}

.btn-container {
    display: flex;
    align-items: center;
    gap: 25px;
}

.btn-container button {
    cursor: pointer;
    padding: 5px 15px;
    font-size: 14px;
    font-weight: 500;
}

.accept-btn {
    border: 1px solid #EDB200;
    border-radius: 5px;
    background: none;
    color: #EDB200;
}

.accept-btn:hover {
    background-color: #EDB20030;
}

.reject-btn {
    cursor: pointer;
    border: 1px solid #4B4F53;
    border-radius: 5px;
    background: none;
    color: #6e767d;
}

.reject-btn:hover {
    color: #FF0E0E;
    border-color: #FF0E0E;
}

.extra-info {
    display: flex;
    justify-content: space-between;
}

.sender {
    display: flex;
    flex-direction: column;
    font-size: 16px;
    gap: 3px;
}

.sender-only-info {
    cursor: pointer;
    color: #EDB200;
}

.sender-only-info:hover {
    text-decoration: underline;
}

.date {
    font-size: 16px;
    color: #656c72;
    align-self: flex-end;
}

.btn-chapters-container {
    display: flex;
    gap: 15px;
}

.btn-chapters-container button {
    font-family: 'Montserrat';
    box-sizing: border-box;
    display: flex;
    gap: 15px;
    align-items: center;
    font-weight: 500;
    font-size: 16px;
    background: none;
    border-radius: 10px;
    padding: 10px 15px;
    border: none;
    border: 1px solid #EDB20000;
    color: #F8F9FA;
    text-align: start;
    cursor: pointer;
    transition: 0.3s;
}

.btn-chapters-container button:hover {
    background-color: #656A6F50;
}

.btn-chapters-container button.active {
    background-color: #656A6F70;
    border: 1px solid #EDB200;
    box-shadow: none;
}

.container-invites {
    margin-top: 25px;
}

.container-img {
    width: 40px;
    height: 40px;
}

.container-img img {
    width: 100%;
    height: 100%;
}

.user-name {
    letter-spacing: 1px;
    cursor: pointer;
    color: #EDB200;
    font-size: 20px;
    display: flex;
    align-items: center;
}





#invites-block-container {
    gap: 0;
}
</style>