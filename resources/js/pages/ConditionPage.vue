<template>
    <div class="terms-container">
        <div class="terms-content">
            <h1>Условия использования платформы DevLink</h1>
            <p class="last-updated">Последнее обновление: 10.06.2025</p>

            <section class="terms-section">
                <h2>1. Общие положения</h2>
                <p>DevLink - это платформа для совместного кодирования, предоставляющая инструменты для командной
                    разработки программного обеспечения. Используя DevLink, вы соглашаетесь с настоящими Условиями.</p>
            </section>

            <section class="terms-section">
                <h2>2. Регистрация и учетная запись</h2>
                <ul>
                    <li>Для доступа к функционалу требуется регистрация с указанием действительного email</li>
                    <li>Вы несете ответственность за сохранность своих учетных данных</li>
                    <li>Запрещено создавать несколько аккаунтов для одного пользователя</li>
                    <li>Минимальный возраст пользователей - 13 лет</li>
                </ul>
            </section>

            <section class="terms-section">
                <h2>3. Репозитории и код</h2>
                <ul>
                    <li>Вы можете создавать публичные и приватные репозитории</li>
                    <li>Вы сохраняете все права на размещенный код</li>
                    <li>Запрещено размещать:
                        <ul>
                            <li>Вредоносный код</li>
                            <li>Материалы, нарушающие авторские права</li>
                            <li>Контент незаконного характера</li>
                        </ul>
                    </li>
                    <li>DevLink оставляет за собой право удалять репозитории, нарушающие эти правила</li>
                </ul>
            </section>

            <section class="terms-section">
                <h2>4. Командная работа</h2>
                <ul>
                    <li>Вы можете создавать команды и приглашать участников</li>
                    <li>Владелец команды определяет уровень доступа участников</li>
                    <li>Приглашая пользователей в команду, вы подтверждаете, что имеете на это право</li>
                    <li>DevLink не несет ответственности за конфликты внутри команд</li>
                </ul>
            </section>

            <section class="terms-section">
                <h2>5. Система задач</h2>
                <ul>
                    <li>Вы можете создавать статусы и задачи внутри репозиториев</li>
                    <li>Ответственность за распределение задач лежит на участниках команды</li>
                    <li>Запрещено использовать систему задач для:
                        <ul>
                            <li>Рассылки спама</li>
                            <li>Размещения рекламы</li>
                            <li>Оскорбительных высказываний</li>
                        </ul>
                    </li>
                </ul>
            </section>

            <section class="terms-section">
                <h2>6. Профиль пользователя</h2>
                <ul>
                    <li>Вы можете редактировать свой профиль: добавлять фото, ссылки, информацию о себе</li>
                    <li>Запрещено:
                        <ul>
                            <li>Использовать чужие фотографии</li>
                            <li>Размещать ложную информацию</li>
                            <li>Добавлять ссылки на запрещенный контент</li>
                        </ul>
                    </li>
                    <li>DevLink оставляет за собой право скрывать или удалять несоответствующий контент</li>
                </ul>
            </section>

            <section class="terms-section">
                <h2>7. Ответственность</h2>
                <ul>
                    <li>DevLink предоставляет платформу "как есть" и не гарантирует бесперебойную работу</li>
                    <li>Вы используете сервис на свой собственный риск</li>
                    <li>DevLink не несет ответственности за:
                        <ul>
                            <li>Потерю данных</li>
                            <li>Косвенные убытки</li>
                            <li>Действия других пользователей</li>
                        </ul>
                    </li>
                </ul>
            </section>

            <section class="terms-section">
                <h2>8. Изменения условий</h2>
                <p>DevLink оставляет за собой право изменять настоящие Условия. О существенных изменениях мы уведомим
                    пользователей через платформу или по email.</p>
            </section>

            <div class="acceptance">
                <p>Используя DevLink, вы подтверждаете, что прочитали, поняли и согласны с этими Условиями.</p>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'TermsOfService',
    metaInfo() {
        return {
            title: 'Условия использования DevLink'
        }
    }
}
</script>

<style scoped>
.terms-container {
    background-color: #101112;
    display: flex;
    justify-content: center;
    width: 100vw;
    /* min-height: calc(100vh - 81px); */
    font-family: 'Montserrat';
    padding: 50px 0;
    color: #F8F9FA;
}

.terms-content {
    width: 70vw;
    max-width: 1100px;
    background-color: #1A1D21;
    border-radius: 10px;
    padding: 40px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
}

h1 {
    color: #EDB200;
    font-size: 28px;
    margin-bottom: 15px;
    text-align: center;
    font-weight: 600;
}

.last-updated {
    text-align: center;
    color: #656A6F;
    margin-bottom: 30px;
    font-size: 14px;
}

h2 {
    color: #EDB200;
    font-size: 22px;
    margin: 25px 0 15px;
    font-weight: 500;
}

.terms-section {
    margin-bottom: 30px;
}

p {
    line-height: 1.6;
    margin-bottom: 15px;
    font-size: 16px;
}

ul {
    padding-left: 20px;
    margin: 15px 0;
}

li {
    margin-bottom: 8px;
    line-height: 1.5;
    font-size: 15px;
}

ul ul {
    padding-left: 20px;
    margin: 8px 0;
}

.acceptance {
    margin-top: 40px;
    padding-top: 20px;
    border-top: 1px solid #343A40;
    font-weight: 500;
    text-align: center;
}
</style>