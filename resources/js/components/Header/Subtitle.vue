<template>
    <div class="container-pages">
        <div class="page" v-for="page in pages" :class="isActive(page) ? 'active' : null">
            <router-link :key="this.$route.fullPath" class="link" :to="page.url">{{ page.name }}</router-link>
        </div>
    </div>
</template>
<script>
export default{
    name: 'Subtitle',
    props:{
        pages:{
            required: true
        }
    },
    methods: {
        isActive(page) {
            return this.$route.path === page.url || (this.$route.query.chapter && this.$route.query.chapter === page.query);
        }
    }
}
</script>
<style scoped>
    .container-pages{
        display: flex;
        gap: 30px;
    }
    .page{
        padding: 0 10px;
        display: flex;
        align-items: center;
        height: 35px;
        font-size: 16px;
        font-family: 'Montserrat';
        font-weight: 500;
    }
    .link{
        user-select: none;
        text-decoration: none;
        color: inherit;
    }
    .active{
        border-bottom: 1px solid #EDB200;
    }
</style>