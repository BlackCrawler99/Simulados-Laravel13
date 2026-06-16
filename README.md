# Simulado ENEM

## Autor

Vinicius Querino

## Visão Geral

Simulado ENEM é um sistema Laravel para gestão de simulados educacionais, captação de leads e análise de desempenho. Ele permite que alunos se cadastrem, recebam simulados com questões por área de conhecimento, finalizem provas, consultem resultados e baixem relatórios em PDF. Administradores podem cadastrar questões, gerenciar cursos, monitorar candidatos, exportar leads e configurar regras do sistema.

## Funcionalidades Principais

- Registro de usuários com dados pessoais, curso desejado e informações de contato.
- Login, verificação de e-mail e gerenciamento de perfil.
- Simulado com seleção automática de questões por área e correção automática.
- Visualização de simulado em andamento e histórico de resultados.
- Geração de PDF de resultado de simulado.
- Painel administrativo para gestão de perguntas, cursos, usuários e configurações.
- Exportação de leads/candidatos para Excel usando `maatwebsite/excel`.
- Importação de questões via modelo de Excel e geração de template de importação.
- Configurações dinâmicas de tema, regras do simulado, WhatsApp e prêmios.
- Módulo premium de escolas e turmas com relatórios de desempenho por turma.
- Middleware de segurança para admin, super admin e módulos habilitados.

## Tecnologias

- PHP 8.3+
- Laravel Framework ^13.7
- MySQL / SQLite / PostgreSQL (qualquer banco suportado pelo Laravel)
- Tailwind CSS
- Vite
- Alpine.js
- Axios
- Laravel Breeze (autenticação)
- barryvdh/laravel-dompdf
- maatwebsite/excel

## Estrutura do Sistema

### Principais Modelos

- `User`
  - Relacionamentos: `exams`, `school`, `schoolClass`
  - Campos: `name`, `email`, `password`, `phone`, `city`, `uf`, `desired_course`, `school_year`, `interested_course`, `school_id`, `school_class_id`
- `Exam`
  - Relacionamentos: `user`, `answers`
  - Campos: `total_questions`, `score`, `completed_at`
- `Question`
  - Relacionamentos: `options`
  - Campos: `statement`, `image`, `area`
- `Option`
  - Relacionamento: `question`
  - Campos: `text`, `is_correct`
- `Setting`
  - Campos: `key`, `value`

### Principais Rotas

- `/` — Página inicial com tema configurável.
- `/dashboard` — Painel do usuário logado.
- `/simulado/iniciar` — Inicia simulados para alunos.
- `/simulado/{exam}` — Exibe simulado em andamento.
- `/simulado/{exam}/finalizar` — Envia respostas e finaliza o simulado.
- `/simulado/{exam}/pdf` — Baixa resultado em PDF.
- `/simulado/{exam}/resultado` — Visualiza resultado da prova.
- `/api/escolas/{school}/turmas` — API para carregar turmas na inscrição.
- `/admin` — Dashboard administrativo.
- `/admin/candidatos` — Lista de leads e candidatos.
- `/admin/dashboard/exportar-leads` — Exporta relatórios em Excel.
- `/admin/questoes` — CRUD de questões.
- `/admin/questoes/importar` — Importa questões via Excel.
- `/admin/questoes/modelo` — Download do modelo de importação.
- `/admin/cursos` — Gestão de cursos.
- `/admin/usuarios` — Gestão de usuários e permissões admin.
- `/admin/configuracoes` — Configurações do sistema.
- `/admin/modulos` — Módulo de desenvolvimento disponível para `super_admin`.
- `/admin/colegios` — Módulo premium de escolas e turmas.

## Como Executar Localmente

1. Copie o arquivo de ambiente:

```bash
cp .env.example .env
```

2. Instale dependências PHP:

```bash
composer install
```

3. Gere a chave da aplicação:

```bash
php artisan key:generate
```

4. Configure o banco de dados em `.env`.

