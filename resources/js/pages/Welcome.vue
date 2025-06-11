<template>
    <div class="outer-padding">
        <div class="container-main-block">
            <div class="main-block">
                <div class="title-page">
                    Разрабатывай новые решения на <br> платформе совместного кодирования
                </div>
                <div class="title-ps">Связываем разработчиков, создаем будущее!</div>
                <!-- <div class="container-input">
                    <input type="text">
                    <button></button>
                </div> -->
            </div>
        </div>
        <div class="container-block">

        </div>
    </div>

</template>
<script>
export default {
    name: 'Welcome',
    data() {
        return {
        }
    },
}
</script>
<style scoped>
*{
    font-family: 'Montserrat';
}
.outer-padding {}

.container-main-block {
    position: fixed;
    width: 100%;
    height: 1050px;
    z-index: -1;
    top: 0;
    background: linear-gradient(#101112, #1A1B1D);
    display: flex;
    justify-content: center;
}

.main-block {
    margin-top: 150px;
}

.title-page {
    font-size: 60px;
    line-height: 75px;
    font-weight: bold;
    text-align: center;
    color: #EDB200;
}

.container-block {
    margin-top: 500px;
    width: 100%;
    height: 2000px;
    z-index: 2;
    background-color: red;
}

.title-ps {
    font-size: 20px;
}
.container-input{

}
</style>