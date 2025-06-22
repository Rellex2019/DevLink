<template>
    <div class="container-profile">
        <div class="profile">
            <div v-if="!editMode" class="person-block">
                <div class="img-container">
                    <img v-if="profileInfo.avatar" :src="profileInfo.avatar" alt="avatar" class="img-avatar">
                    <img v-else src="@/svg/default-avatar.svg" alt="avatar" class="img-avatar">
                </div>
                <div class="person-name">{{ profileInfo.name }}</div>
                <div class="person-description" v-if="profileInfo.bio" v-html="profileInfo.bio"></div>
                <button class="btn-edit-profile" @click="enableEdit">Редактировать профиль</button>
                <div class="contacts">
                    <div class="location" v-if="profileInfo.location">
                        <div class="svg"><img src="@/svg/mark.svg" alt=""></div>
                        <div class="location-name">{{ profileInfo.location }}</div>
                    </div>
                    <div class="socials" v-if="profileInfo.links">
                        <div class="social" v-for="social in profileInfo.links">
                            <div class="svg"><img src="@/svg/link.svg" alt=""></div>
                            <a :href="social.link">{{ social.name }}</a>
                        </div>
                    </div>
                </div>
                <div class="teams" v-if="teams.length > 0">
                    <div class="name-team-block">Команды</div>
                    <div class="container-team-img">
                        <a @click="$router.push(`/team/${team.name}`)" v-for="team in teams"><img :src="team.logo"
                                alt=""></a>
                    </div>
                </div>
            </div>


            <div v-if="editMode" class="person-block">
                <div class="img-container" @click="triggerFileInput">
                    <img v-if="avatarPreview" :src="avatarPreview" alt="Аватар команды" class="img-avatar">
                    <img v-else-if="profileInfo.avatar" :src="profileInfo.avatar" alt="avatar"
                        class="img-avatar avatar-change">
                    <img v-else src="@/svg/default-avatar.svg" alt="avatar" class="img-avatar avatar-change">
                    <input style="display: none;" @change="handleFileUpload" ref="fileInput" type="file"
                        accept="image/jpeg, image/png">
                    <div class="plus-img" v-if="!avatarPreview">+</div>
                </div>
                <div class="person-name">{{ profileInfo.name }}</div>
                <textarea class="person-description" v-model="profileChange.bio"
                    placeholder="Расскажите о себе"></textarea>
                <div class="contacts">
                    <div class="location">
                        <div class="svg"><img src="@/svg/mark.svg" alt=""></div>
                        <div class="location-name"><input type="text" v-model="profileChange.location"
                                placeholder="Введите местоположение"></div>
                    </div>
                    <div class="socials-edit">
                        <div class="social-edit" v-for="(social, index) in defaultSocials" :key="index">
                            <div class="social-icon">
                                <img src="@/svg/link.svg" alt="">
                            </div>
                            <div class="container-social-info">
                                <input type="text" v-model="social.name" :placeholder="'Название ' + (index + 1)"
                                    class="social-input">
                                <input type="text" v-model="social.link" placeholder="Ссылка" class="social-input">
                            </div>
                        </div>
                    </div>
                    <div class="container-btns">
                        <button class="save-btn" @click="saveChanges" type="button">Сохранить</button>
                        <button class="cancel-btn" @click="cancelEdit" type="button">Отмена</button>
                    </div>
                </div>
            </div>



            <div style="flex: 1; display: flex; flex-direction: column; gap: 35px;">
                <div class="repository-block">
                    <Search :placeholderText="'Найти репозиторий'" @write-input="handleSearchInput" />
                    <div v-if="filteredRepositories && filteredRepositories.length > 0" class="container-repository">
                        <div v-for="repository in filteredRepositories" :key="repository.id">
                            <div class="repository">
                                <div class="repository-info">
                                    <div class="repository-name" @click="goToRepository(repository)">{{ repository.name
                                        }}
                                    </div>
                                    <div class="repository-access">{{ repository.access }}</div>
                                </div>
                                <div class="last-update"> Обновлено {{ formatDate(repository.updated_at) }}</div>
                            </div>
                        </div>
                    </div>
                    <div v-else class="empty-message">
                        Нет доступных репозиториев
                    </div>

                </div>
                <div class="invites-block-container" v-if="isOwner">
                    <p>Приглашения</p>
                    <div v-if="invites && invites.length" class="invites-block">
                        <div class="invite" v-for="invite in invites" :key="invite.id">
                            <div style="display: flex;align-items: center;gap: 15px;">
                                <div class="img-cont">
                                    <img :src="invite.team.logo" alt="">
                                </div>
                                <div @click="$router.push(`/team/${invite.team.name}`)" class="name-team">
                                    {{ invite.team.name }}
                                </div>
                            </div>
                            <div class="action-btns">
                                <button class="accept-btn" @click="acceptInvite(invite.id)">Принять</button>
                                <button class="reject-btn" @click="rejectInvite(invite.id)">Отклонить</button>
                            </div>

                        </div>
                    </div>
                    <div v-else style="text-align: start;" class="empty-message">
                        У вас нет ни одного приглашения
                    </div>
                </div>

            </div>
        </div>
        <footer>
        <Footer/>
    </footer>
    </div>

