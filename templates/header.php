<?php
// Garante que a sessão seja iniciada em todas as páginas, antes de qualquer output.
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Inclui o arquivo de configuração base
require_once __DIR__ . '/../config/config.php';

// Carrega as configurações do tema do banco de dados
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../src/models/Setting.php';
$db = Database::getInstance();
$settingModel = new Setting($db);
$themeSettings = $settingModel->getAllSettings();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel Shopping</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    
    <!-- Ícones do Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        /* Estilos personalizados */
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        main {
            flex: 1;
        }
    </style>
</head>
<body>

<header style="background-color: <?= htmlspecialchars($themeSettings['header_cor_fundo']); ?>; color: <?= htmlspecialchars($themeSettings['header_cor_letra']); ?>;">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center py-3">
            <a href="<?= BASE_URL ?>" id="home-logo-link" class="d-flex align-items-center text-decoration-none" style="color: inherit;">
                <img src="<?= htmlspecialchars(rtrim(BASE_URL, '/') . '/' . ltrim($themeSettings['header_logo_url'], '/')) ?>" alt="Logo" height="50">
            </a>
            <div class="d-flex align-items-center">
                <!-- Formulário de Busca -->
                <form action="<?= BASE_URL ?>" method="GET" class="d-flex me-3" role="search">
                    <input class="form-control me-2" type="search" name="search" placeholder="Buscar empresa..." aria-label="Buscar">
                    <button class="btn btn-outline-light" type="submit">Buscar</button>
                </form>
                <a href="https://wa.me/556198343743" target="_blank" class="btn btn-success">
                    <i class="bi bi-whatsapp"></i> Contato
                </a>
            </div>
        </div>
    </div>
</header>

<?php
// Se estiver na área administrativa E o usuário estiver logado, carrega o menu de navegação do admin
if (isset($is_admin_area) && $is_admin_area && isset($_SESSION['user_id'])) {
    require_once __DIR__ . '/admin_nav.php';
}
?>

<main>
