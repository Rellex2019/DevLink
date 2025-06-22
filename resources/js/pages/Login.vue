<template>
    <div class="login-container">
        <div class="form-container">
            <img class="logo-svg" src="@/svg/logoLogin.svg" alt="">
            <div class="title">Вход в <span style="font-weight: 500;">Dev<span style="color:#EDB200">Link</span></span></div>
            <form class="container-inputs" @submit.prevent="handleSubmit">
                <label class="title-field">Email</label>
                <input v-model="form.email" class="input" required type="email">
                <label class="title-field" style="margin-top: 1.56vw;">Пароль 
                    <!-- <div class="forget">Забыли пароль?</div> -->
                </label>
                <input v-model="form.password" class="input" required type="password">
                <div class="error" v-if="error"><img class="alert" src="@/svg/alert.svg" /> Email или пароль введены неверно</div>
                <button class="login-btn">Войти</button>
                <router-link :to="{name: 'registration'}" class="create-acc"> Ещё нет аккаунта?</router-link>
            </form>
        </div>
    </div>
</template>
<script>
import { mapGetters } from 'vuex/dist/vuex.cjs.js';

export default {
    name: 'Login',
    data()
    {
        return{
            form:{
                email: '',
                password: '',
            },
            error: ''
        }
    },
    methods:{
        handleSubmit()
        {
            axios.post('/login', this.form)
            .then(response =>{
                this.error = '';
                this.$router.push('dashboard');
                this.$store.commit('authStore/setUser', response.data.user);
            })
            .catch(e=>{
                this.error = e.response.data.message;
            })
        }
    },
    computed: {
        ...mapGetters('authStore', ['isAuthenticated', 'user']),
    },
}
</script>

<style scoped>
.login-container {
    font-family: 'Montserrat';
    font-weight: normal;
    width: 100vw;
    height: 100vh;
    overflow: hidden;
    background-color: #101112;

    display: flex;
    justify-content: center;
}

.form-container {
    display: flex;
    flex-direction: column;
    align-items: center;
    margin-top: 7.81vw;
}

.logo-svg {
    width: 6.25vw;
}

.title {
    margin-top: 2.6vw;
    color: #F8F9FA;
    font-size: 1.66vw;
}
.container-inputs{
    margin-top: 1.56vw;
    display: flex;
    flex-direction: column;
    width: 20.5vw;
    color: #F8F9FA;
    padding: 1.56vw 1.3vw;
    background-color: #161718;
    border-radius: 0.26vw;
    border: 1px solid #343A40;
}
.title-field{
    align-items: center;
    display: flex;
    justify-content: space-between;
    width: 100%;
    font-size: 1.04vw;
    margin-bottom: 0.78vw;
}
.input{
    background-color: #101112;
    border: 1px solid #343A40;
    height: 4.44vh;
    font-size: 0.9vw;
    padding: 0.5vw 0.9vw;
    color: #F8F9FA;
    border-radius: 0.26vw;
    box-sizing: border-box;
}
.input:focus{
    outline:1px solid #EDB200;
    border-radius: 0.26vw;
    border: none;
}
.forget{
    cursor: pointer;
    font-size: 0.67vw;
    color: #656A6F;
}
.forget:hover{
    color: #EDB200;
}
.create-acc{
    text-decoration: none;
    margin-top: 1vw;
    cursor: pointer;
    font-size: 0.8vw;
    color: #656A6F; 
}
.create-acc:hover{
    color: #EDB200;
}
.login-btn{
    cursor: pointer;
    width: 100%;
    padding: 0.67vw 0vw;
    border-radius: 0.26vw;
    border: 1px solid #EDB200;
    font-family: 'Montserrat';
    font-weight: bold;
    color: #F8F9FA;
    font-size: 0.93vw;
    background: none;
    margin-top: 1.56vw;
}
.login-btn:hover{
    background-color: #EDB200;
    color: #101112;
}
.login-btn:active{
    background-color: #af8300;
}
.error {
    gap: 0.6vw;
    display: flex;
    align-items: center;
    font-weight: 100;
    font-size: 0.83vw;
    color: #FF0E0E;
    margin-top: 0.8vw;
}
.alert {
    width: 1vw;
    height: 1vw;
}
</style>