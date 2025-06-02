<template>
    <div class="settings-container">
        <div class="settings-panel">

            <div class="team-delete-container">
                <div class="danger-container">
                    <div>
                        <p class="title-delete">Переименование репозитория</p>
                        <p class="ps">Изменяет название репозитория. Это влечет за собой репенос адреса для доступа к нему.</p>
                    </div>
                    <button class="delete-team-btn" @click="deleteTeam">Переименовать</button>
                </div>
                <div class="danger-container">
                    <div>
                        <p class="title-delete">Изменение видимости</p>
                        <p class="ps">Изменяет правило приватности этого репозитория.</p>
                    </div>
                    <button class="delete-team-btn" @click="deleteTeam">Изменить видимость</button>
                </div>


                
                <div class="danger-container">
                    <div>
                        <p class="title-delete">Передача прав владельца</p>
                        <p class="ps">Передайте этот репозиторий другому пользователю.</p>
                    </div>
                    <button class="delete-team-btn" @click="deleteTeam">Передать</button>
                </div>
                <div class="danger-container">
                    <div>
                        <p class="title-delete">Удалить этот репозиторий</p>
                        <p class="ps">После удаления репозитория пути назад уже не будет. Будьте уверены.</p>
                    </div>
                    <button class="delete-team-btn" @click="deleteTeam">Удалить</button>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    name: 'RepositorySettings'
}
</script>
<style scoped>
.settings-container {
    font-family: 'Montserrat';
    flex: 1;
    color: #F8F9FA;
    display: flex;
    justify-content: center;
    background-color: #161718;
}

.settings-panel {
    width: 60%;
    max-width: 1600px;
    display: flex;
    flex-direction: column;
    gap: 5%;
    padding: 20px;
}

.team-delete-container {
    color: #F8F8F8;
    border: 1px solid #FF0E0E;
    border-radius: 5px;
    padding: 15px;
    display: flex;
    flex-direction: column;
    gap: 30px;
}
.danger-container{
    display: flex;
    justify-content: space-between;
}
.title-delete {
    font-weight: 500;
}

.ps {
    margin-top: 10px;
    font-size: 17px;
}

.delete-team-btn {
    cursor: pointer;
    white-space: nowrap;
    padding: 10px 15px;
    font-size: 16px;
    font-weight: 500;
    background: none;
    border: 1px solid #FF0E0E;
    border-radius: 5px;
    color: #FF0E0E;
    height: fit-content;
    align-self: center;
}
.delete-team-btn:hover {
    background-color: #FF0E0E20;
}
</style>