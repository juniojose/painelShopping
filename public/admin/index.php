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

// --- ROTEAMENTO DE AÇÕES (CONTROLLERS) ---
if (isset($_GET['controller'])) {
    $controller = $_GET['controller'];
    $action = $_GET['action'] ?? 'index'; // Ação padrão

    $controllerMap = [
        'user' => $basePath . '/src/controllers/UserController.php',
        'company' => $basePath . '/src/controllers/CompanyController.php',
        'banner' => $basePath . '/src/controllers/BannerController.php',
        'auth' => $basePath . '/src/controllers/AuthController.php',
    ];

    if (array_key_exists($controller, $controllerMap)) {
        // O arquivo do controller já tem um switch para a variável $action
        require $controllerMap[$controller];
        exit; // Interrompe a execução para não carregar uma view depois
    }
}

// --- ROTEAMENTO DE VIEWS ---

// A autenticação é exigida para todas as views, exceto a de login
// A lógica de login é tratada pelo AuthController, então aqui só precisamos garantir
// que o usuário esteja logado para ver qualquer outra página.
require_auth();

$page = $_GET['page'] ?? 'dashboard';

$allowedPages = [
    'dashboard' => $basePath . '/src/views/admin/dashboard.php',
    'empresas' => $basePath . '/src/views/admin/empresas/index.php',
    'empresas-form' => $basePath . '/src/views/admin/empresas/form.php',
    'banners' => $basePath . '/src/views/admin/banners/index.php',
    'banners-form' => $basePath . '/src/views/admin/banners/form.php',
    'usuarios' => $basePath . '/src/views/admin/usuarios/index.php',
    'usuarios-form' => $basePath . '/src/views/admin/usuarios/form.php',
];

if (array_key_exists($page, $allowedPages)) {
    include $allowedPages[$page];
} else {
    include $allowedPages['dashboard'];
}