</template>
<script>
import axios from 'axios';
import Search from '../components/input/Search.vue';
import { mapGetters } from 'vuex/dist/vuex.cjs.js';
import store from '../store';
import echo from '../echo';
import Footer from '../components/Footer.vue';

export default {
    name: 'ProfileView',
    data() {

        const defaultSocials = [
            { name: '', link: '' },
            { name: '', link: '' },
            { name: '', link: '' },
            { name: '', link: '' }
        ];


        return {
            isOwner: false,
            profileInfo: {},
            profileChange: {},
            editMode: false,
            defaultSocials: JSON.parse(JSON.stringify(defaultSocials)),
            initialSocials: [],
            avatarFile: null,
            avatarPreview: null,
            repositories: [],
            searchQuery: '',
            teams: [],
            invites: []
        }
    },
    watch: {
        '$route': {
            handler
                (to, from) {
                this.fetchUserInfo();
            },
            immediate: true
        }

    },
    methods: {
        async fetchUserInfo() {
            await axios.get(`/user/${this.$route.params.user}`,
                {
                    headers: {
                        'X-Current-User-Id': this.user?.id || null
                    }
                }
            )
                .then(response => {
                    this.repositories = response.data.projects;
                    this.profileInfo = response.data.profile;
                    this.profileChange = response.data.profile;
                    this.teams = response.data.teams;
                    this.isOwner = response.data.isOwner;
                    if (this.isOwner) {
                        this.fetchInvites();
                    }
                })
        },
        async fetchInvites() {
            axios.get(`/user/${this.$route.params.user}/invites`)
                .then(response => {
                    this.invites = response.data;
                })
        },
        enableEdit() {
            this.defaultSocials = [
                { name: '', link: '' },
                { name: '', link: '' },
                { name: '', link: '' },
            ];

            this.profileChange.links.forEach((social, index) => {
                if (index < this.defaultSocials.length) {
                    this.defaultSocials[index] = { ...social };
                }
            });

            this.editMode = true;
        },
        async saveChanges() {
            const validLinks = this.defaultSocials.filter(
                social => social.name.trim() !== '' && social.link.trim() !== ''
            );
            this.profileInfo = JSON.parse(JSON.stringify(this.profileChange));
            this.editMode = false;
            const formData = new FormData();


            formData.append('bio', this.profileChange.bio || '');

            formData.append('location', this.profileChange.location || '');

            if (this.avatarFile) {
                formData.append('avatar', this.avatarFile);
            }

            validLinks.forEach((link, index) => {
                formData.append(`links[${index}][name]`, link.name);
                formData.append(`links[${index}][url]`, link.link);
            });

            axios.post(`/user/${this.$route.params.user}/edit`, formData,
                {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                }
            )
                .then(response => {
                    this.profileInfo = response.data.profile;
                    this.profileChange = response.data.profile;
                    this.avatarPreview = null;
                    this.avatarFile = null;
                    if (response.data.profile.links) {
                        this.defaultSocials = response.data.profile.links;
                        console.log(this.defaultSocials);
                    }
                    store.commit('authStore/updateAvatar', response.data.profile.avatar)
                })
        },

        cancelEdit() {
            this.profileChange = JSON.parse(JSON.stringify(this.profileInfo));
            this.avatarPreview = null;
            this.avatarFile = null;
            this.editMode = false;
        },
        triggerFileInput() {
            this.$refs.fileInput.click()
        },
        handleFileUpload(event) {
            const file = event.target.files[0]
            if (!file) return

            if (!file.type.match('image/png') && !file.type.match('image/jpeg')) {
                this.$showAlert('Пожалуйста, выберите изображение', 'error')
                return
            }


            if (file.size > 2 * 1024 * 1024) {
                this.$showAlert('Файл слишком большой. Максимальный размер - 2MB', 'error')
                return
            }

            this.avatarFile = file

            const reader = new FileReader()
            reader.onload = (e) => {
                this.avatarPreview = e.target.result
            }
            reader.readAsDataURL(file)
        },
        goToRepository(repo) {
            if (!repo.owner_name || !repo.name) {
                console.error('Ника пользователя или названия репозитория не существует');
                return;
            }

            this.$router.push(`/${repo.owner_name}/${repo.name}`);
        },
        formatDate(dateString) {
            const options = { year: 'numeric', month: 'long', day: 'numeric' };
            return new Date(dateString).toLocaleDateString('ru-RU', options);
        },
        handleSearchInput(text) {
            this.searchQuery = text;
        },
        acceptInvite(inviteId) {
            axios.post(`user/invite/${inviteId}/accept`)
                .then(response => {
                    this.deleteFromInvites(inviteId);
                })

        },
        rejectInvite(inviteId) {
            axios.post(`user/invite/${inviteId}/reject`)
                .then(response => {
                    this.deleteFromInvites(inviteId);
                })
        },
        deleteFromInvites(id) {
            this.invites = this.invites.filter(i => {
                i.id != id
            })
        }
    },
    mounted() {

        echo.private(`user.${this.user.id}`)
            .listen('UserInvited', (data) => {
                console.log('New invitation in team:', data);
                this.invites.push(data.invite);
                this.newNotification(data.invite, 'inviteTeam');
            })
    },
    computed: {
        ...mapGetters('authStore', ['user']),
        filteredRepositories() {
            if (!this.repositories || this.repositories.length === 0) return [];

            if (this.searchQuery.trim() === '') {
                return this.repositories;
            }

            return this.repositories.filter(repo =>
                repo.name.toLowerCase().includes(this.searchQuery.toLowerCase())
            );
        }
    },
    components: {
        Search,
        Footer
    }

}
</script>
<style scoped>
.empty-message {
    padding: 20px;
    text-align: center;
    color: #666;
}

