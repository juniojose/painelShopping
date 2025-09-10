# Painel Shopping

## Descrição

Painel Shopping é uma plataforma web desenvolvida em PHP que permite a exibição de uma vitrine de empresas e banners promocionais. Os usuários podem navegar pelo conteúdo e, ao clicar em um item, o site de destino é carregado dentro de um `iframe`, mantendo a identidade visual da plataforma.

A aplicação conta com uma área administrativa protegida por login, onde é possível gerenciar os usuários, as empresas e os banners que são exibidos na área pública.

## Tecnologias Utilizadas

*   **Backend:** PHP
*   **Banco de Dados:** MySQL
*   **Frontend:** HTML, CSS, JavaScript, Bootstrap 5

## Funcionalidades Principais

*   **Área Pública:**
    *   Header e Footer customizáveis via arquivo de configuração.
    *   Banner "Hero" rotativo (se houver mais de um).
    *   Grid de cards de empresas.
    *   Visualização de conteúdo externo via `iframe` sem sair da página.

*   **Área Administrativa:**
    *   Sistema de autenticação de usuários.
    *   Gerenciamento completo (CRUD) de Usuários.
    *   Gerenciamento completo (CRUD) de Empresas.
    *   Gerenciamento completo (CRUD) de Banners.

---

## Instalação e Deploy (Instruções Futuras)

*Estas são as instruções planejadas para quando a aplicação estiver finalizada.*

1.  **Clone o Repositório:**
    ```bash
    git clone <url-do-repositorio>
    ```

2.  **Configuração do Banco de Dados:**
    *   Importe o arquivo `database.sql` para o seu banco de dados MySQL. Isso criará as tabelas necessárias.
    *   Renomeie o arquivo `config/config.example.php` para `config/config.php`.
    *   Edite `config/config.php` e preencha as informações de conexão com o seu banco de dados (host, nome de usuário, senha, nome do banco).

3.  **Configuração do Servidor Web (Apache/Nginx):**
    *   Aponte a raiz do seu servidor (DocumentRoot) para o diretório `/public` da aplicação. Isso garante que apenas os arquivos públicos sejam acessíveis diretamente pela web.
    *   Certifique-se de que o módulo `mod_rewrite` (para Apache) esteja ativado para futuras URLs amigáveis.

4.  **Primeiro Acesso:**
    *   Acesse o link `/admin` para chegar à tela de login.
    *   Será necessário criar um primeiro usuário diretamente no banco de dados para poder acessar a área administrativa.
