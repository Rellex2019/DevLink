<template>
    <div class="outer-padding">
        <div class="container-main-block">
            <div class="main-block" :style="{
                opacity: mainBlockOpacity,
                transform: `scale(${mainBlockScale}) translateY(${mainBlockTranslate}px)`
            }">
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
        <div class="container-block-examples">
            <div class="container-slide" :style="{ backgroundImage: `url('${appBaseUrl}/resources/frame_rep.png')` }">
            </div>
            <div class="control-slide-panel">
                <div class="container-btn">
                    <button v-for="(btn, index) in buttons" :key="index" @click="selectButton(index)"
                        :class="{ active: activeButton === index }">
                        {{ btn }}
                    </button>
                </div>
            </div>
        </div>
        <div class="trade-proposal-container"></div>

    </div>

</template>
<script>
export default {
    name: 'Welcome',
    data() {
        return {
            mainBlockOpacity: 1,
            mainBlockScale: 1,
            mainBlockTranslate: 0,

            buttons: ['Команды', 'Задачи', 'Репозитории'],
            activeButton: 0,
        };
    },
    computed: {
        appBaseUrl() {
            return window.location.origin;
        },
    },
    mounted() {
        window.addEventListener('scroll', this.handleScroll);
    },
    beforeDestroy() {
        window.removeEventListener('scroll', this.handleScroll);
    },
    methods: {
        selectButton(index) {
            this.activeButton = index;
        },
        handleScroll() {
            const scrollY = window.scrollY;
            const fadeStart = 100;
            const fadeEnd = 500;

            this.mainBlockOpacity = Math.max(0, 1 - scrollY / fadeEnd);

            this.mainBlockScale = Math.max(0.8, 1 - scrollY / (fadeEnd * 2));

            this.mainBlockTranslate = Math.max(-100, -scrollY / 5);
        },
    },
};
</script>
<style scoped>
* {
    font-family: 'Montserrat';
}

.outer-padding {}

.container-main-block {
    position: fixed;
    width: 100%;
    height: 1400px;
    z-index: -1;
    top: 0;
    display: flex;
    justify-content: center;
    background-color: rgb(16, 17, 18);
    background-image: url('@/images/svet.png');
    background-repeat: no-repeat;
    background-position: 0 -250px;
    background-size: cover;
}

.main-block {
    display: flex;
    flex-direction: column;
    gap: 50px;
    padding-top: 150px;

    transition: opacity 0.3s ease, transform 0.3s ease;

}

.title-page {
    font-size: 60px;
    line-height: 75px;
    font-weight: bold;
    text-align: center;
    color: #EDB200;
}

.title-ps {
    color: #FFFFFF;
    font-weight: 500;
    font-size: 20px;
    text-align: center;
}


.container-block-examples {
    margin-top: 900px;
    width: 100%;
    height: 575px;
    background: linear-gradient(180deg, #EDB20005 2.75%, #EDB20010 25.75%, #18191B 95.1%);
    display: flex;
    flex-direction: column;
    align-items: center;
}

.container-slide {
    margin-top: -200px;
    width: 1100px;
    height: 640px;
    border: 3px solid #EDB20080;
    box-shadow: 0 0 20px #EDB20070;
    border-radius: 25px 25px 0px 0px;
    border-bottom: 0px;
    background-size: contain;
    background-repeat: no-repeat;

}

.control-slide-panel {
    height: 130px;
    width: 100%;
    background: linear-gradient(180deg, #202123 0%, #101112 100%);
    border-top: 1px solid #656A6F;

    display: flex;
    justify-content: center;
    align-items: center;
}

.container-btn {
    padding: 5px;
    position: relative;
    width: 400px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    border: 1px solid #656A6F;
    border-radius: 40px;
    height: fit-content;
}

.control-slide-panel button {
    position: relative;
    border: none;
    color: white;
    padding: 10px 20px;
    font-size: 16px;
    cursor: pointer;
    border-radius: 40px;
    background: transparent;
    z-index: 1;
    transition: color 0.5s ease;
}

.control-slide-panel button::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: linear-gradient(to bottom, #EDB20070, #EDB20036);
    border-radius: 40px;
    opacity: 0;
    transition: opacity 0.3s ease;
    z-index: -1;
}

.container-btn button.active::before {
    opacity: 1;
}


.trade-proposal-container{
    width: 100%;
    height: 910px;
    background-color: #101112;
}
</style>