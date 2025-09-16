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

    // 2. Handle OpenGraph Image Upload
    $current_og_image_path = $_POST['current_og_image'] ?? null;
    $new_og_image_path = handle_file_upload($_FILES['og_image'] ?? null, 'theme', $current_og_image_path);

    if ($new_og_image_path === null && (!isset($_FILES['og_image']) || $_FILES['og_image']['error'] !== UPLOAD_ERR_NO_FILE)) {
        $_SESSION['message'] = 'Erro: O arquivo de imagem OpenGraph enviado não é válido ou falhou ao salvar.';
        redirect($redirect_url);
        exit;
    }

    if ($new_og_image_path !== $current_og_image_path) {
        $settingModel->updateSetting('og_image', $new_og_image_path);
    }

    // 3. Handle other text, color, and numeric settings
    $settings_to_process = [
        'header_cor_fundo' => 'color',
        'header_cor_letra' => 'color',
        'footer_cor_fundo' => 'color',
        'footer_cor_letra' => 'color',
        'companies_per_page' => 'numeric',
        'companies_columns' => 'numeric',
        'og_title' => 'text',
        'og_description' => 'text'
    ];

    foreach ($settings_to_process as $key => $type) {
        if (isset($_POST[$key])) {
            $value = $_POST[$key];
            $is_valid = false;

            switch ($type) {
                case 'color':
                    if (preg_match('/^#([a-fA-F0-9]{6}|[a-fA-F0-9]{3})$/', $value)) {
                        $is_valid = true;
                    }
                    break;
                case 'numeric':
                    if (is_numeric($value) && $value >= 1) {
                        $is_valid = true;
                    }
                    break;
                case 'text':
                    // For text, we just ensure it's a string and trim it.
                    // No special validation needed here, as it will be escaped on output.
                    $value = trim($value);
                    $is_valid = true;
                    break;
            }

            if ($is_valid) {
                $settingModel->updateSetting($key, $value);
            }
        }
    }

    $_SESSION['message'] = 'Configurações salvas com sucesso!';
    redirect($redirect_url);

} else {
    // Redirect if not a valid update request
    redirect($redirect_url);
}
