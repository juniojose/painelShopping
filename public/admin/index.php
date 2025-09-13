<?php
// Garante que a sessão seja iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Define que estamos na área administrativa para carregar componentes específicos
$is_admin_area = true;

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
        'settings' => $basePath . '/src/controllers/SettingsController.php',
    ];

    if (array_key_exists($controller, $controllerMap)) {
        // O arquivo do controller já tem um switch para a variável $action
        require $controllerMap[$controller];
        exit; // Interrompe a execução para não carregar uma view depois
    }
}

// --- ROTEAMENTO DE VIEWS ---

// Se o usuário não estiver logado, a única página que ele pode ver é a de login.
// O formulário de login (e seu layout) é tratado pela própria view.
if (!isset($_SESSION['user_id'])) {
    if (!isset($_GET['controller'])) {
        include $basePath . '/src/views/admin/login.php';
    }
} else {
    // Se o usuário ESTÁ logado, carrega o layout principal do admin que envolve o conteúdo.
    require_once $basePath . '/templates/header.php';

    // Roteamento da view de conteúdo
    $page = $_GET['page'] ?? 'dashboard';

    $allowedPages = [
        'dashboard' => $basePath . '/src/views/admin/dashboard.php',
        'empresas' => $basePath . '/src/views/admin/empresas/index.php',
        'empresas-form' => $basePath . '/src/views/admin/empresas/form.php',
        'banners' => $basePath . '/src/views/admin/banners/index.php',
        'banners-form' => $basePath . '/src/views/admin/banners/form.php',
        'usuarios' => $basePath . '/src/views/admin/usuarios/index.php',
        'usuarios-form' => $basePath . '/src/views/admin/usuarios/form.php',
        'settings' => $basePath . '/src/views/admin/settings/index.php',
    ];

    if (array_key_exists($page, $allowedPages)) {
        include $allowedPages[$page];
    } else {
        include $allowedPages['dashboard'];
    }

    require_once $basePath . '/templates/footer.php';
}
