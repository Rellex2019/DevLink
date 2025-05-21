<template>
    <Header v-if="$route.path != '/registration' && $route.path != '/login' && $route.path != '/'" />
    <router-view />
</template>

<script>
import Header from './components/Header.vue';


export default {
    name: 'App',
    components: {
        Header,
    },
    beforeCreate() {
        this.$store.commit('authStore/initializeStore');
    }
};

</script>

<style>
.outer-padding {
    display: flex;
    justify-content: space-between;
    align-items: center;
    height: 100%;
    padding: 0px 50px;
}
</style>
