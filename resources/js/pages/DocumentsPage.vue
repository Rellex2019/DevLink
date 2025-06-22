<template>
    <div class="documents-container">
      <div class="documents-content">
        <h1>Документация DevLink</h1>
        <p class="subtitle">Официальные документы и руководства платформы</p>
  
        <div class="documents-grid">
          <section class="document-card" @click="downloadDocument('terms')">
            <div class="document-icon">
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                <polyline points="14 2 14 8 20 8"></polyline>
                <line x1="16" y1="13" x2="8" y2="13"></line>
                <line x1="16" y1="17" x2="8" y2="17"></line>
                <polyline points="10 9 9 9 8 9"></polyline>
              </svg>
            </div>
            <h3>Условия использования</h3>
            <p>Правила и условия работы с платформой DevLink</p>
            <div class="document-meta">
              <span>PDF, 256 КБ</span>
              <span>Обновлено: 10.06.2025</span>
            </div>
          </section>
  
          <section class="document-card" @click="$router.push({name: 'privacy'})">
            <div class="document-icon">
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
              </svg>
            </div>
            <h3>Политика конфиденциальности</h3>
            <p>Как мы собираем, используем и защищаем ваши данные</p>
            <div class="document-meta">
              <span>PDF, 312 КБ</span>
              <span>Обновлено: 10.06.2025</span>
            </div>
          </section>
  
         
  
          <section class="document-card" @click="downloadDocument('guide')">
            <div class="document-icon">
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path>
                <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path>
              </svg>
            </div>
            <h3>Руководство пользователя</h3>
            <p>Полное руководство по работе с платформой</p>
            <div class="document-meta">
              <span>PDF, 2.4 МБ</span>
              <span>Обновлено: 01.06.2025</span>
            </div>
          </section>
  

  

        </div>
  
        <section class="additional-section">
          <h2>Дополнительные ресурсы</h2>
          <div class="resources-list">
            <div class="resource-item" @click="openLink('https://github.com/Rellex2019/DevLink')">
              <div class="resource-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                  <path d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z"/>
                </svg>
              </div>
              <div class="resource-info">
                <h3>GitHub репозиторий</h3>
                <p>Открытый код и issue tracker</p>
              </div>
            </div>
          </div>
        </section>
      </div>
    </div>
  </template>
  
  <script>
  export default {
    name: 'DocumentsPage',
    methods: {
      downloadDocument(type) {
        // Здесь будет логика загрузки документа
        let docName = '';
        switch(type) {
          case 'terms': docName = 'Условия использования DevLink'; break;
          case 'privacy': docName = 'Политика конфиденциальности DevLink'; break;
          case 'api': docName = 'API документация DevLink'; break;
          case 'guide': docName = 'Руководство пользователя DevLink'; break;
          case 'security': docName = 'Политика безопасности DevLink'; break;
          case 'faq': docName = 'FAQ DevLink'; break;
        }
        alert(`Начата загрузка документа: ${docName}`);
        window.open(`/condition.docx`, '_blank');
      },
      openLink(url) {
        window.open(url, '_blank');
      }
    },
    metaInfo() {
      return {
        title: 'Документы DevLink'
      }
    }
  }
  </script>
  
  <style scoped>
  .documents-container {
    background-color: #101112;
    display: flex;
    justify-content: center;
    width: 100vw;
    height: 100%;
    /* min-height: calc(100vh - 81px); */
    font-family: 'Montserrat';
    padding: 50px 0;
    color: #F8F9FA;
  }
  
  .documents-content {
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
  
  .documents-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 30px;
    margin-bottom: 50px;
  }
  
  .document-card {
    background-color: #25292D;
    border-radius: 10px;
    padding: 25px;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    cursor: pointer;
    border-left: 4px solid #EDB200;
  }
  
  .document-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
  }
  
  .document-icon {
    width: 50px;
    height: 50px;
    background-color: #EDB20020;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 20px;
  }
  
  .document-icon svg {
    color: #EDB200;
    width: 24px;
    height: 24px;
  }
  
  .document-card h3 {
    color: #EDB200;
    font-size: 18px;
    margin-bottom: 15px;
  }
  
  .document-card p {
    color: #656A6F;
    font-size: 14px;
    margin-bottom: 20px;
    min-height: 40px;
  }
  
  .document-meta {
    display: flex;
    justify-content: space-between;
    color: #656A6F;
    font-size: 12px;
    border-top: 1px solid #343A40;
    padding-top: 15px;
  }
  
  .additional-section {
    margin-top: 50px;
  }
  
  .additional-section h2 {
    color: #EDB200;
    font-size: 22px;
    margin-bottom: 20px;
    text-align: center;
  }
  
  .resources-list {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 20px;
  }
  
  .resource-item {
    background-color: #25292D;
    border-radius: 10px;
    padding: 20px;
    display: flex;
    align-items: center;
    gap: 15px;
    transition: transform 0.3s ease;
    cursor: pointer;
  }
  
  .resource-item:hover {
    transform: translateY(-3px);
  }
  
  .resource-icon {
    width: 40px;
    height: 40px;
    background-color: #EDB20020;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
  }
  
  .resource-icon svg {
    color: #EDB200;
    width: 20px;
    height: 20px;
  }
  
  .resource-info h3 {
    color: #F8F9FA;
    font-size: 16px;
    margin-bottom: 5px;
  }
  
  .resource-info p {
    color: #656A6F;
    font-size: 13px;
  }
  
  @media (max-width: 768px) {
    .documents-content {
      width: 90vw;
      padding: 20px;
    }
    
    .documents-grid {
      grid-template-columns: 1fr;
    }
    
    .resources-list {
      grid-template-columns: 1fr;
    }
  }
  </style>