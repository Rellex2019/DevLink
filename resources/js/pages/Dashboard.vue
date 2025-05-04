<template>
    <div class="container-repository-block">
        <div class="repository-block">


            <div class="container-files-block">
                <div class="outer-padding-inblock">
                    <div class="files-block">
                        <div class="title-block">
                            <div style="width: 70%; overflow: hidden; text-overflow: ellipsis;">Репозитории</div>
                            <button class="btn">Создать</button>
                        </div>
                        <Search class="search-input" placeholderText="Найти репозиторий" />
                        <div style="display: flex; flex-direction: column; gap: 5px; margin-top: 0.8vw;">
                            <div v-for="repository in 6" class="repository">

                                <img src="@/images/Avatar.png" class="repository-img" alt="">
                                <div class="repository-path">Rellex/fillerCorp</div>

                            </div>
                            <div class="exten">Показать больше...</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="container-active-block">

            </div>


            <div class="container-team-block">
                <div class="outer-padding-inblock">
                    <div class="team-block">
                        <TeamPanel/>
                    </div>
                </div>
            </div>

        </div>
    </div>
</template>

<script>
import Search from '../components/input/Search.vue';
import TeamPanel from '../components/modal/TeamPanel.vue';

export default {
    name: 'Dashboard',
    components: {
        Search,
        TeamPanel
    }
};
</script>

<style scoped>
.search-input {
    margin-top: 1.04vw;
}

.repository {
    cursor: pointer;
    display: flex;
    gap: 10px;
}

.repository-img {
    width: 20px;
    height: 20px;
    border-radius: 10px;
}
.repository-path{
    font-size: 16px;
    overflow: hidden;
    text-overflow: ellipsis;
}
.btn{
    transition: 0.1s;
    cursor: pointer;
    background: none;
    border: 1px solid #656A6F;
    color: #EDB200;
    padding: 0.20vw 0.9vw;
    border-radius: 0.3vw;
}
.btn:hover{
    background-color: #27282a;
}
.exten{
    cursor: pointer;
    margin-top: 1vw;
    color: #EDB200;
}
.exten:hover{
    text-decoration: underline;
}




* {
    color: #F8F9FA;
}
.outer-padding-inblock {
    padding: 0px 2.6vw;
}

.container-repository-block {
    width: 100%;

}

.repository-block {
    width: 100%;
    display: flex;
}

.container-files-block {
    font-family: 'Montserrat';
    width: 23.5%;
    background-color: #161718;
    box-sizing: border-box;
    border-right: 1px solid #656A6F;
}

.container-active-block {
    box-sizing: border-box;
    border-bottom: 1px solid #656A6F;
    border-right: 1px solid #656A6F;
    width: 49.5%;
    height: calc(100vh - 51px - 80px);

    background-color: #161718;
}

.container-team-block {
    width: 27%;
    box-sizing: border-box;
    background-color: #101112;
}

.team-block {
    box-sizing: border-box;
    padding-top: 3.12vw;
    height: calc(100vh - 80px);
}

.title-block {
    display: flex;
    justify-content: space-between;
    overflow: hidden;
    text-overflow: ellipsis;
    font-size: 22px;
    margin-top: 3.12vw;
}
</style>