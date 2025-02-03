
<template>
<Header/>
<router-view />
</template>

<script>
import Header from './components/Header.vue';


export default {
    name: 'App',
    components:{
        Header,
    }
};

</script>

<style>
    .outer-padding{
        display: flex;
        justify-content: space-between;
        align-items: center;
        height: 100%;
        padding: 0px 50px;
    }
</style>
