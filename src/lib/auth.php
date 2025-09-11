<?php
/**
 * Verifica se o usuário está autenticado. Se não estiver, redireciona para a página de login.
 */
function require_auth() {
    // Garante que a sessão seja iniciada
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    // Verifica se a variável de sessão do usuário não está definida
    if (!isset($_SESSION['user_id'])) {
        // Armazena uma mensagem de erro para exibir na página de login
        $_SESSION['error_message'] = 'Você precisa fazer login para acessar esta página.';
        
        // Redireciona para a página de login
        // O caminho é relativo à raiz do site, assumindo uma configuração de servidor adequada
        header('Location: /views/admin/login.php');
        exit; // Interrompe a execução do script
    }
}
