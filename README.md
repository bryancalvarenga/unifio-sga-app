# 🏀 Sistema de Agendamento da Quadra Poliesportiva — UNIFIO

Este projeto tem como objetivo desenvolver um **sistema web em PHP** para o agendamento da quadra poliesportiva do **Centro Universitário UNIFIO**.  
O sistema permite que **alunos, professores, atléticas e comunidade externa** façam reservas de eventos esportivos e não esportivos, com controle de aprovação pela coordenação.

---

## 🚀 Tecnologias Utilizadas
- **PHP 8**
- **MySQL**
- **Composer** (gerenciador de dependências)
- **Dotenv** (configurações seguras via `.env`)
- **PHPMailer** (envio de notificações por e-mail)
- **Dompdf** (geração de relatórios em PDF)
- **Respect/Validation** (validações)

---

## 📂 Estrutura do Projeto
```
/public        -> arquivos públicos (index.php, CSS, JS, imagens)
/app
  /Controllers -> lógica das telas
  /Models      -> conexão com o banco
  /Views       -> páginas (HTML + PHP)
/Core          -> Router e Database
/Config        -> configs globais e rotas
/database      -> migrations, seeds e dumps
/storage
  /logs        -> logs de execução
  /reports     -> relatórios gerados
/docs          -> documentação do projeto (logs diários, decisões)
/vendor        -> dependências instaladas pelo Composer
```

---

## ⚙️ Configuração do Ambiente

1. **Clone o repositório**
   ```bash
   git clone git@github.com:SEU_USUARIO/unifio-sga-app.git
   cd unifio-sga-app
   ```

2. **Instale as dependências**
   ```bash
   composer install
   ```

3. **Crie o arquivo `.env`**
   Copie o exemplo abaixo e ajuste conforme seu ambiente:
   ```env
   APP_ENV=local
   APP_DEBUG=true
   BASE_URL=http://localhost:8000

   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_NAME=unifio_sga
   DB_USER=root
   DB_PASS=

   SMTP_HOST=smtp.seu-provedor.com
   SMTP_USER=naoresponda@unifio.edu.br
   SMTP_PASS=sua_senha
   SMTP_PORT=587
   ```

4. **Crie o banco de dados**
   ```bash
   mysql -u root -p -e "CREATE DATABASE IF NOT EXISTS unifio_sga DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
   ```

5. **Suba o servidor local**
   ```bash
   php -S localhost:8000 -t public
   ```
   Acesse: [http://localhost:8000](http://localhost:8000)

---

## 📝 Documentação de Desenvolvimento
Toda a evolução do projeto está registrada em `docs/`  
- [LOG_2025-09-13.md](docs/LOG_2025-09-13.md) → resumo do primeiro dia de trabalho

---

## ✨ Próximos Passos
- Implementar autenticação (login/cadastro)
- Criar fluxo de agendamento de eventos esportivos e não esportivos
- Adicionar aprovação da coordenação
- Implementar geração de relatórios
- Estilizar interface com base na identidade visual da UNIFIO