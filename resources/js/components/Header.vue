<template>
    <header :style="subtitleItems && subtitleItems.length ? { border: 'none' } : null">
        <div class="outer-padding">
            <div class="container-logo">
                <img class="logo-svg" src="@/svg/logo.svg" alt="Логотип">
                <p class="logo-name">Dev<span>Link</span></p>
            </div>
            <div class="container-tools">
                <Search :animated='true' w=260 placeholderText="Найти друзей, репозитории" />
                <div class="container-btns" v-if="!isAuthenticated">
                    <button @click="$router.push({ name: 'login' })" class="login">Войти</button>
                    <button @click="$router.push({ name: 'registration' })" class="singup">Зарегистрироваться</button>
                </div>
                <div v-if="isAuthenticated" tabindex="0"
                    @keydown="(e) => actionModal(e, 'isPlusVisible', '.plus-block')"
                    @click="(e) => actionModal(e, 'isPlusVisible', '.plus-block')" class="plus-block style-block">
                    <svg width="16" class="plus-svg" height="16" viewBox="0 0 16 16" fill="currentColor"
                        xmlns="http://www.w3.org/2000/svg">
                        <path d="M8 1V8M8 15V8M8 8H1M8 8H15" class="plus-svg" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" />
                    </svg>

                    <Modal :isVisible="isPlusVisible" class="modal-plus">
                        <router-link :to="{ name: 'createRepository' }" class="option-modal" ref="optionModal">
                            <div class="option-svg-container">
                                <svg width="21" height="21" class="option-svg" viewBox="0 0 21 21" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path stroke="currentColor" class="option-svg"
                                        d="M8.71429 16.5556H2C1.44772 16.5556 1 16.1078 1 15.5556V7.11111M19.2857 8.77778V5.86111M19.2857 5.86111V3.94444C19.2857 3.39216 18.838 2.94444 18.2857 2.94444H9.68651C9.14956 2.94444 8.71429 2.50917 8.71429 1.97222V1.97222C8.71429 1.43528 8.27901 1 7.74206 1H2C1.44772 1 1 1.44771 1 2V7.11111M19.2857 5.86111L9.69907 5.73166C9.31452 5.72647 9 6.03677 9 6.42136V6.42136C9 6.8023 8.69119 7.11111 8.31024 7.11111H1M16.7143 11.8333V16.4167M16.7143 21V16.4167M16.7143 16.4167H11.8571M16.7143 16.4167H21" />
                                </svg>
                            </div>
                            <div class="option-name">Новый репозиторий</div>
                        </router-link>
                        <!-- <router-link :to="'/task/create'" class="option-modal">
                            <div class="option-svg-container">
                                <svg width="20" height="20" class="option-svg" viewBox="0 0 20 20" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path stroke="currentColor" class="option-svg"
                                        d="M1 1V18.3925C1 18.6687 1.22386 18.8925 1.5 18.8925H15.3635M1 1V18.4962C1 18.7738 1.22614 18.9983 1.50374 18.9962L15.3635 18.8925M1 1H10.2377M15.3635 18.8925V11.8M10.2377 1V5.22836C10.2377 5.5045 10.4615 5.72836 10.7377 5.72836H12.0402M10.2377 1L13.899 3.63284M12.239 12.3333L18.8158 5.98254C18.8789 5.92162 18.926 5.84527 18.9456 5.75978C19.2036 4.63155 18.5433 3.70279 16.9835 3.88053C16.8821 3.89209 16.7884 3.93928 16.7145 4.00977L10.2377 10.1881M12.239 12.3333L9.59868 13.3584C9.17044 13.5247 8.76346 13.074 8.97244 12.6649L10.2377 10.1881M12.239 12.3333L10.2377 10.1881M3.53473 8.46866H7.98459M3.53473 11.5313H7.75928M6.85804 14.7015H3.53473"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                </svg>

                            </div>
                            <div class="option-name">Создать задачу</div>
                        </router-link> -->
                        <router-link :to="{ name: 'createTeam' }" class="option-modal" id="border-none">
                            <svg width="32" height="16" class="option-svg" viewBox="0 0 32 16" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path class="option-svg"
                                    d="M1.3095 14.8538C0.401164 14.7648 1.59342 10.7084 3.56693 9.57796C3.79543 9.44708 4.0721 9.46714 4.30915 9.58179C5.44835 10.1327 7.10783 10.8889 9.46045 9.49239M1.3095 14.8538C2.75014 14.995 5.60482 14.9578 7.06958 14.9056C7.58229 14.8874 7.93676 14.4645 7.9663 13.9523C8.03812 12.7071 8.39534 10.6941 9.46045 9.49239M1.3095 14.8538C0.355257 14.7603 1.7193 10.2882 3.87197 9.42988C3.91475 9.41282 3.96175 9.41505 4.00341 9.4347C5.14439 9.9727 6.88809 11.0194 9.46045 9.49239M1.3095 14.8538C3.07416 15.0268 6.96044 14.932 7.81508 14.87C7.90483 14.8635 7.96289 14.7863 7.95816 14.6962C7.90712 13.7236 8.13885 10.9834 9.46045 9.49239M30.6905 14.9015C31.6447 14.808 30.2807 10.3359 28.128 9.47754C28.0852 9.46048 28.0383 9.46271 27.9966 9.48235C26.8556 10.0204 25.1119 11.067 22.5395 9.54004C23.8611 11.0311 24.0929 13.7713 24.0418 14.7438C24.0371 14.834 24.0952 14.9111 24.1849 14.9177C25.0396 14.9797 28.9258 15.0744 30.6905 14.9015ZM19.9124 8.82745C21.9012 10.2443 22.9588 12.5241 22.8997 14.1323C22.8837 14.5686 22.4856 14.8538 22.0491 14.8538H9.7618C9.48804 14.8538 9.20945 14.7421 9.13933 14.4774C8.81104 13.2385 9.92451 10.209 11.9909 8.72618C12.2622 8.53151 12.6191 8.52984 12.9219 8.67069C15.2617 9.75933 16.7609 9.77076 18.9558 8.76422C19.2673 8.62135 19.6332 8.62859 19.9124 8.82745Z"
                                    stroke="currentColor" stroke-linejoin="round" />
                                <path class="option-svg"
                                    d="M9.39884 6.06458C9.39884 7.48873 8.24797 8.64082 6.83126 8.64082C5.41455 8.64082 4.26367 7.48873 4.26367 6.06458C4.26367 4.64044 5.41455 3.48835 6.83126 3.48835C8.24797 3.48835 9.39884 4.64044 9.39884 6.06458Z"
                                    stroke="currentColor" />
                                <path class="option-svg"
                                    d="M27.6293 5.8888C27.6293 7.31294 26.4784 8.46503 25.0617 8.46503C23.645 8.46503 22.4941 7.31294 22.4941 5.8888C22.4941 4.46465 23.645 3.31256 25.0617 3.31256C26.4784 3.31256 27.6293 4.46465 27.6293 5.8888Z"
                                    stroke="currentColor" />
                                <path class="option-svg"
                                    d="M19.7406 4.30673C19.7406 6.41046 18.0404 8.11346 15.946 8.11346C13.8516 8.11346 12.1514 6.41046 12.1514 4.30673C12.1514 2.203 13.8516 0.5 15.946 0.5C18.0404 0.5 19.7406 2.203 19.7406 4.30673Z"
                                    stroke="currentColor" />
                            </svg>

                            <div class="option-name">Создать команду</div>
                        </router-link>
                    </Modal>
                </div>

                <button v-if="isAuthenticated" class="notification-block style-block"
                    @keydown="(e) => actionModal(e, 'isNotificationVisible', '.notification-block')"
                    @click="(e) => actionModal(e, 'isNotificationVisible', '.notification-block')"
                    ref="notificationBlock">
                    <svg width="18" height="22" class="bell-svg" viewBox="0 0 18 22" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path class="bell-svg" stroke="currentColor"
                            d="M7.16234 19.9643C8.19899 21.3452 9.70685 21.3452 10.7435 19.9643M15.7932 17.1105C8.95499 17.1105 6.34703 17.1105 2.18847 17.1105C1.33193 17.1105 0.853811 16.0774 1.30531 15.3495C4.03101 10.9553 2.36006 7.4114 3.52923 4.6983C5.32747 0.525467 13.0709 0.532413 14.633 4.79925C15.6294 7.52072 14.0858 11.1014 16.6863 15.3389C17.1355 16.0709 16.652 17.1105 15.7932 17.1105Z"
                            stroke-width="2" stroke-linecap="round" />
                    </svg>
                    <div v-if="notifyCount > 0" class="notify-count">

                    </div>
                    <Modal :isVisible="isNotificationVisible" class="modal-notify">
                        <div class="option-notify" v-for="notify in notifications" :key="notify.id">
                            <p v-if="notify.type === 'inviteTeam'"
                                @click.self="$router.push(`/${notify.data.user.name}`)">
                                Пользователь <span class="selection"
                                    @click="$router.push(`/${notify.data.sender.name}`)">{{ notify.data.sender.name
                                    }}</span>
                                предлагает вам вступить в команду <span class="selection"
                                    @click="$router.push(`/team/${notify.data.team.name}`)">{{ notify.data.team.name
                                    }}</span>
                            </p>

                            <p v-if="notify.type === 'inviteProject'"
                                @click.self="$router.push(`/team/${notify.data.team.name}?chapter=repository`)">
                                Вашей команде <span class="selection"
                                    @click="$router.push(`/team/${notify.data.team.name}`)">{{ notify.data.team.name
                                    }}</span>
                                пользователь <span class="selection"
                                    @click="$router.push(`/${notify.data.sender.name}`)">{{ notify.data.sender.name
                                    }}</span> предлагает присоедениться к разработке репозитория <span class="selection"
                                    @click="$router.push(`/${notify.data.project.owner_name}/${notify.data.project.name}`)">{{
                                    notify.data.project.name }}</span>
                            </p>
                        </div>
                    </Modal>
                </button>


                <button v-if="isAuthenticated" @keydown="(e) => actionModal(e, 'isPersonVisible', '.person-block')"
                    @click="(e) => actionModal(e, 'isPersonVisible', '.person-block')" class="person-block"
                    ref="personBlock">
                    <div class="person-name">{{ user.name }}</div>
                    <!-- Сделать фото из БД -->
                    <div v-if="user.avatar" class="container-person-photo"><img class="person-photo"
                            :src="`${user.avatar}`" alt="Фото пользователя"></div>
                    <Modal :isVisible="isPersonVisible" class="modal-person">
                        <div class="option" @click="$router.push(`/${user.name}`)">
                            <p>Профиль</p>
                        </div>
                        <div class="option" @click="exitFromAccount">
                            <p>Выход</p>
                        </div>
                    </Modal>
                </button>
            </div>
        </div>

    </header>
    <div class="subtitle" v-if="isAuthenticated">
        <div class="subtitle-outer-padding">
            <Subtitle :pages="subtitleItems" />
        </div>
    </div>
