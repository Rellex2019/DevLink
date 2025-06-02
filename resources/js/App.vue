<template>
    <Header v-if="$route.path != '/registration' && $route.path != '/login' && $route.path != '/'" />
    <router-view />
</template>

<script>
import Header from './components/Header.vue';
import { mapGetters } from 'vuex/dist/vuex.cjs.js';

export default {
    name: 'App',
    components: {
        Header,
    },
    beforeCreate() {
        this.$store.commit('authStore/initializeStore');
    },

    computed: {
        ...mapGetters('authStore', ['user']),
    },
};

</script>

<style></style>
