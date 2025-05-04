<template>
    <div class="container-content">
        <div class="content">

            <div class="container-teams" v-show="activeTab === 'teams'">
                <div class="title-block">Команды</div>
                <div v-for="team in 8">
                    <div class="container-team">
                        <div style="display: flex; align-items: center; gap: 0.75vw;">
                            <img class="team-logo" src="@/images/Avatar.png" alt="">
                            <div class="team-name">Plague Team</div>
                        </div>
                        <img class="team-arrow" src="@/svg/arrow-folder.svg">
                    </div>
                </div>
            </div>

            <div class="container-tasks" v-show="activeTab === 'tasks'">
                <div class="tasks-btn">
                    <div class="title-block">Задачи</div>
                    <button class="btn-add-task">Добавить</button>
                </div>


                <div v-for="task in 6">
                    <div class="container-team">
                        <div class="user-task">
                            <div style="display: flex; justify-content: space-between;">
                                <div style="display: flex; align-items: center; gap: 0.75vw;">
                                    <img class="user-logo" src="@/images/Avatar.png" alt="">
                                    <div class="user-name">Rellex</div>
                                </div>

                            </div>
                            <div>
                                <div style="display: flex; align-items: center; gap: 0.75vw;">
                                    <div class="task-status red"></div>
                                    <div class="task-file">AdminController.php</div>
                                </div>
                            </div>

                        </div>
                        <img class="team-arrow" src="@/svg/arrow-folder.svg">
                    </div>


                </div>

            </div>
        </div>
        <div class="btns">
            <div class="btn" :class="{ 'active-btn': activeTab === 'teams' }" @click="activeTab = 'teams'">Команды</div>

            <div class="btn" :class="{ 'active-btn': activeTab === 'tasks' }" @click="activeTab = 'tasks'">Задачи</div>
        </div>
    </div>
</template>
<script>
export default {
    name: 'TeamPanel',
    data() {
        return {
            activeTab: 'teams'
        }
    }
}
</script>
<style scoped>
.container-content {
    font-family: 'Montserrat';
    width: 100%;
    background-color: #161718;
    border-radius: 0.8vw 0.8vw 0px 0px;
}

.btns {
    display: flex;
    width: 100%;
}

.btn {
    transition: 0.2s;
    font-size: 0.95vw;
    cursor: pointer;
    border: 1px solid #656A6F;
    background-color: #202123;
    flex: 1;
    padding: 0.7vw 0;
    overflow: hidden;
    display: flex;
    justify-content: center;
}

.btn:hover {
    background-color: #161718;
}

.active-btn {
    border: 1px solid #EDB200;
}

.container-teams {
    max-height: 30vw;
    width: 100%;
    overflow: auto;
    padding-left: 0.2vw;
    padding-right: 1vw;
}

.container-tasks {
    position: relative;
    max-height: 30vw;
    width: 100%;
    overflow: auto;
    padding-left: 0.2vw;
    padding-right: 1vw;
}

.container-teams::-webkit-scrollbar,
.container-tasks::-webkit-scrollbar {
    width: 5px;
}

.container-teams::-webkit-scrollbar-track,
.container-tasks::-webkit-scrollbar-track {
    background: #2D2F31;
    border-radius: 3px;
}

.container-teams::-webkit-scrollbar-thumb,
.container-tasks::-webkit-scrollbar-thumb {
    background-color: #EDB200;
    border-radius: 3px;
}

.container-teams::-webkit-scrollbar-thumb:hover,
.container-tasks::-webkit-scrollbar-thumb:hover {
    background-color: #FFC600;
}





.content {
    padding: 0px 1.2vw;
}

.title-block {
    padding-top: 1.56vw;
    padding-bottom: 1.2vw;
    font-size: 1.2vw;
}



.container-team {
    transition: 0.2s;
    cursor: pointer;
    width: 100%;
    border-radius: 0.3vw;
    padding: 1vw 0.7vw;
    box-sizing: border-box;
    box-shadow: 0px 0px 0.25vw 0px #4B4F53;
    margin-bottom: 1.25vw;
    display: flex;
    justify-content: space-between;
    align-items: center;


}

.container-team:hover {
    box-shadow: 0px 0px 0.4vw 0px #656A6F;
}

.team-logo {
    width: 2.1vw;
    height: 2.1vw;
    border-radius: 20px;
}

.team-name {
    display: flex;
    align-items: center;
    font-size: 0.95vw;
}

.team-arrow {
    width: 0.5vw;
    height: 1vw;
}






.btn-add-task {
    cursor: pointer;
    font-family: 'Montserrat';
    margin-top: 0.65vw;
    font-size: 0.85vw;
    padding: 0.33vw 0.6vw;
    background: none;
    border: 1px solid #EDB200;
    border-radius: 0.3vw;
    color: #F8F9FA;
}

.btn-add-task:hover {
    background-color: #202123;
}

.tasks-btn {
    display: flex;
    justify-content: space-between;
    align-items: center;

}

.user-logo {
    width: 1vw;
    height: 1vw;
    border-radius: 0.5vw;
}

.user-name {
    font-size: 0.9vw;
}

.user-task {
    display: flex;
    flex-direction: column;
    width: 100%;
    gap: 0.3vw;
}

.task-status {
    width: 1vw;
    height: 1vw;
    border-radius: 0.5vw;
}

.task-file {
    color: #EDB200;
    font-family: 'Fira Code';
}

.red {
    background-color: red;
}
</style>