</template>

<script>
import Modal from '@/js/components/modal/Modal.vue';
import Search from '@/js/components/input/Search.vue';
import { mapGetters } from 'vuex/dist/vuex.cjs.js';
import Subtitle from './Header/Subtitle.vue';
import axios from 'axios';
import echo from '../echo';





export default {
    name: 'Header',
    data() {
        return {
            isPlusVisible: false,
            isPersonVisible: false,
            isNotificationVisible: false,


            subtitleItems: null,
            notifyCount: 0,
            notifications: []

        };
    },
    methods: {
        actionModal(e, varible, mainBlock) {
            if (e.type === 'keydown' && e.key == 'Enter' || e.type === 'click') {
                this[varible] = !this[varible];
                window.addEventListener('click', (e) => this.handleClick(e, varible, mainBlock));
                window.addEventListener('focusin', (e) => this.handleClick(e, varible, mainBlock));

            }
            else if (e.type === 'keydown' && e.key !== 'Enter' && e.key !== 'Tab') {
                this[varible] = false;
            }
            if (mainBlock === '.notification-block') {
                this.notifyCount = 0;
            }

        },
        handleClick(e, varible, mainBlock) {
            const block = document.querySelector(mainBlock);
            if (block && !block.contains(e.target)) {
                this[varible] = false;
                window.removeEventListener('click', this.handleClick);
                window.removeEventListener('focusin', this.handleClick);
            }
        },
        async exitFromAccount() {
            await axios.get("/logout").then((response) => {
                this.$router.push("/login");
                this.$store.commit('authStore/logout');
            });
        },
        async loadSubtitle() {
            if (this.$route.params.user && this.$route.params.repositoryName &&
                (this.$route.name == 'repository' || this.$route.name == 'repositoryTeams' || this.$route.name == 'repositorySettings')) {
                try {
                    const response = await axios.get(`/files/${this.$route.params.user}/${this.$route.params.repositoryName}`);
                    const repositoryInfo = response.data;

                    this.subtitleItems = repositoryInfo.isOwner ? [
                        { name: 'Код', url: `/${this.$route.params.user}/${this.$route.params.repositoryName}` },
                        { name: 'Задачи', url: `/${this.$route.params.user}/${this.$route.params.repositoryName}/tasks` },
                        { name: 'Настройки', url: `/${this.$route.params.user}/${this.$route.params.repositoryName}/settings` }
                    ] : [
                        { name: 'Код', url: `/${this.$route.params.user}/${this.$route.params.repositoryName}` },
                        { name: 'Задачи', url: `/${this.$route.params.user}/${this.$route.params.repositoryName}/tasks` }
                    ];
                } catch (error) {
                    console.error(error);
                }
            } else if (this.$route.params.team && this.$route.name == 'team') {
                const response = await axios.get(`/team/${this.$route.params.team}/get`);
                const teamInfo = response.data;
                this.subtitleItems = teamInfo.isMember ? [
                    { name: 'Участники', query: 'peoples', url: `${this.$route.params.team}?chapter=peoples` },
                    { name: 'Репозитории', query: 'repository', url: `${this.$route.params.team}?chapter=repository` },
                    { name: 'Настройки', query: 'settings', url: `${this.$route.params.team}?chapter=settings` }
                ] : [
                    { name: 'Обзор команды', query: 'peoples', url: `${this.$route.params.team}?chapter=peoples` },
                ];
            }
            else {
                this.subtitleItems = [];
            }
        },
        newNotification(data, type) {
            this.notifications.push({
                'type': type,
                'data': data
            });
            this.notifyCount++;
            console.log(this.notifications);
        }
    },
    computed: {
        ...mapGetters('authStore', ['isAuthenticated', 'user']),
    },
    watch: {
        '$route': 'loadSubtitle'
    },
    mounted() {
        this.loadSubtitle();
        // Для владельца команды - уведомления о новых приглашениях
        if (this.user) {
            echo.private(`team.${this.user.id}`)
                .listen('TeamInvited', (data) => {
                    console.log('New invitation in project:', data);
                    this.newNotification(data.invitation, 'inviteProject');
                });

            // Для отправителя - уведомления о принятии/отклонении
            echo.private(`user.${this.user.id}`)
                .listen('UserInvited', (data) => {
                    console.log('New invitation in team:', data);
                    this.newNotification(data.invite, 'inviteTeam');
                })
                .listen('.invitation.rejected', (data) => {
                    console.log('Invitation rejected:', data);
                    // this.showRejectNotification(data.invitation);
                });
        }

    },
    components: {
        Modal,
        Search,
        Subtitle
    },
};

