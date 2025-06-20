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
            <div class="image-cont">
            <transition name="slide-fade" mode="out-in">

                    <div class="container-slide" :key="activeSlide"
                    :style="{ backgroundImage: `url('/resources/frame_${slideImages[activeSlide]}.png')` }"></div>
                

            </transition>
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
        <div class="trade-proposal-container">
            <div class="trade-proposal">
                <div class="row">
                    <div class="column">
                        <div class="text-proposal">
                            <span>Твои секреты и твой бизнес: защищены.</span> Работайте приватно
                            в своей команде или делитесь кодом с сообществом.
                        </div>
                        <div class="image-block" :style="{ backgroundImage: `url('/resources/utp1.png')` }">

                        </div>
                    </div>
                    <div class="column">
                        <div class="text-proposal">
                            <span>Удобная система задач.</span>
                            Cоздавайте статусы, распределяйте работу и отслеживайте
                            прогресс в удобном Kanban-стиле.
                        </div>
                        <div class="image-block" :style="{ backgroundImage: `url('/resources/utp2.png')` }">

                        </div>
                    </div>
                    <div class="column">
                        <div class="text-proposal">
                            <span>Командная разработка
                                без лишних сложностей.</span> Добавляйте участников, контролируйте
                            доступ и сосредоточьтесь на коде.
                        </div>
                        <div class="image-block" :style="{ backgroundImage: `url('/resources/utp3.png')` }">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="column-info">
                        <p>4x быстрее</p>
                        к разработка в небольших командах
                    </div>
                    <div class="column-info">
                        <p>Попробуйте бесплатно</p>
                        и сосредоточьтесь на коде, <br>
                        а не на процессах.
                    </div>
                </div>
            </div>
        </div>
        <div class="container-tarifs-block">
            <div class="tarifs-block">
                <div class="name-tarifs-block">
                    Тарифные планы
                    <p>Создавайте, просматривайте и редактируйте репозитории</p>
                </div>

                <div class="tarifs-container">
                    <div class="tarif default">
                        <div class="bonus-container">
                            <p>Стартовый</p>
                            <div class="polosa"></div>
                            Бесплатно
                        </div>
                        <div class="permissions default">
                            <div class="permission">
                                <img src="/resources/arrow-black.svg" alt="Стрелочка">
                                <p>Публичные и приватные репозитории</p>
                            </div>
                            <div class="permission">
                                <img src="/resources/arrow-black.svg" alt="Стрелочка">
                                <p>Создание команды без участников</p>
                            </div>
                        </div>
                        <button @click="$router.push('/registration')">Начать бесплатно</button>
                    </div>
                    <div class="tarif premium">
                        <div class="bonus-container">
                            <p>Премиум</p>
                            <div class="polosa"></div>
                            129 руб
                        </div>
                        <div class="permissions default">
                            <div class="permission">
                                <img src="/resources/arrow-yellow.svg" alt="Стрелочка">
                                <p>Создание команд</p>
                            </div>
                            <div class="permission">
                                <img src="/resources/arrow-yellow.svg" alt="Стрелочка">
                                <p>Создание статусов и задач</p>
                            </div>
                            <div class="permission">
                                <img src="/resources/arrow-yellow.svg" alt="Стрелочка">
                                <p>Назначение команд на проекты</p>
                            </div>
                        </div>
                        <button @click="$router.push('/registration')">Попробовать премиум</button>
                    </div>
                </div>
                <img class="svet" id="svet1" src="@/images/svet.png" alt="">
                <img class="svet" id="svet2" src="@/images/svet.png" alt="">
            </div>

        </div>
    </div>
    <footer>
        <Footer />
    </footer>
</template>
<script>
import Footer from '../components/Footer.vue';

