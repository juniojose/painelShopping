<?php
// Garante que a sessão seja iniciada para verificar o login
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Define o caminho raiz do projeto subindo dois níveis (de /public/admin)
$basePath = dirname(dirname(__DIR__));

// Inclui o arquivo de configuração para ter acesso à BASE_URL e outras constantes
require_once $basePath . '/config/config.php';

// Se o usuário já estiver logado, carrega o dashboard.
if (isset($_SESSION['user_id'])) {
    // O dashboard já tem sua própria lógica de autenticação, mas fazemos o include aqui.
    include $basePath . '/src/views/admin/dashboard.php';
} else {
    // Caso contrário, carrega a página de login.
    include $basePath . '/src/views/admin/login.php';
}
