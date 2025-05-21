<template>
    <div class="container-rep-create">
        <div class="rep-create">

            <div class="container-forms">

                <transition name="fade-slide" mode="out-in">
                    <form @submit.prevent="submitForm" v-if="currentStep === 1" key="step1" class="container-form">
                        <div class="name-block">Создайте свою команду</div>
                        <div class="container-textarea">
                            <div class="name-input">Названия организации *</div>
                            <div class="container-path">
                                <input type="text" class="input" name="name" v-model="formDataCreate.name" required
                                    id="">
                            </div>
                        </div>
                        <div class="container-textarea">
                            <div class="name-input">Контактный адрес электронной почты *</div>
                            <div class="container-path">
                                <input type="email" class="input" name="email" v-model="formDataCreate.email" required
                                    id="">
                            </div>
                        </div>
                        <div class="container-checkbox">
                            <div class="custom-checkbox">
                                <input type="checkbox" v-model="isChecked" required @change="$emit('change', isChecked)"
                                    class="custom-checkbox__input">
                                <span class="custom-checkbox__checkmark" @click="isChecked = !isChecked"></span>
                                <span class="custom-checkbox__label"> Я являюсь человеком и принимаю 
                                    <a href="#" style="color: inherit;">
                                        Условия обслуживания
                                    </a>.
                                </span>
                            </div>
                        </div>
                        <button class="create-btn" type="submit">Продолжить ></button>
                    </form>
                </transition>

                <transition name="fade-slide" mode="out-in">
                    <div v-if="currentStep === 2" key="step2" class="container-form">
                        <div class="name-block">Добро пожаловать <br>в {{ teamInfo.name }}</div>
                        <div class="container-textarea2">
                            <div class="name-input">Выберите аватар (необязательно)</div>
                            <div class="container-path2 " @click="triggerFileInput">

                                <div class="container-team-avatar">
                                    <img v-if="avatarPreview" :src="avatarPreview" alt="Аватар команды"
                                        class="avatar-preview">
                                    <div v-else class="avatar-placeholder">
                                        <span>+</span>
                                    </div>
                                </div>
                                <input type="file" ref="fileInput" class="input" accept="image/jpeg,image/png"
                                    style="display: none;" @change="handleFileUpload">
                            </div>
                        </div>
                        <div class="container-textarea2">
                            <div class="name-input">Добавить членов команды</div>
                            <div class="container-path2">
                                <input type="search" class="input" name="email" id="">
                            </div>
                        </div>
                        <button class="btn-back" @click="completeTeamCreate">Завершить настройку</button>
                    </div>
                </transition>


            </div>
            <Footer class="footer" />
        </div>
    </div>
</template>

<script>
import { mapGetters } from 'vuex/dist/vuex.cjs.js';
import Footer from '../components/Footer.vue';
import axios from 'axios';

export default {
    name: 'TeamCreateView',
    components: {
        Footer
    },
    data() {
        return {
            currentStep: 1,
            avatarFile: null,
            avatarPreview: null,
            isChecked: false,
            formDataCreate: {
                name: '',
                email: '',
            },
            teamInfo: null,
        }
    },
    computed: {
        ...mapGetters('authStore', ['user']),
    },
    methods: {
        async submitForm() {
            console.log('Форма отправлена:', this.formDataCreate);
            await axios.post('/team/create', this.formDataCreate)
                .then(response => {
                    this.teamInfo = response.data;
                    this.currentStep++;
                })

        },
        async completeTeamCreate() {
            const formData = new FormData();
            formData.append('logo', this.avatarFile || '')
            await axios.post(`/team/${this.teamInfo.id}/change`, formData,
                {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                }
            )
                .then(response => {
                    // $router.push('/')
                })

        },
        triggerFileInput() {
            this.$refs.fileInput.click()
        },
        handleFileUpload(event) {
            const file = event.target.files[0]
            if (!file) return

            if (!file.type.match('image/png') && !file.type.match('image/jpeg')) {
                alert('Пожалуйста, выберите изображение')
                return
            }


            if (file.size > 2 * 1024 * 1024) {
                alert('Файл слишком большой. Максимальный размер - 2MB')
                return
            }

            this.avatarFile = file

            const reader = new FileReader()
            reader.onload = (e) => {
                this.avatarPreview = e.target.result
            }
            reader.readAsDataURL(file)
        },
    },
    mounted() {
    }
}
</script>

