<?php
// Garante que a sessão seja iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Define o caminho raiz do projeto
$basePath = dirname(dirname(__DIR__));

// Inclui arquivos essenciais
require_once $basePath . '/config/config.php';
require_once $basePath . '/src/lib/auth.php';

// Verifica se o usuário está autenticado para qualquer página da área de admin
require_auth();

// Roteamento simples baseado no parâmetro 'page'
$page = $_GET['page'] ?? 'dashboard';

// Lista de páginas permitidas para evitar inclusão de arquivos arbitrários
$allowedPages = [
    'dashboard' => $basePath . '/src/views/admin/dashboard.php',
    'empresas' => $basePath . '/src/views/admin/empresas/index.php',
    'empresas-form' => $basePath . '/src/views/admin/empresas/form.php',
    'banners' => $basePath . '/src/views/admin/banners/index.php',
    'banners-form' => $basePath . '/src/views/admin/banners/form.php',
    'usuarios' => $basePath . '/src/views/admin/usuarios/index.php',
    'usuarios-form' => $basePath . '/src/views/admin/usuarios/form.php',
];

// Verifica se a página solicitada é válida, senão, carrega o dashboard
if (array_key_exists($page, $allowedPages)) {
    include $allowedPages[$page];
} else {
    // Página não encontrada, carrega o dashboard como padrão
    include $allowedPages['dashboard'];
}
