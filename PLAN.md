# Plano de Desenvolvimento

Este plano descreve os passos para a criação da aplicação "Painel Shopping", dividido em fases lógicas.

## Fase 1: Estrutura, Configuração e Banco de Dados [Concluído]

1.  **Criar a Estrutura de Diretórios:** [Concluído]
2.  **Criar o Arquivo de Configuração (`config/config.php`):** [Concluído]
3.  **Definir o Schema do Banco de Dados (`database.sql`):** [Concluído]
    *   O script SQL foi atualizado para incluir a tabela `settings`.

    ```sql
    CREATE TABLE `banners` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `nome` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
      `url_link` varchar(2048) COLLATE utf8_unicode_ci NOT NULL,
      `url_imagem_banner` varchar(2048) COLLATE utf8_unicode_ci NOT NULL,
      `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
      `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
      PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

    CREATE TABLE `empresas` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `nome` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
      `url_site` varchar(2048) COLLATE utf8_unicode_ci NOT NULL,
      `url_logo` varchar(2048) COLLATE utf8_unicode_ci NOT NULL,
      `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
      `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
      PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

    CREATE TABLE `settings` (
      `setting_key` varchar(255) NOT NULL,
      `setting_value` text,
      PRIMARY KEY (`setting_key`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

    CREATE TABLE `users` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `nome` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
      `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
      `senha` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
      `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
      `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
      PRIMARY KEY (`id`),
      UNIQUE KEY `email` (`email`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
    ```

## Fase 2: Layout Base e Área Pública [Concluído]
## Fase 3: Área Administrativa - Autenticação [Concluído]
## Fase 4: Área Administrativa - CRUDs [Concluído]
## Fase 5: Finalização e Testes [Concluído]
## Fase 6: Melhorias e Otimizações [Concluído]

## Fase 7: Novas Funcionalidades e Refatoração [Concluído]

Esta fase incluiu melhorias significativas na interface, novas funcionalidades e refatorações de código.

### 1. Funcionalidade de Configuração do Tema

*   **Ação:** Criação de uma nova seção "Configurações" no painel administrativo para permitir a alteração da logo e das cores do tema diretamente pela interface, armazenando os dados na nova tabela `settings`.
*   **Status:** [Concluído]

### 2. Melhorias na Exibição de Empresas

*   **Ação:** Adição de um sistema de paginação na listagem de empresas na página pública.
*   **Status:** [Concluído]
*   **Ação:** Adição de uma configuração para definir o número de empresas a serem exibidas por linha.
*   **Status:** [Concluído]

### 3. Refatoração da Interface e Usabilidade

*   **Ação:** Ocultar o menu de navegação administrativo para usuários não logados, mesmo na página de login.
*   **Status:** [Concluído]
*   **Ação:** Remoção do botão "Sair" duplicado da página do Dashboard.
*   **Status:** [Concluído]
*   **Ação:** Remoção do link "Dashboard" duplicado do menu principal, mantendo apenas "Painel".
*   **Status:** [Concluído]
*   **Ação:** Limpeza da interface da página inicial (remoção do título "Nossas Lojas" e simplificação do "Hero").
*   **Status:** [Concluído]

### 4. Manutenção

*   **Ação:** Atualização do arquivo `database.sql` para refletir a estrutura mais recente do banco de dados.
*   **Status:** [Concluído]

## Fase 8: Funcionalidade de Busca [Concluído]

Esta fase adiciona um campo de busca na página inicial para filtrar empresas dinamicamente.

### 1. Busca de Empresas

*   **Etapa 1.1: Adicionar Lógica de Busca no Model**
    *   **Ação:** Criar um novo método `searchByName(string $name)` em `src/models/Company.php` que use `prepared statements` para consultar empresas com `LIKE`.
    *   **Status:** [Concluído]

*   **Etapa 1.2: Inserir o Formulário de Busca no Header**
    *   **Ação:** Adicionar um formulário de busca com método `GET` em `templates/header.php`.
    *   **Status:** [Concluído]

*   **Etapa 1.3: Integrar a Busca na Página Inicial**
    *   **Ação:** Em `public/index.php`, verificar a existência de `$_GET['search']` e chamar o método de busca apropriado (`searchByName` ou `findAll`).
    *   **Status:** [Concluído]