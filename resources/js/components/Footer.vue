<template>
    <div class="container-short-footer">
        <div class="short-footer">
            <RouterLink to="/condition" class="option">Условия</RouterLink>
            <RouterLink to="/privacy" class="option">Конфиденциальность</RouterLink>
            <RouterLink to="/documents" class="option">Документы</RouterLink>
            <RouterLink to="/contact" class="option">Контакты</RouterLink>
        </div>
    </div>
</template>
<script>
import { RouterLink } from 'vue-router';

export default{
    name: 'Footer'
}
</script>
<style scoped>
.container-short-footer{

    width: 100%;
    display: flex;
    justify-content: center;
}
.short-footer{
    width: 100%;
    max-width: 550px;
    display: flex;
    justify-content: space-between;
    font-size: 16px;
    color: #ababab;
    font-weight: lighter;
}
.option{
    text-decoration: none;
    color: inherit;
}
.option:hover{

    color: #fff;
}
</style>