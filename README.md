# Sistema de Agendamento da Quadra Poliesportiva — UNIFIO

Sistema web em PHP para gerenciamento de agendamentos esportivos e não esportivos da quadra poliesportiva do Centro Universitário UNIFIO.
Permite que alunos, professores, atléticas e comunidade registrem e acompanhem eventos, com fluxo de aprovação pela Coordenação e registro de presença de alunos.

---

## Principais funcionalidades

- Calendário interativo com navegação por mês (`?mes=YYYY-MM`), indicação de disponibilidade por dia/turno (P1/P2) e seleção de slots.
- Cadastro e edição de eventos (esportivos e não esportivos) com materiais, observações e estimativa de participantes.
- Fluxo de aprovação (Coordenação): PENDENTE → APROVADO/REJEITADO/CANCELADO/FINALIZADO.
- Presença de alunos: marcar/desmarcar presença em eventos e contagem total por evento.
- Autenticação (login/cadastro) com feedback amigável e botão para mostrar/ocultar senha.
- Notificações por e-mail (PHPMailer) e geração de relatórios em PDF (Dompdf) — base preparada.
- Datas em pt_BR e fuso America/Sao_Paulo (evita exibir “dia anterior”).
- UX consistente: Bootstrap + ícones Lucide; mensagens de flash e modais de detalhe.

---

## Perfis e permissões

- ALUNO: visualizar “Meus Eventos”, marcar/desmarcar presença, criar eventos (conforme política).
- ATLETICA: criar/editar eventos esportivos próprios; cancelar; visualizar status.
- PROFESSOR: criar/editar eventos não esportivos próprios; cancelar.
- COORDENACAO: analisar/alterar status de qualquer evento; visão consolidada.
- COMUNIDADE: cadastro simplificado (conforme política institucional).

---

## Tecnologias

- Back-end: PHP 8, MySQL, Composer, Dotenv
- Front-end: Bootstrap 5, JavaScript (ES6), ícones Lucide
- Auxiliares: PHPMailer, Dompdf, Respect/Validation
- Core: Router e Database próprios (IntlDateFormatter pt_BR)

---

## Arquitetura e padrões

- MVC light
  - `/app/Controllers` — lógica de telas
  - `/app/Models` — acesso a dados
  - `/app/Views` — templates PHP + Bootstrap/Lucide
- Core
  - `/Core/Router.php` — rotas
  - `/Core/Database.php` — conexão PDO
- Config
  - `/Config/routes.php` — definição de rotas
  - `.env` — credenciais e variáveis de ambiente
- Migrations em `/database` (SQL versionado por data)
- Assets estáticos em `/public` (index, CSS, JS, imagens)

---

## Estrutura do projeto

```
/public              -> arquivos públicos (index.php, CSS, JS, imagens)
/app
  /Controllers       -> controllers (EventController, AuthController, etc.)
  /Models            -> models (Event, User, Presence, ...)
  /Views             -> templates (PHP com Bootstrap/Lucide)
/Core                -> Router e Database
/Config              -> configs globais e rotas
/database            -> migrations .sql (execução por ordem de prefixo)
/storage
  /logs              -> logs de execução
  /reports           -> relatórios gerados (PDF/CSV)
/docs                -> documentação (CHANGELOGs, decisões técnicas)
/vendor              -> dependências do Composer
```

---

## Modelo de dados (essencial)

### Tabela `events`
- `id` (PK), `usuario_id` (FK → users.id)
- `categoria` (`ESPORTIVO|NAO_ESPORTIVO`)
- `subtipo_esportivo` (`FUTSAL|VOLEI|BASQUETE`) ou `subtipo_nao_esportivo` (`PALESTRA|WORKSHOP|FORMATURA`)
- `finalidade` (`TREINO|CAMPEONATO|OUTRO`)
- `data_evento` (DATE), `periodo` (`P1|P2`)
- `status` (`PENDENTE|APROVADO|REJEITADO|CANCELADO|FINALIZADO`)
- `materiais_necessarios` (TEXT), `estimativa_participantes` (INT), `observacoes` (TEXT), timestamps
- Unique: `(data_evento, periodo)` — trava de calendário