export default {
    name: 'Welcome',
    data() {
        return {
            mainBlockOpacity: 1,
            mainBlockScale: 1,
            mainBlockTranslate: 0,

            buttons: ['Репозитории', 'Задачи', 'Команды' ],
            slideImages: ['rep', 'task', 'team' ],
            activeButton: 0,
            activeSlide: 0,
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
    components: {
        Footer
    },
    methods: {
        selectButton(index) {
            this.activeButton = index;
            this.activeSlide = index;
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

.outer-padding{
    z-index: -1;
}
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
    width: 100%;
    height: 100%;

    background-size: contain;
    background-repeat: no-repeat;

}
.image-cont{
    margin-top: -200px;
    width: 1100px;
    height: 640px;
    border: 3px solid #EDB20080;
    box-shadow: 0 0 20px #EDB20070;
    border-radius: 25px 25px 0px 0px;
    border-bottom: 0px;
    background-color: #101112;
    overflow: hidden;
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


.trade-proposal-container {
    margin-top: -1px;
    width: 100%;
    height: 910px;
    background-color: #101112;
    display: flex;
    justify-content: center;
    align-items: end;
    border-bottom: 1px solid #656A6F;
}

.trade-proposal {
    width: 1100px;
}

.row {
    display: flex;
}

.column {
    gap: 25px;
    flex: 1;
    border: 1px solid #656A6F;
    display: flex;
    flex-direction: column;
}

.column-info {
    color: #7B7B7B;
    font-size: 17px;
    gap: 10px;
    flex: 1;
    border: 1px solid #656A6F;
    border-bottom: 0px;
    display: flex;
    height: 275px;
    flex-direction: column;
    padding: 0px 40px;
}

.column-info p {
    color: #FFFFFF;
    font-size: 24px;
    font-weight: 500;
    margin-top: 100px;
}

.text-proposal {
    color: #7B7B7B;
    padding: 60px 40px;
    padding-bottom: 0px;
    font-size: 17px;
    font-weight: 400;
    height: 105px;
}

.text-proposal span {
    color: #FFFFFF;
}

.image-block {
    align-self: flex-end;
    width: calc(100% - 40px);
    border-radius: 15px 0 0 0;
    border: 1px solid #656A6F;
    height: 363px;
    border-right: 0px;
    border-bottom: 0px;
}



.container-tarifs-block {
    overflow: hidden;
    z-index: -1;
    position: relative;
    padding-top: 30px;
    background-color: #101112;
    width: 100%;
    display: flex;
    justify-content: center;
}

.tarifs-block {
    width: 1100px;
    display: flex;
    flex-direction: column;
}

.name-tarifs-block {
    gap: 20px;
    color: #FFFFFF;
    font-size: 36px;
    font-weight: 500;
    display: flex;
    flex-direction: column;
    align-items: center;
}

.name-tarifs-block p {
    font-size: 22px;
}

.tarifs-container {
    margin-top: 80px;
    padding: 70px 0px;
    border: 2px solid #EDB20050;
    border-bottom: 0px;
    box-shadow: 0px -5px 4px #EDB20040;
    border-radius: 25px 25px 0px 0px;
    background-color: #101112;

    display: flex;
    justify-content: center;
    gap: 60px;
}

.tarif {
    transition: 0.3s;
    border-radius: 15px;
    height: 420px;
    width: 255px;
    padding: 40px 40px;

    display: flex;
    flex-direction: column;
    justify-content: space-between;
}
.tarif:hover {
    transform: scale(1.03);
}
.tarif.default {
    border: 3px solid #202123;
    box-shadow: 0px 0px 4px #F8F9FA;
}
.tarif.default:hover {
    box-shadow: 0px 0px 6px #F8F9FA;
}
.tarif.premium {
    border: 3px solid #EDB20030;
    box-shadow: 0px 0px 7px #EDB200;
}
.tarif.premium:hover {
    box-shadow: 0px 0px 16px #EDB200;
}
.bonus-container {
    gap: 25px;
    display: flex;
    flex-direction: column;
    align-items: center;
    color: #FFFFFF;
    font-size: 24px;
    font-weight: 500;
}

.polosa {
    display: block;
    width: 80px;
    height: 1px;
    background-color: #FFFFFF;
}

.bonus-container p {
    font-size: 32px;
}

.permissions {
    margin-top: 30px;
    font-size: 17px;
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.default {
    color: #C6C6C6;
}

.permission {
    display: flex;
    align-items: center;
    gap: 10px;
}

.tarif button {
    cursor: pointer;
    transition: 0.3s;
    color: #F8F9FA;
    border-radius: 10px;
    font-size: 17px;
    font-weight: 500;
    padding: 15px 0px;
}
.tarif.default button:hover{
    box-shadow: 0 0 10px #f8f9fa30;
}

.tarif.default button {
    border: 1px solid #f8f9fa30;
    background-color: #202123;
}

.tarif.premium button {
    border: 1px solid #EDB20030;
    box-shadow: 0 0 7px #EDB200;
    background-color: #EDB20035;
}
.tarif.premium button:hover{
    box-shadow: 0 0 15px #EDB200;
}
footer {
    border-top: 2px solid #EDB20050;
    box-shadow: 0px -2px 4px #EDB20040;
    padding: 30px;
    background-color: #101112;
}

.svet {
    position: absolute;
    z-index: -1;
}

#svet1 {
    transform: rotate(90deg);
    margin-left: -110px;

    bottom: 0;
    left: 0;
}

#svet2 {
    transform: rotate(270deg);
    margin-right: -103px;
    top: 0;
    right: 0;
}



.slide-fade-enter-active {
  transition: all 0.3s ease-out;
}

.slide-fade-leave-active {
  transition: all 0.3s ease-in;
}

.slide-fade-enter-from,
.slide-fade-leave-to {
  opacity: 0;
}

</style>