</script>

<style scoped>
* {
    color: #F8F9FA;
}

#border-none {
    border: none;
}

header {
    flex: none;
    box-sizing: border-box;
    width: 100vw;
    height: 80px;
    background-color: #101112;
    border-bottom: 1px solid #656A6F;

}

.container-logo {
    display: flex;
    align-items: center;
}

.logo-svg {
    height: 30px;
    width: 70px;
}

.logo-name {
    margin-left: 10px;
    font-size: 30px;
    font-family: 'Roboto';
}

.logo-name span {
    color: #EDB200;
}



.container-tools {
    display: flex;
    align-items: center;
}


.style-block {

    height: 30px;
    width: 30px;
    display: flex;
    justify-content: center;
    align-items: center;
    background-color: #202123;
    border: 1px solid #656A6F;
    border-radius: 5px;
    margin-left: 15px;
}

.style-block:focus {
    outline-width: 0;
    border: 1px solid #EDB200;
}

.plus-svg {
    width: 14px;
    height: 14px;
    background: none;
    color: #656565;
}

.plus-block:hover {
    border-color: #EDB200;
}

.plus-block:hover .plus-svg {
    color: #EDB200;
}

.plus-block:focus .plus-svg {
    color: #EDB200;
}

.modal-plus {
    z-index: 1;
    top: 90px;
    margin-left: 190px;
}

