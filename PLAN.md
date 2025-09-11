# Plano de Desenvolvimento

Este plano descreve os passos para a criação da aplicação "Painel Shopping", dividido em fases lógicas.

## Fase 1: Estrutura, Configuração e Banco de Dados [Concluído]

1.  **Criar a Estrutura de Diretórios:** [Concluído]
    ```
    /painelShopping
    |-- /config           # Arquivos de configuração (banco de dados, tema)
    |-- /public           # Arquivos públicos (CSS, JS, Imagens, index.php)
    |-- /src              # Lógica da aplicação (classes, controllers)
    |   |-- /controllers
    |   |-- /models
    |   `-- /views
    |-- /templates        # Partes reutilizáveis do layout (header, footer)
    `-- README.md
    ```

2.  **Criar o Arquivo de Configuração (`config/config.php`):** [Concluído]
    *   Definir constantes ou um array para as configurações de tema (cores, logo) e para a conexão com o banco de dados (host, user, pass, dbname).

3.  **Definir o Schema do Banco de Dados (`database.sql`):** [Concluído]
    *   Criar o script SQL para gerar as tabelas necessárias.

    ```sql
    CREATE TABLE `users` (
      `id` INT AUTO_INCREMENT PRIMARY KEY,
      `nome` VARCHAR(255) NOT NULL,
      `email` VARCHAR(255) NOT NULL UNIQUE,
      `senha` VARCHAR(255) NOT NULL, -- Armazenará o hash da senha
      `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
      `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    );

    CREATE TABLE `empresas` (
      `id` INT AUTO_INCREMENT PRIMARY KEY,
      `nome` VARCHAR(255) NOT NULL,
      `url_site` VARCHAR(2048) NOT NULL,
      `url_logo` VARCHAR(2048) NOT NULL,
      `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
      `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    );

    CREATE TABLE `banners` (
      `id` INT AUTO_INCREMENT PRIMARY KEY,
      `nome` VARCHAR(255) NOT NULL,
      `url_link` VARCHAR(2048) NOT NULL,
      `url_imagem_banner` VARCHAR(2048) NOT NULL,
      `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
      `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    );
    ```

## Fase 2: Layout Base e Área Pública [Concluído]

1.  **Integrar o Bootstrap:** [Concluído]
    *   Adicionar o CDN do Bootstrap no template principal.

2.  **Criar os Templates Base (`/templates`):** [Concluído]
    *   `header.php`: Conterá a estrutura do header, lendo as configurações do `config.php`.
    *   `footer.php`: Conterá a estrutura do footer, com o link para o painel admin.

3.  **Desenvolver a Página Principal (`/public/index.php`):** [Concluído]
    *   Incluir `header.php` e `footer.php`.
    *   Buscar os banners e empresas do banco de dados.
    *   Renderizar o banner "Hero".
    *   Renderizar os cards das empresas em um grid.
    *   Implementar a lógica JavaScript para que o clique nos links abra o conteúdo em um `iframe`.

## Fase 3: Área Administrativa - Autenticação [Concluído]

1.  **Criar a Página de Login (`/src/views/admin/login.php`):** [Concluído]
    *   Formulário com campos para e-mail e senha.

2.  **Desenvolver o Controller de Autenticação (`/src/controllers/AuthController.php`):** [Concluído]
    *   Lógica para processar o login: verificar o usuário e senha (hash) no banco.
    *   Gerenciar a sessão do PHP (`session_start()`, `$_SESSION`).
    *   Criar a função de logout.

3.  **Proteger as Rotas Administrativas:** [Concluído]
    *   Criar um script ou função que verifique se o usuário está logado antes de carregar qualquer página da área de admin.

## Fase 4: Área Administrativa - CRUDs [Em Andamento]

**Status: CRUDs para `Empresas` e `Banners` concluídos. Próximo passo é o CRUD de `Usuários`.**

1.  **Criar os Models (`/src/models`):**
    *   `User.php`, `Company.php`, `Banner.php`.
    *   Cada classe terá métodos para criar, ler, atualizar e deletar registros (`create()`, `findById()`, `findAll()`, `update()`, `delete()`). Usar PDO e prepared statements para segurança.

2.  **Desenvolver as Views do Admin (`/src/views/admin/`):**
    *   Para cada CRUD (usuários, empresas, banners), criar:
        *   `index.php`: Listagem dos registros com botões de editar/excluir.
        *   `form.php`: Formulário para criar e editar registros.

3.  **Desenvolver os Controllers do Admin (`/src/controllers/`):**
    *   `UserController.php`, `CompanyController.php`, `BannerController.php`.
    *   Cada controller irá gerenciar a lógica do seu respectivo CRUD, recebendo os dados do formulário, chamando os métodos do Model e redirecionando para as Views corretas.

## Fase 5: Finalização e Testes

1.  **Revisão de Segurança:**
    *   Garantir que todas as entradas do usuário (formulários, URLs) estão sendo devidamente validadas e sanitizadas (ex: `htmlspecialchars`).
    *   Confirmar que todas as queries SQL usam prepared statements.

2.  **Testes de Responsividade:**
    *   Verificar a aparência e funcionalidade da aplicação em diferentes tamanhos de tela (desktop, tablet, mobile).

3.  **Refatoração e Limpeza:**
    *   Revisar o código em busca de duplicações e oportunidades de melhoria.
    *   Adicionar comentários onde a lógica for complexa.

4.  **Atualizar o `README.md`:**
    *   Adicionar instruções detalhadas de como instalar e configurar a aplicação em um ambiente de produção.
