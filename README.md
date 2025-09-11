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

## Instalação e Configuração

Siga os passos abaixo para configurar o ambiente de desenvolvimento.

### Pré-requisitos

*   Servidor web local (Apache, Nginx, etc.)
*   PHP 8.0 ou superior
*   MySQL 5.7 ou superior (ou MariaDB)

### Passo 1: Clonar o Repositório

```bash
git clone https://github.com/juniojose/painelShopping.git
cd painelShopping
```

### Passo 2: Configurar o Banco de Dados

1.  Crie um novo banco de dados no seu servidor MySQL. Por exemplo, `painel_shopping`.
2.  Importe a estrutura das tabelas usando o arquivo `database.sql`.

    ```bash
    mysql -u seu_usuario -p nome_do_banco < database.sql
    ```

### Passo 3: Configurar a Aplicação

1.  Vá para o diretório `config/`.
2.  Renomeie o arquivo `config.php.example` para `config.php`.

    ```bash
    mv config/config.php.example config/config.php
    ```
3.  Abra o arquivo `config/config.php` e preencha as credenciais do seu banco de dados nas constantes `DB_USER` e `DB_PASS`.

### Passo 4: Criar o Primeiro Usuário Administrador

Para acessar a área administrativa, você precisa criar um usuário diretamente no banco de dados. Execute o seguinte comando SQL, substituindo os valores de exemplo:

```sql
INSERT INTO `users` (`nome`, `email`, `senha`) VALUES ('Administrador', 'admin@example.com', '$2y$10$3lJ.E/3Q2.E1Z.E2Y.E3X.E4U.E5V.E6W.E7X.E8Y.E9Z'); -- A senha é 'password123'
```
**Nota:** A senha no exemplo acima é 'password123'. O hash foi gerado com `password_hash('password123', PASSWORD_BCRYPT)`.

### Passo 5: Configurar o Servidor Web

Configure a raiz do seu servidor web (DocumentRoot no Apache) para apontar para o diretório `/public` da aplicação. Isso é crucial para a segurança, pois impede o acesso direto aos arquivos de lógica e configuração.

**Exemplo de configuração para Apache (httpd-vhosts.conf):**
```apache
<VirtualHost *:80>
    ServerName painel-shopping.test
    DocumentRoot "C:/caminho/para/painelShopping/public"
    <Directory "C:/caminho/para/painelShopping/public">
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

### Passo 6: Acessar a Aplicação

1.  Acesse a URL que você configurou (ex: `http://painel-shopping.test`).
2.  Para acessar a área administrativa, vá para `/admin` (ex: `http://painel-shopping.test/admin`).
3.  Use as credenciais que você inseriu no banco de dados (`admin@example.com` e `password123`) para fazer o login.