### Tabela `presence`
- `id` (PK), `evento_id` (FK → events.id ON CASCADE), `usuario_id` (FK → users.id ON CASCADE)
- `created_at` (TIMESTAMP)
- Unique: `(evento_id, usuario_id)` — impede presença duplicada
- Rotas relacionadas:
  - `POST /eventos/presenca` — marcar
  - `POST /eventos/presenca/remover` — desmarcar

Observação: caso o “Curso” evolua, considerar tabela `cursos` + `users.curso_id`.

---

## Rotas principais (exemplos)

- Calendário: `/eventos?mes=YYYY-MM`
- Criar evento: `/eventos/esportivo/novo` e `/eventos/nao-esportivo/novo`
- Editar evento: `/eventos/editar?id=123`
- Meus eventos: `/eventos`
- Presença (ALUNO):
  - `POST /eventos/presenca`
  - `POST /eventos/presenca/remover`
- Autenticação:
  - `GET /login`, `POST /login`
  - `GET /register`, `POST /register`
  - `GET /logout` (opcional)

---

## Configuração do ambiente

1) Clonar e instalar dependências
```bash
git clone git@github.com:bryancalvarenga/unifio-sga-app.git
cd unifio-sga-app
composer install
```

2) `.env`
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

3) Banco
```bash
mysql -u root -p -e "CREATE DATABASE IF NOT EXISTS unifio_sga DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
```

4) Migrations
Execute os arquivos `.sql` em `/database` na ordem do prefixo (ex.: `2025_09_18_create_presence.sql`):
```bash
mysql -u root -p unifio_sga < database/2025_09_18_create_presence.sql
# ... demias migrations
```

5) Servidor local
```bash
php -S localhost:8000 -t public
# Acesse: http://localhost:8000
```

---

## Front-end e UX

- Bootstrap 5 para layout responsivo.
- Lucide para ícones: `<i data-lucide="...">`.
- Script unificado para login/register: `/public/assets/js/auth.js`
  - Mostrar/ocultar senha (toggle de dois ícones com `d-none`).
  - Aviso de Caps Lock (opcional).
  - Validação “senha = confirmar” no register.
- Mensagens de flash no `layout.php` e erros amigáveis no `login.php`.

---

## Testes manuais (checklist)

- Calendário abre no mês correto e navega com `?mes=YYYY-MM`.
- Seleção de P1/P2 preenche os inputs ocultos no formulário.
- Editar evento abre o calendário no mês do próprio evento.
- Meus eventos: cards com data correta, contagem de presenças, botões alinhados.
- ALUNO: marcar/desmarcar presença com redirecionamento para `#evt-<id>`.
- Login/Register: ícones e olho de senha funcionando; erros amigáveis; register com campo Curso obrigatório (lista A–Z).

---

## Roadmap

- Tabela de cursos + `users.curso_id` (normalização).
- Modal “Ver evento”: lista de presentes; exportar CSV/Excel.
- Relatórios consolidados por período/curso/perfil.
- E-mails de confirmação/status (PHPMailer).
- Throttle de login e logs de auditoria leves.

---

## Documentação

- `docs/` mantém logs e decisões.
  - `docs/LOG_2025-09-17.md` — último log de alterações.
  - `docs/LOG_2025-09-14_2.md` — terceiro dia de trabalho.
  - `docs/LOG_2025-09-14.md` — terceiro dia de trabalho.
  - `docs/LOG_2025-09-13.md` — segundo dia de trabalho.
  - `docs/LOG_2025-09-12.md` — primeiro dia de trabalho.

---

## Contribuição

- PRs pequenos e objetivos.
- Mensagens de commit no padrão: `feat(...)`, `fix(...)`, `chore(...)`.
- Padronizar PHP 8 (tipagem), SQL versionado em `/database`.

---

## Licença

A definir pela instituição (sugestão: MIT).
