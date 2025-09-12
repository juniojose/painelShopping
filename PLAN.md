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

## Fase 4: Área Administrativa - CRUDs [Concluído]

1.  **Criar os Models (`/src/models`):** [Concluído]
    *   `User.php`, `Company.php`, `Banner.php`.
    *   Cada classe terá métodos para criar, ler, atualizar e deletar registros (`create()`, `findById()`, `findAll()`, `update()`, `delete()`). Usar PDO e prepared statements para segurança.

2.  **Desenvolver as Views do Admin (`/src/views/admin/`):** [Concluído]
    *   Para cada CRUD (usuários, empresas, banners), criar:
        *   `index.php`: Listagem dos registros com botões de editar/excluir.
        *   `form.php`: Formulário para criar e editar registros.

3.  **Desenvolver os Controllers do Admin (`/src/controllers/`):** [Concluído]
    *   `UserController.php`, `CompanyController.php`, `BannerController.php`.
    *   Cada controller irá gerenciar a lógica do seu respectivo CRUD, recebendo os dados do formulário, chamando os métodos do Model e redirecionando para as Views corretas.

## Fase 5: Finalização e Testes [Concluído]

1.  **Revisão de Segurança:** [Concluído]
    *   Garantir que todas as entradas do usuário (formulários, URLs) estão sendo devidamente validadas e sanitizadas (ex: `htmlspecialchars`).
    *   Confirmar que todas as queries SQL usam prepared statements.

2.  **Testes de Responsividade:** [Concluído]
    *   Verificar a aparência e funcionalidade da aplicação em diferentes tamanhos de tela (desktop, tablet, mobile).

3.  **Refatoração e Limpeza:** [Concluído]
    *   Revisar o código em busca de duplicações e oportunidades de melhoria.
    *   Adicionar comentários onde a lógica for complexa.

4.  **Atualizar o `README.md`:** [Concluído]
    *   Adicionar instruções detalhadas de como instalar e configurar a aplicação em um ambiente de produção.

## Fase 6: Melhorias e Otimizações [Concluído]

Esta fase foca em melhorar a usabilidade, performance e funcionalidade da aplicação com base no feedback do usuário.

### 1. Melhoria na Navegação do Admin

*   **Etapa 1.1: Criar o Componente do Menu**
    *   **Ação:** Criar um novo arquivo (`templates/admin_nav.php`) contendo o HTML de uma barra de navegação com links para as seções principais.
    *   **Status:** [Concluído]

*   **Etapa 1.2: Integrar o Menu e Centralizar o Layout**
    *   **Ação:** Modificar o `header.php` para incluir o novo menu condicionalmente na área de admin. Centralizar a chamada do `header` e `footer` no roteador `public/admin/index.php`.
    *   **Status:** [Concluído]

*   **Etapa 1.3: Limpar Views Redundantes**
    *   **Ação:** Remover as chamadas `require_once` para `header.php` e `footer.php` de todos os arquivos de view do admin, uma vez que o layout agora é centralizado.
    *   **Status:** [Concluído]

### 2. Otimização de Performance (Lentidão no Carregamento)

*   **Etapa 2.1: Isolar o Início da Sessão**
    *   **Ação:** Remover a chamada `session_start()` do cabeçalho global (`templates/header.php`) para que a sessão não seja mais iniciada desnecessariamente em todas as páginas (como a home pública).
    *   **Status:** [Concluído]

*   **Etapa 2.2: Garantir a Sessão na Área de Admin**
    *   **Ação:** Verificar e garantir que os pontos de entrada da área administrativa (como `public/admin/index.php` e os controllers) continuam iniciando a sessão corretamente, de forma que o login e a proteção de rotas não sejam afetados.
    *   **Status:** [Concluído]

### 3. Conteúdo Dinâmico na Página Inicial

*   **Etapa 3.1: Conectar a Home ao Banco de Dados**
    *   **Ação:** Adicionar a lógica em `public/index.php` para buscar os banners e empresas do banco de dados.
    *   **Status:** [Concluído]

*   **Etapa 3.2: Dinamizar o "Hero" com um Carrossel de Banners**
    *   **Ação:** Substituir o HTML estático por um Carrossel do Bootstrap que exibe os banners cadastrados.
    *   **Status:** [Concluído]

*   **Etapa 3.3: Dinamizar o Grid de Empresas**
    *   **Ação:** Substituir os cards estáticos por um loop que renderiza dinamicamente as empresas cadastradas.
    *   **Status:** [Concluído]

### 4. Link no Rodapé

*   **Etapa 4.1: Transformar o Texto em Link**
    *   **Ação:** No `templates/footer.php`, transformar o texto "kmkz.ai.br" em um link clicável que abre em uma nova aba.
    *   **Status:** [Concluído]