5. Execute as migrations:

```bash
php artisan migrate
```

6. Instale dependências Node:

```bash
npm install
```

7. Compile os assets:

```bash
npm run build
```

8. Inicie o servidor de desenvolvimento:

```bash
npm run dev
```

## Comandos Úteis

- `composer install` — instala dependências PHP.
- `npm install` — instala dependências JS.
- `npm run dev` — executa Vite em modo desenvolvimento.
- `npm run build` — compila os ativos para produção.
- `php artisan migrate` — executa migrations.
- `php artisan test` — roda testes automatizados.
- `php artisan tinker` — abre REPL do Laravel.

## Módulos e Configurações

- `is_admin` — identifica administradores.
- `super_admin` — acesso especial ao módulo de desenvolvimento.
- `module.enabled` — controla o acesso aos módulos premium de escolas.
- `Setting::updateOrCreate` — configura temas, número de questões do simulado, WhatsApp e prêmios.

## Importação e Exportação

- Exporta leads para Excel com `App\\Exports\\LeadsExport`.
- Exporta modelo de importação de questões com `App\\Exports\\QuestionTemplateExport`.
- Importa questões via Excel em `App\\Imports\\QuestionImport`.

## Observações

- O sistema atual limita cada aluno a uma tentativa de simulado.
- A seleção de questões é baseada na configuração de número de perguntas e tentativas de balanceamento por área de conhecimento.
- O tema da home page é configurável no painel administrativo e carregado dinamicamente.

## Estrutura do Banco de Dados

- `users`: armazena cadastro de candidatos e administradores. Campos principais: `id`, `name`, `email`, `password`, `phone`, `city`, `uf`, `desired_course`, `school_year`, `interested_course`, `school_id`, `school_class_id`, `is_admin`.
- `exams`: registra simulados aplicados. Campos principais: `id`, `user_id`, `total_questions`, `score`, `completed_at`, `created_at`.
- `questions`: banco de questões. Campos principais: `id`, `area`, `statement`, `image`, `created_at`.
- `options`: alternativas das questões. Campos principais: `id`, `question_id`, `text`, `is_correct`.
- `answers`: respostas dos alunos vinculadas ao `exam` e à `question` (contém `option_id` e `is_correct`).
- `courses`: cursos disponíveis para seleção no registro.
- `schools` e `school_classes`: tabelas do módulo premium para gestão de colégios e turmas.
- `settings`: pares `key`/`value` usados para configurar tema, número de questões, regras de promoção e integrações (WhatsApp, prêmios etc.).

Esta estrutura serve como referência; nomes de colunas e tabelas podem ser adaptados conforme migrações personalizadas.

## Fluxo de Usuário

1. Usuário acessa a página inicial e se registra (opcionalmente selecionando escola/turma quando o módulo premium está ativo).
2. Após verificação de e-mail e login, usuário acessa o `dashboard` com seus simulados.
3. Usuário inicia o simulado (`/simulado/iniciar`). O sistema monta uma prova balanceada por área com o número de questões configurado.
4. O aluno responde as questões e envia o formulário para `/simulado/{exam}/finalizar`.
5. O backend corrige automaticamente, atualiza `exams.score` e `completed_at`, e redireciona ao `dashboard` com a nota.
6. O aluno pode visualizar detalhes em `/simulado/{exam}/resultado` e baixar o PDF em `/simulado/{exam}/pdf` (apenas se o simulado estiver finalizado).

Fluxo administrativo:

- Administrador acessa `/admin` para visualizar métricas, exportar leads e gerenciar recursos.
- CRUD de questões em `/admin/questoes` e importação via `/admin/questoes/importar` usando o template disponível em `/admin/questoes/modelo`.
- Exportação de leads em Excel via `/admin/dashboard/exportar-leads`.
- Gestão de temas e regras em `/admin/configuracoes` (número de questões, mensagens de WhatsApp, prêmios, etc.).

---

Made with Laravel by Vinicius Querino.
