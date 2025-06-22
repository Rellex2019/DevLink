<template>
    <div class="privacy-container">
        <div class="privacy-content">
            <h1>Политика конфиденциальности платформы DevLink</h1>
            
            <section class="privacy-section">
                <h2>1. Общие положения</h2>
                <p>DevLink гарантирует защиту персональных данных пользователей в соответствии с Федеральным законом № 152-ФЗ «О персональных данных» и GDPR (для международных пользователей). Платформа обеспечивает безопасное хранение и обработку информации, включая:</p>
                <ul>
                    <li>Учетные данные (логины, пароли, email);</li>
                    <li>Данные профиля (имя, аватар, ссылки на соцсети);</li>
                    <li>Содержимое репозиториев и командных чатов.</li>
                </ul>
            </section>

            <section class="privacy-section">
                <h2>2. Принципы обработки данных</h2>
                <ul>
                    <li><strong>Минимизация:</strong> Собираются только необходимые для работы сервиса данные.</li>
                    <li><strong>Шифрование:</strong> Все передаваемые данные защищены протоколами TLS/SSL.</li>
                    <li><strong>Контроль доступа:</strong> Пользователи самостоятельно управляют видимостью проектов (приватные/публичные репозитории).</li>
                </ul>
            </section>

            <section class="privacy-section">
                <h2>3. Использование данных</h2>
                <p>DevLink использует информацию исключительно для:</p>
                <ul>
                    <li>Функционирования платформы (совместное редактирование кода, управление задачами);</li>
                    <li>Улучшения сервиса (анонимизированная аналитика);</li>
                    <li>Технической поддержки (ответы на запросы пользователей).</li>
                </ul>
            </section>

            <section class="privacy-section">
                <h2>4. Передача третьим лицам</h2>
                <p>Данные не передаются сторонним компаниям, кроме случаев:</p>
                <ul>
                    <li>Требований законодательства (по запросу уполномоченных органов);</li>
                    <li>Интеграций с одобренными сервисами (например, GitHub API — только с согласия пользователя).</li>
                </ul>
            </section>

            <section class="privacy-section">
                <h2>5. Права пользователей</h2>
                <p>Вы можете:</p>
                <ul>
                    <li>Запросить удаление аккаунта и всех связанных данных;</li>
                    <li>Отозвать согласие на обработку персональных данных;</li>
                    <li>Получить информацию о хранящейся о вас информации.</li>
                </ul>
            </section>

            <section class="privacy-section">
                <h2>6. Cookies и аналитика</h2>
                <p>DevLink использует:</p>
                <ul>
                    <li>Необходимые cookies для авторизации и работы редактора кода;</li>
                    <li>Анонимные метрики (Google Analytics) для анализа посещаемости.</li>
                </ul>
            </section>

            <section class="privacy-section">
                <h2>7. Контакты</h2>
                <p>По вопросам конфиденциальности обращайтесь:</p>
                <ul>
                    <li>Email: <a href="mailto:privacy@devlink.ru">privacy@devlink.ru</a></li>
                    <li>Юридический адрес: РФ, Астрахань, ул. Татищева, 20.</li>
                </ul>
                <p class="update-date">Дата последнего обновления: 10.06.2025</p>
                <p class="consent">При использовании DevLink вы подтверждаете согласие с данной политикой.</p>
            </section>
        </div>
    </div>
</template>

<script>
export default {
    name: 'PrivacyPage',
    metaInfo() {
        return {
            title: 'Политика конфиденциальности DevLink'
        }
    }
}
</script>

<style scoped>
.privacy-container {
    background-color: #101112;
    display: flex;
    justify-content: center;
    width: 100vw;
    height: fit-content;
    /* min-height: calc(100vh - 81px); */
    font-family: 'Montserrat';
    padding: 50px 0;
    color: #F8F9FA;
}

.privacy-content {
    flex: 1;
    width: 70vw;
    max-width: 1100px;
    height: fit-content;
    background-color: #1A1D21;
    border-radius: 10px;
    padding: 40px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
}

h1 {
    color: #EDB200;
    font-size: 28px;
    margin-bottom: 30px;
    text-align: center;
    font-weight: 600;
}

h2 {
    color: #EDB200;
    font-size: 22px;
    margin: 25px 0 15px;
    font-weight: 500;
}

.privacy-section {
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

a {
    color: #EDB200;
    text-decoration: none;
    transition: color 0.3s;
}

a:hover {
    color: #FFD700;
    text-decoration: underline;
}

.update-date {
    font-style: italic;
    color: #656A6F;
    margin-top: 20px;
}

.consent {
    font-weight: 500;
    margin-top: 30px;
    padding-top: 20px;
    border-top: 1px solid #343A40;
}
</style>