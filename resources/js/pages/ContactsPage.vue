<template>
    <div class="contacts-container">
      <div class="contacts-content">
        <h1>Свяжитесь с нами</h1>
        <p class="subtitle">Мы всегда рады вашим вопросам и предложениям</p>
  
        <div class="contacts-grid">
          <section class="contact-card">
            <div class="contact-icon">
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                <polyline points="22,6 12,13 2,6"></polyline>
              </svg>
            </div>
            <h3>Электронная почта</h3>
            <p>Основные вопросы</p>
            <a href="mailto:support@devlink.ru">ms.zyathin@gmail.com</a>

            <p>Партнерства</p>
            <a href="mailto:partners@devlink.ru">ms.thekirz@gmail.com</a>
          </section>
  
          <section class="contact-card">
            <div class="contact-icon">
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
              </svg>
            </div>
            <h3>Телефон</h3>
            <p>Техническая поддержка</p>
            <a href="tel:+79969121569">8 (996) 912-15-69</a>
            <p>Коммерческие вопросы</p>
            <a href="tel:+79969121569">8 (905) 458-77-88</a>
          </section>
  
          <section class="contact-card">
            <div class="contact-icon">
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                <circle cx="12" cy="10" r="3"></circle>
              </svg>
            </div>
            <h3>Офис</h3>
            <p>Россия, Астрахань</p>
            <p>ул. Медиков, 20</p>
            <p>Пн-Пт: 9:00 - 18:00</p>
          </section>
  
          <section class="contact-card social-card">
            <div class="contact-icon">
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"></path>
              </svg>
            </div>
            <h3>Социальные сети</h3>
            <div class="social-links">
              <a href="https://vk.com/thekirz" target="_blank" rel="noopener noreferrer" class="social-link">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                  <path d="M15.073 2H8.938C3.332 2 2 3.333 2 8.927v6.136C2 20.667 3.323 22 8.927 22h6.136C20.667 22 22 20.677 22 15.073V8.938C22 3.332 20.677 2 15.073 2zm3.073 14.27h-1.459c-.552 0-.718-.447-1.708-1.437-.834-.833-1.209-.937-1.448-.937-.302 0-.385.083-.385.5v1.312c0 .354-.115.562-.834.562-1.229 0-2.625-1.5-3.583-3.583-1.354-2.687-1.77-3.26-2.062-3.26-.146 0-.291.146-.291.562v1.02c0 .406.146.562.27.562.27 0 .522-.28.833-.834.906-1.666 1.447-2.166 1.77-2.166.208 0 .354.104.354.562v2.895c0 .208 0 .25-.177.25-.323 0-.885-.27-1.792-.27-1.406 0-2.25.677-2.25 1.771 0 .906.687 1.562 1.354 1.562.26 0 .52-.083.78-.26.26-.177.687-.552 1.041-.552.104 0 .208.104.208.333v2.312c0 .374-.062.48-.312.48-.541 0-1.291-.166-2.25-.166-1.77 0-3.083.906-3.083 2.604 0 1.354 1 2.354 2.416 2.354 2.083 0 3.354-1.25 3.354-1.25s.031 1.251 1.458 1.251h2.292c1.417 0 1.917-.917 1.917-1.917v-.729c0-.667.437-1.021.687-1.021.208 0 .354.146.354.479v1.271c0 1.251-.709 2.229-2.229 2.229h-1.521c-1.229 0-1.5-.521-1.5-1.521V14.5c0-1.062.229-1.229.635-1.479.406-.25.854-.604.854-.771 0-.145-.115-.25-.25-.25-.229 0-.604.354-1.23.354-.729 0-1.25-.521-1.25-1.25 0-1.229 1.312-2.291 3.333-2.291 1.521 0 2.083.229 2.583.729.26.26.385.333.552.333.229 0 .354-.229.354-.687v-1.75c0-.521-.166-.75-.521-.75-.229 0-.489.114-.927.552-1.229 1.229-1.978 1.729-2.437 1.729-.229 0-.354-.115-.354-.437V8.855c0-.823.219-1.855 1.647-1.855h3.417c.375 0 .687.302.687.677v3.063c0 .375-.198.573-.448.573-.25 0-.427-.198-.427-.573V9.26c0-.26-.229-.479-.479-.479h-1.98c-.26 0-.479.219-.479.479v1.98c0 1.563.771 2.333 1.771 2.333.375 0 .729-.146.729.427v2.563c0 .26-.229.479-.479.479z"/>
                </svg>
                VK
              </a>
              <a href="https://t.me/thekirz" target="_blank" rel="noopener noreferrer" class="social-link">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                  <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm4.64 6.8c-.15 1.58-.8 5.42-1.13 7.19-.14.75-.42 1-.68 1.03-.58.05-1.02-.38-1.58-.75-.88-.57-1.38-.93-2.23-1.5-.99-.65-.35-1.01.22-1.59.15-.15 2.71-2.48 2.76-2.69.03-.09.06-.17.01-.24-.06-.06-.11-.01-.23.02-.12.03-2.41 1.52-3.03 1.88-.29.16-.7.3-1.07.31-1.12.03-1.31-.36-1.68-1.18-.6-1.33-1.52-4.22-1.69-5.58-.04-.32.09-.5.31-.51.2-.01.34.14.42.29.45.87 1.41 2.94 1.51 3.15.16.34.32.73.1 1.13-.03.06-.12.12-.12.12-.08 0-.17-.09-.32-.18s-1.89-1.23-2.6-1.7c-.98-.66-1.33-.79-1.59-.8-.34-.01-.65.1-.65.1s-.53.17-.01.53c1.07.73 2.09 1.44 2.3 1.51.54.18.93.16 1.26.05.41-.13 2.59-1.22 2.92-1.45.58-.4.7-.45.8-.45.09 0 .18.09.01.28z"/>
                </svg>
                Telegram
              </a>
              <a href="https://github.com/Rellex2019/DevLink.git" target="_blank" rel="noopener noreferrer" class="social-link">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                  <path d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z"/>
                </svg>
                GitHub
              </a>
            </div>
          </section>
        </div>
  
        <section class="feedback-section">
          <h2>Форма обратной связи</h2>
          <form @submit.prevent="submitForm" class="feedback-form">
            <div class="form-group">
              <label for="name">Ваше имя</label>
              <input type="text" id="name" v-model="form.name" required>
            </div>
            <div class="form-group">
              <label for="email">Email</label>
              <input type="email" id="email" v-model="form.email" required>
            </div>
            <div class="form-group">
              <label for="subject">Тема</label>
              <select id="subject" v-model="form.subject" required>
                <option value="" disabled selected>Выберите тему</option>
                <option value="support">Техническая поддержка</option>
                <option value="bug">Сообщение об ошибке</option>
                <option value="feature">Предложение по улучшению</option>
                <option value="partnership">Партнерство</option>
                <option value="other">Другое</option>
              </select>
            </div>
            <div class="form-group">
              <label for="message">Сообщение</label>
              <textarea id="message" v-model="form.message" rows="5" required></textarea>
            </div>
            <button type="submit" class="submit-btn">Отправить</button>
          </form>
        </section>
      </div>
    </div>
  </template>
  
  <script>
  export default {
    name: 'ContactsPage',
    data() {
      return {
        form: {
          name: '',
          email: '',
          subject: '',
          message: ''
        }
      }
    },
    methods: {
      submitForm() {
        this.$showAlert('Ваше обращение успешно отправлено', 'accept')
        this.form = {
          name: '',
          email: '',
          subject: '',
          message: ''
        };
      }
    },
    metaInfo() {
      return {
        title: 'Контакты DevLink'
      }
    }
  }
  </script>
  
  <style scoped>
  .contacts-container {
    background-color: #101112;
    display: flex;
    justify-content: center;
    width: 100vw;
    /* min-height: calc(100vh - 81px); */
    font-family: 'Montserrat';
    padding: 50px 0;
    color: #F8F9FA;
  }
  
  .contacts-content {
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
    margin-bottom: 10px;
    text-align: center;
    font-weight: 600;
  }
  
  .subtitle {
    text-align: center;
    color: #656A6F;
    margin-bottom: 40px;
    font-size: 16px;
  }
  
  .contacts-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 30px;
    margin-bottom: 50px;
  }
  
  .contact-card {
    background-color: #25292D;
    border-radius: 10px;
    padding: 25px;
    transition: transform 0.3s ease;
  }
  
  .contact-card:hover {
    transform: translateY(-5px);
  }
  
  .contact-icon {
    width: 50px;
    height: 50px;
    background-color: #EDB20020;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 20px;
  }
  
  .contact-icon svg {
    color: #EDB200;
    width: 24px;
    height: 24px;
  }
  
  .contact-card h3 {
    color: #EDB200;
    font-size: 18px;
    margin-bottom: 15px;
  }
  
  .contact-card p {
    color: #656A6F;
    font-size: 14px;
    margin-bottom: 5px;
  }
  
  .contact-card a {
    color: #F8F9FA;
    text-decoration: none;
    display: block;
    margin-bottom: 15px;
    transition: color 0.3s;
  }
  
  .contact-card a:hover {
    color: #EDB200;
  }
  
  .social-card {
    display: flex;
    flex-direction: column;
  }
  
  .social-links {
    display: flex;
    flex-direction: column;
    gap: 10px;
    margin-top: 10px;
  }
  
  .social-link {
    display: flex;
    align-items: center;
    gap: 10px;
    color: #F8F9FA;
    text-decoration: none;
    transition: color 0.3s;
  }
  
  .social-link:hover {
    color: #EDB200;
  }
  
  .social-link svg {
    width: 20px;
    height: 20px;
  }
  
  .feedback-section {
    margin-top: 50px;
  }
  
  .feedback-section h2 {
    color: #EDB200;
    font-size: 22px;
    margin-bottom: 20px;
    text-align: center;
  }
  
  .feedback-form {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
  }
  
  .form-group {
    margin-bottom: 15px;
  }
  
  .form-group label {
    display: block;
    margin-bottom: 8px;
    color: #F8F9FA;
    font-size: 14px;
  }
  
  .form-group input,
  .form-group select,
  .form-group textarea {
    width: 95%;
    padding: 12px 10px;
    background-color: #25292D;
    border: 1px solid #343A40;
    border-radius: 5px;
    color: #F8F9FA;
    font-family: 'Montserrat';
    font-size: 14px;
  }
  
  .form-group textarea {
    resize: vertical;
  }
  
  .form-group:last-child {
    grid-column: span 2;
  }
  
  .submit-btn {
    grid-column: span 2;
    background-color: #EDB200;
    color: #101112;
    border: none;
    padding: 12px 25px;
    border-radius: 5px;
    font-weight: 600;
    cursor: pointer;
    transition: background-color 0.3s;
    justify-self: center;
    width: 200px;
  }
  
  .submit-btn:hover {
    background-color: #FFD700;
  }
  
  @media (max-width: 768px) {
    .contacts-content {
      width: 90vw;
      padding: 20px;
    }
    
    .feedback-form {
      grid-template-columns: 1fr;
    }
    
    .form-group:last-child {
      grid-column: span 1;
    }
    
    .submit-btn {
      grid-column: span 1;
      width: 100%;
    }
  }
  </style>