.container-profile {
    flex: 1;
    background-color: #101112;
    display: flex;
    align-items: center;
    width: 100vw;
    flex-direction: column;
    /* min-height: 100%; */
    font-family: 'Montserrat';
    font-size: 20px;
}

.profile {
    display: flex;
    justify-content: space-between;
    gap: clamp(5px, 2.5vw, 40px);
    margin-top: 80px;
    width: 84vw;
    height: fit-content;
    max-width: 1600px;
}

.person-block {
    display: flex;
    flex-direction: column;
    gap: 25px;
    width: 22.5%;
    max-width: 270px;
    color: #FFFFFF;
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
    width: 100%;
    padding-top: 100%;
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

.person-description {
    word-break: break-all;
    font-size: 20px;
}

.btn-edit-profile {
    cursor: pointer;
    padding: 15px 0px;
    font-size: 16px;
    border-radius: 5px;
    border: 1px solid #EDB200;
    background: none;
    color: white;
}

.btn-edit-profile:hover {
    background-color: #edb20033;
}

.btn-edit-profile:active {
    background-color: #edb20092;
}

.contacts {
    margin-top: 10px;
    display: flex;
    flex-direction: column;
    gap: 20px;
    font-size: 15px;
}

.contacts::after {
    content: '';
    display: block;
    width: 100%;
    height: 1px;
    background-color: #343A40;
}

.location {
    display: flex;
    gap: 10px;
}

.svg,
.social-icon {
    margin-top: 2px;
}

.svg img {
    width: 25px;
    height: 25px;
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










/* РЕПОЗИТОРИИ */
.repository-block {
    padding-right: 5px;
    height: 50%;
}





.container-repository {
    margin-top: 20px;
    display: flex;
    height: 80%;
    flex-direction: column;
    overflow-y: auto;
    gap: 15px;
}
.container-repository::-webkit-scrollbar {
    width: 7px;
}

.container-repository::-webkit-scrollbar-track {
    background-color: #343A40;
    border-radius: 10px;
}

.container-repository::-webkit-scrollbar-thumb {
    background-color: #161718;
    border-radius: 10px;
}

.container-repository::-webkit-scrollbar-thumb:hover {
    background-color: #222325;
}
.repository {
    border-radius: 5px;
    border: 1px solid #343A40;
    padding: 15px 20px;
    color: #F8F9FA;
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.repository-info {

    display: flex;
    gap: 15px;
}

.repository-name {
    cursor: pointer;
    color: #EDB200;
    font-size: 20px;
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




/* Приглашения */

.invites-block-container {
    color: #ddd;
    width: 50%;
    height: fit-content;
    min-height: 200px;
    border-radius: 8px;
    display: flex;
    flex-direction: column;
    gap: 25px;
}

.invites-block-container p {
    font-weight: 500;
}

.invites-block {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.invite {
    padding: 10px 10px;
    border: 1px solid #4B4F53;
    border-radius: 7px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 15px;
}

.img-cont {
    width: clamp(20px, 3vw, 50px);
    height: clamp(20px, 3vw, 50px);
    overflow: hidden;
    border-radius: 5vw;
}

.img-cont img {
    width: 100%;
    height: 100%;
}

.name-team:hover {
    cursor: pointer;
    text-decoration: underline;
}

.action-btns {
    display: flex;
    gap: 15px;
}

.action-btns button {
    font-size: 16px;
    padding: 9px 30px;
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
    border: 1px solid #4B4F53;
    border-radius: 5px;
    background: none;
    color: #6e767d;
}

.reject-btn:hover {
    color: #FF0E0E;
    border-color: #FF0E0E;
}

footer{
    width: 100%;
    padding: 30px 0px;
}
</style>