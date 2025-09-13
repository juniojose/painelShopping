<?php
$basePath = realpath(__DIR__ . '/../../');

// Includes
require_once $basePath . '/src/lib/auth.php';
require_auth();
require_once $basePath . '/config/database.php';
require_once $basePath . '/src/models/Setting.php';
require_once $basePath . '/src/lib/helpers.php';

// Inicialização
$db = Database::getInstance();
$settingModel = new Setting($db);
$redirect_url = '?page=settings';

$action = $_GET['action'] ?? '';

if ($action === 'update' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // 1. Handle Logo Upload
    $current_logo_path = $_POST['current_header_logo_url'] ?? null;
    $new_logo_path = handle_file_upload($_FILES['header_logo_url'] ?? null, 'theme', $current_logo_path);

    if ($new_logo_path === null && (!isset($_FILES['header_logo_url']) || $_FILES['header_logo_url']['error'] !== UPLOAD_ERR_NO_FILE)) {
        $_SESSION['message'] = 'Erro: O arquivo de logo enviado não é válido ou falhou ao salvar.';
        redirect($redirect_url);
        exit;
    }
    
    // Apenas atualiza o banco se um novo logo foi enviado com sucesso
    if ($new_logo_path !== $current_logo_path) {
         $settingModel->updateSetting('header_logo_url', $new_logo_path);
    }

    // 2. Handle other settings
    $allowed_keys = [
        'header_cor_fundo', 
        'header_cor_letra', 
        'footer_cor_fundo', 
        'footer_cor_letra',
        'companies_per_page'
    ];

    foreach ($allowed_keys as $key) {
        if (isset($_POST[$key])) {
            $value = $_POST[$key];
            
            // Add specific validation
            if ($key === 'companies_per_page') {
                if (!is_numeric($value) || $value < 1) {
                    continue; // Skip invalid number
                }
            } else { // It's a color
                if (!preg_match('/^#([a-fA-F0-9]{6}|[a-fA-F0-9]{3})$/', $value)) {
                    continue; // Skip invalid color
                }
            }
            $settingModel->updateSetting($key, $value);
        }
    }

    $_SESSION['message'] = 'Configurações salvas com sucesso!';
    redirect($redirect_url);

} else {
    // Redirect if not a valid update request
    redirect($redirect_url);
}