<style scoped>
.container-rep-create {
    background-color: #101112;
    display: flex;
    justify-content: center;
    width: 100vw;
    min-height: calc(100vh - 80px);
    font-family: 'Montserrat';
    font-size: 20px;
}

.rep-create {
    margin-top: clamp(50px, 5vw, 100px);
    color: #FFFFFF;
    background-color: #101112;
    display: flex;
    flex-direction: column;
    width: 40vw;
    max-width: 725px;
    min-height: 75vh;
    gap: 60px;
}

.name-block {

    font-size: 32px;
    font-weight: 500;
    white-space: nowrap;
}

.container-forms {
    min-height: 450px;
    position: relative;
}

.container-form {
    width: 100%;
    position: absolute;
    font-size: 20px;
    display: flex;
    flex-direction: column;
    gap: 35px;
}


.container-path {
    display: flex;
    gap: 15px;
}

.user-name {
    display: flex;
    align-items: center;
    font-size: 26px;
    font-weight: 500;
    gap: 15px;
}

.slesh {
    font-size: 30px;
    font-weight: 900;
}

.name-input {
    font-size: 20px;
}

.input {
    font-size: 20px;
    padding: 8px 10px;
    border-radius: 5px;
    border: 1px solid #343A40;
    background-color: #161718;
    color: #FFFFFF;
    width: 100%;
}

.textarea {
    resize: none;
    overflow-y: hidden;
    min-height: 40px;
}

.container-textarea {
    display: flex;
    flex-direction: column;
    gap: 15px;
    font-size: 22px;
}





.custom-checkbox {
    display: inline-flex;

    align-items: center;
    position: relative;
    cursor: pointer;
    user-select: none;
    padding-left: 30px;
    margin: 5px 0;
}

.custom-checkbox__input {
    position: absolute;
    opacity: 0;
    cursor: pointer;
    height: 0;
    width: 0;
}

.custom-checkbox__checkmark {
    position: absolute;
    top: 0;
    left: 0;
    height: 20px;
    width: 20px;
    background-color: #161718;
    border: 2px solid #343A40;
    border-radius: 4px;
    transition: all 0.3s;
}

.custom-checkbox:hover .custom-checkbox__checkmark {
    border-color: #888;
}

.custom-checkbox__input:checked~.custom-checkbox__checkmark {
    border-color: #EDB200;
}

.custom-checkbox__checkmark:after {
    content: "";
    position: absolute;
    display: none;
    left: 7px;
    top: 2px;
    width: 5px;
    height: 10px;
    border: solid #EDB200;
    border-width: 0 2px 2px 0;
    transform: rotate(45deg);
}

.custom-checkbox__input:checked~.custom-checkbox__checkmark:after {
    display: block;
}

.custom-checkbox__label {
    margin-top: 2px;
    margin-left: 8px;
    font-size: 16px;
}








.create-btn {
    width: 100%;
    cursor: pointer;
    align-self: flex-start;
    font-size: 21px;
    font-weight: bold;
    padding: 18px 35px;
    border-radius: 5px;
    border: 1px solid #EDB200;
    background: none;
    color: white;
}

.create-btn:hover {
    background-color: #edb20033;
}

.create-btn:active {
    background-color: #edb20092;
}

.footer {

    margin-top: clamp(10px, 5vw, 100px);
}



/* Анимации */
.fade-slide-enter-active,
.fade-slide-leave-active {
    transition: all 0.5s ease;
}

.fade-slide-enter-from {
    opacity: 0;
    transform: translateX(50px);
}

.fade-slide-leave-to {
    opacity: 0;
    transform: translateX(-50px);
}

.fade-slide-enter-to,
.fade-slide-leave-from {
    opacity: 1;
    transform: translateX(0);
}


.container-team-avatar {
    cursor: pointer;
    width: 100px;
    height: 100px;
    border-radius: 50px;
    background-color: #161718;
    border: 1px solid #343A40;
    overflow: hidden;
    display: flex;
    justify-content: center;
    align-items: center;
}

.avatar-preview {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.avatar-placeholder {
    font-size: 32px;
    color: #999;
}

.container-path2 {
    display: flex;
    gap: 25px;
}

.container-textarea2 {
    display: flex;
    flex-direction: column;
    gap: 20px;
    font-size: 22px;
}

.btn-back {
    cursor: pointer;
    border: none;
    color: #edb200ce;
    background: none;
    font-size: 14px;
}
</style>