.option-modal {
    height: 40px;
    font-family: Montserrat;
    text-decoration: none;
    display: flex;
    justify-content: flex-start;
    align-items: center;
    border-bottom: 1px solid #656A6F;
    outline: none;
}

.option-modal:hover .option-name {
    color: #EDB200;
}

.option-modal:hover .option-svg {
    color: #EDB200;
}

.option-modal:focus .option-name {
    color: #EDB200;
}

.option-modal:focus .option-svg {
    color: #EDB200;
}

.option-svg-container {
    width: 30px;
    height: 20px;
    display: flex;
    justify-content: center;
    align-items: center;
}

.option-svg {
    color: #656565;
}

.option-name {
    font-size: 15px;
    margin-left: 10px;
}



.bell-svg {
    width: 18px;
    height: 20px;
    background: none;
    color: #656565;
}

.notification-block:hover {
    border-color: #EDB200;
}

.notification-block:hover .bell-svg {
    color: #EDB200;
}

.notification-block:focus .bell-svg {
    color: #EDB200;
}

.notify-count {
    width: 10px;
    height: 10px;
    border-radius: 50%;
    background-color: red;
    position: absolute;
    margin-left: 27px;
    margin-bottom: 27px;
}


.person-block {
    cursor: pointer;
    background-color: #101112;
    padding: 0px 10px;
    font-family: 'Montserrat';
    display: flex;
    align-items: center;
    margin-left: 20px;
    border: none;
}

