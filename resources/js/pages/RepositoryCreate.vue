<template>
    <div class="container-rep-create">
        <div class="rep-create">
            <div class="name-block">Создайте новый репозиторий</div>
            <form @submit.prevent="createRepository" class="container-form">
                <div>
                    <div class="name-input">Владелец *</div>
                    <div class="container-path">
                        <div class="user-name">
                            <div>{{ user.name }}</div><span class="slesh">/</span>
                        </div>
                        <input type="text" name="name" v-model="form.name" @input="errors.name=''" class="input"
                            placeholder="Название репозитория">
                    </div>

                    <span class="hint">{{ `${domain}/${user.name}/${form.name}` }}</span>
                    <div class="error" v-if="errors.name"><img class="alert" src="@/svg/alert.svg" /> {{
                        errors.name }}</div>
                </div>
                <div class="container-textarea">
                    <div class="name-input">Описание</div>
                    <div class="container-path">
                        <textarea ref="textarea" type="text" name="name" class="input textarea"
                            v-model="form.description" @input="adjustTextareaHeight"></textarea>
                    </div>
                </div>


                <div class="access-radio-group">
                    <div class="name-input">Доступ к репозиторию</div>
                    <div class="radio-options">
                        <label class="custom-radio">
                            <input type="radio" name="access" value="public" v-model="form.access">
                            <span class="radio-icon"></span>
                            <span class="radio-label">Публичный</span>
                        </label>

                        <label class="custom-radio">
                            <input type="radio" name="access" value="private" v-model="form.access">
                            <span class="radio-icon"></span>
                            <span class="radio-label">Приватный</span>
                        </label>
                    </div>
                </div>
                <button type="submit" class="create-btn">Создать репозиторий</button>
            </form>
            <Footer class="footer" />
        </div>
    </div>
</template>

<script>
import { mapGetters } from 'vuex/dist/vuex.cjs.js';
import Footer from '../components/Footer.vue';
import axios from 'axios';

export default {
    name: 'RepositoryCreate',
    components: {
        Footer
    },
    data() {
        return {
            form: {
                name: '',
                description: '',
                access: 'public',
            },
            errors: {
                name: ''
            },
            domain: window.location.origin
        };
    },
    computed: {
        ...mapGetters('authStore', ['user']),
    },
    methods: {
        adjustTextareaHeight() {
            const textarea = this.$refs.textarea;
            textarea.style.height = 'auto';
            textarea.style.height = `${textarea.scrollHeight}px`;
        },
        async createRepository() {
            await axios.post('/projects', this.form)
                .then(response => {
                    this.$router.push(`/${response.data.owner_name}/${response.data.name}`)
                })
                .catch(e => {
                    if (e.response && e.response.data.errors.name) {
                        this.errors.name = 'Уже есть репозиторий с таким именем';
                    }
                })
        }
    },
    mounted() {
        this.adjustTextareaHeight();
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
}

.container-form {
    font-size: 20px;
    display: flex;
    flex-direction: column;
    gap: 35px;
}

.name-input {}

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

.container-textarea::after {
    margin-top: 10px;
    content: '';
    display: block;
    width: 100%;
    height: 1px;
    background-color: #343A40;
}




.access-radio-group {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.radio-options {
    display: flex;
    gap: 20px;
    margin-top: 10px;
}

.custom-radio {
    display: flex;
    align-items: center;
    gap: 8px;
    cursor: pointer;
    position: relative;
}

.custom-radio input[type="radio"] {
    position: absolute;
    opacity: 0;
    width: 0;
    height: 0;
}

.radio-icon {
    display: inline-block;
    width: 18px;
    height: 18px;
    border: 2px solid #6e7681;
    border-radius: 50%;
    position: relative;
    transition: all 0.2s ease;
}

.custom-radio:hover .radio-icon {
    border-color: #EDB200;
}

.custom-radio input[type="radio"]:checked+.radio-icon {
    border-color: #EDB200;
    background-color: #c19100;
}

.radio-icon::after {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: white;
    opacity: 0;
}

.custom-radio input[type="radio"]:checked+.radio-icon::after {
    opacity: 1;
}

.radio-label {
    font-size: 16px;
    color: #c9d1d9;
}

.create-btn {
    cursor: pointer;
    align-self: flex-start;
    font-size: 18px;
    padding: 13px 35px;
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


.hint {
    font-weight: 100;
    font-size: 0.9vw;
    color: #67707b;
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
</style>