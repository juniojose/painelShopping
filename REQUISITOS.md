# Requisitos da Aplicação

1.  **Linguagem e Banco de Dados:**
    *   Linguagem: PHP.
    *   Banco de Dados: MySQL.

2.  **Áreas da Aplicação:**
    *   **Área de Usuário (Pública):**
        *   Acesso livre, sem necessidade de login ou sessão.
        *   Exibe banners e cards de empresas.
        *   Ao clicar em um banner ou card, o link de destino é aberto em um `iframe` na mesma página, mantendo o header da aplicação.
    *   **Área Administrativa (Privada):**
        *   Acesso restrito por login e senha (controle de sessão).

3.  **Funcionalidades da Área Administrativa (CRUDs):**
    *   **Cadastro de Usuários:**
        *   Campos: `id`, `nome`, `email`, `senha`, `created_at`, `updated_at`.
    *   **Cadastro de Empresas:**
        *   Campos: `id`, `nome`, `url_site`, `url_logo`, `created_at`, `updated_at`.
    *   **Cadastro de Banners:**
        *   Campos: `id`, `nome`, `url_link`, `url_imagem_banner`, `created_at`, `updated_at`.

4.  **Design e Layout:**
    *   **Responsividade:** A aplicação deve ser compatível com desktops e dispositivos móveis (uso de Bootstrap).
    *   **Header:**
        *   Logo à esquerda.
        *   Botão "Contato" à direita (cor verde, letras brancas, com ícone do WhatsApp).
    *   **Conteúdo Principal (Público):**
        *   Banner "Hero" (1920px x 480px) abaixo do header.
        *   Grid de cards com as logos das empresas cadastradas.
    *   **Footer:**
        *   Texto de direitos autorais à esquerda: "Plataforma feita por kmkz.ai.br - todos os direitos reservados".
        *   Link "Painel Admin" à direita para a página de login.

5.  **Configuração:**
    *   Deve existir um arquivo de configuração central para gerenciar:
        *   `header_logo_url`: URL da logo no header.
        *   `header_cor_fundo`: Cor de fundo do header.
        *   `header_cor_letra`: Cor da letra no header.
        *   `footer_cor_fundo`: Cor de fundo do footer.
        *   `footer_cor_letra`: Cor da letra no footer.

6.  **Boas Práticas:**
    *   O desenvolvimento deve seguir as melhores práticas de programação em PHP, incluindo:
        *   **Segurança:** Prevenção contra ataques comuns (SQL Injection, XSS).
        *   **Performance:** Código otimizado.
        *   **SRP (Single Responsibility Principle):** Cada classe/arquivo deve ter uma única responsabilidade.