.person-block:focus {
    padding: 0px 9px;
    outline-width: 0;
    border: 1px solid #EDB200;
    border-radius: 5px;
}

.person-name {
    font-size: 20px;
}

.container-person-photo {
    margin-left: 20px;
    width: 50px;
    height: 50px;
    border-radius: 25px;
    overflow: hidden;
    border: 1px solid #202123;

}

.person-photo {
    width: 50px;
    height: 50px;
    object-fit: cover;
    background: none;
}

.modal-person {
    z-index: 1;
    right: 50px;
    top: 90px;
    width: fit-content;
}

.modal-notify {
    z-index: 1;
    right: 50px;
    top: 90px;
    width: 400px;
}

.option {
    padding: 0px 30px;
    display: flex;
    justify-content: center;

}

.option p {
    cursor: pointer;
    font-size: 15px;
    width: fit-content;
    height: 100%;
    padding: 10px 10px;
    border-bottom: 1px solid #656A6F;
}

.option-notify {
    padding: 0px 20px;
    display: flex;
    justify-content: center;

}

.option-notify p {
    text-align: start;
    cursor: pointer;
    font-size: 15px;
    width: fit-content;
    height: 100%;
    padding: 10px 0px;
    border-bottom: 1px solid #656A6F;
    word-break: keep-all;
}

.option-notify p:hover {
    background-color: #202123;
}

.subtitle {
    background-color: #101112;
}

.subtitle-outer-padding {
    padding: 0px 50px;
    border-bottom: 1px solid #656A6F;
}

.outer-padding {
    display: flex;
    justify-content: space-between;
    align-items: center;
    height: 100%;
    padding: 0px 50px;
}

.selection {
    color: #EDB200;
}

.selection:hover {
    text-decoration: underline;
}


.container-btns {
    margin-left: 50px;
    display: flex;
    gap: 15px;
}

.container-btns button {
    cursor: pointer;
    font-family: 'Montserrat';
    padding: 6px 20px;
    border-radius: 5px;
    border: none;
    background: none;
}

.login:hover {
    color: #EDB200;
}

.singup {
    border: 1px solid #F8F9FA !important;
}

.singup:hover {
    background-color: #F8F9FA10;
    color: #EDB200;
}
</style>