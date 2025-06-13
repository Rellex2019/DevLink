<template>
    <div class="split-screen">
        <!-- Левый блок (преимущества) -->
        <div class="left-panel">

            <div>
                <div class="title-advantages">Создайте бесплатную учетную запись</div>
                <p class="acces-text">И получите доступ к</p>
                <div class="ul-list">
                    <div class="li-list">
                        <div class="circle"></div>Создание и просмотру репозиториев
                    </div>
                    <div class="li-list">
                        <div class="circle"></div>Удобному взаимодействию с командой <br> через задачи
                    </div>
                    <div class="li-list">
                        <div class="circle"></div>Просмотру изменений вашем проекте
                    </div>
                </div>
            </div>
        </div>

        <!-- Правый блок (форма) -->
        <div class="right-panel">
            <div class="container-right">
                <div class="title">Регистрация в Dev<span style="color:#EDB200">Link</span></div>

                <form @submit.prevent="handleSubmit" class="form">
                    <div class="form-group">
                        <label>Email *</label>
                        <input type="email" v-model="form.email" required>

                        <!-- ВЫВОД ОШИБКИ -->


                    </div>

                    <div class="form-group">
                        <label>Пароль *</label>
                        <input type="password" v-model="form.password" required minlength="8">
                        <span class="hint">Пароль должен содержать 8 символов включая числа и буквы.</span>
                    </div>

                    <div class="form-group">
                        <label>Ник пользователя *</label>
                        <input type="text" v-model="form.name" @input="checkNick" required>
                        <span class="hint" v-if="!usernameError">Ник может содержать числа и буквы или одиночные
                            дефисы.</span>
                        <div class="error" v-if="usernameError"><img class="alert" src="@/svg/alert.svg" /> {{
                            usernameError }}</div>
                    </div>


                    <button type="submit" class="submit-btn">Продолжить ></button>
                </form>

                <p class="terms">
                    Создавая учетную запись, вы соглашаетесь с <a href="#" class="link">Условиями обслуживания</a> . Для
                    получения дополнительной информации о политике конфиденциальности DevLink см. <a href="#"
                        class="link">Заявление о конфиденциальности DevLink </a>.
                </p>
            </div>
            <div class="login-in">Уже есть аккаунт? <RouterLink style="color: #101112; font-weight: 100;" to="/login">
                    Войти в аккаунт</RouterLink>
            </div>
        </div>
    </div>
</template>

<script>
import { RouterLink } from 'vue-router';
import { mapGetters } from 'vuex/dist/vuex.cjs.js';

export default {
    data() {
        return {
            form: {
                email: '',
                password: '',
                name: ''
            },
            usernameError: ''
        }
    },
    methods: {
        handleSubmit() {
            if (!this.usernameError) {
                axios.get('/sanctum/csrf-cookie').then(response => {
                    axios.post('/register', this.form)
                        .then(response => {
                            this.$store.commit('authStore/setUser', response.data.user);
                            this.$router.push('/dashboard');
                        })
                });

            }
        },
        checkNick() {
            const regex = /^[a-zA-Z0-9]+(-[a-zA-Z0-9]+)*$/;

            if (!this.form.name) {
                this.usernameError = 'Поле не может быть пустым';
            } else if (!regex.test(this.form.name)) {
                this.usernameError = 'Допустимы только буквы, цифры и одиночные дефисы';
            } else {
                this.usernameError = '';
            }
        }
    },
    computed: {
        ...mapGetters('authStore', ['isAuthenticated', 'user']),
    },
    mounted() {
        if (this.isAuthenticated) {
            this.$router.push('/dashboard')
        }
    }
}
</script>

<style scoped>
.circle {
    margin-top: 0.45vw;
    width: 0.52vw;
    height: 0.52vw;
    border-radius: 0.26vw;
    background-color: white;
}

.title-advantages {
    font-size: 1.87vw;
}

.acces-text {
    margin-top: 1.56vw;
    font-size: 1.25vw;
}

.ul-list {
    font-size: 1.04vw;
    margin-top: 1.04vw;
}

.li-list {
    display: flex;

    line-height: 1.25vw;
    gap: 0.75vw;
    margin-top: 0.78vw;
}

.split-screen {
    font-family: 'Montserrat';
    display: flex;
    height: 100vh;
    width: 100vw;

    overflow: hidden;
}

.left-panel,
.right-panel {
    position: relative;
    flex: 1;
    display: flex;
    flex-direction: column;
    min-width: 0;
    align-items: center;
    padding-top: 15vh;
    overflow-y: auto;
}

.left-panel {
    position: relative;
    font-weight: bold;
    background: linear-gradient(to bottom, #101112, #202123);
    color: white;
    background-color: #f8f9fa;
    border-right: 1px solid #e1e4e8;
    overflow-y: hidden;
}

.left-panel::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('@/images/svet.png') center/cover no-repeat;
    background-position: 0px 15vw;
    background-size: 100%;
    z-index: 1;
    opacity: 1;
}


.right-panel {
    background-color: white;
}

.container-right {
    width: 33.33vw;
}

.title {
    font-size: 1.66vw;
    font-weight: bold;
}

.form {
    margin-top: 2.34vw;
}

.form-group {
    font-size: 1.04vw;
    margin-bottom: 1.3vw;
}

.form-group label {
    display: block;
    margin-bottom: 0.31vw;
    font-weight: 600;
}

.form-group input {
    width: 100%;
    box-sizing: border-box;
    font-size: 1.04vw;
    padding: 0.6vw 0.62vw;
    border: 1px solid #d1d5da;
    border-radius: 0.26vw;
}

.hint {
    font-weight: 100;
    font-size: 0.83vw;
    color: #586069;
    display: block;
    margin-top: 0.26vw;
}

.error {
    gap: 0.6vw;
    display: flex;
    align-items: center;
    font-weight: 100;
    font-size: 0.83vw;
    color: #FF0E0E;
    margin-top: 0.26vw;
}

.alert {
    width: 1vw;
    height: 1vw;
}

.submit-btn {
    width: 100%;
    height: 5.55vh;
    padding: 0.62vw;
    background-color: #161718;
    color: white;
    border: none;
    border-radius: 0.52vw;
    font-size: 0.93vw;
    font-weight: bold;

    cursor: pointer;
}

.submit-btn:hover {
    background-color: #292b2d;
}

.submit-btn:active {
    background-color: #101112;
}

.terms {
    width: 32.13vw;
    font-size: 0.8vw;
    margin-top: 0.78vw;
    font-weight: normal;
}

.link {
    color: #008230;
}

.login-in {
    position: absolute;
    font-weight: 500;
    top: 1.56vw;
    right: 3.12vw;
    font-size: 0.85vw;
}

/* Адаптивность - на мобильных переходим на вертикальную раскладку */
@media (max-width: 768px) {
    .split-screen {
        flex-direction: column;
        height: auto;
    }

    .left-panel {
        border-right: none;
        border-bottom: 1px solid #e1e4e8;
    }
}
</style>