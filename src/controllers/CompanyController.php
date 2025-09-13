<?php
$basePath = realpath(__DIR__ . '/../../');

// Includes
require_once $basePath . '/src/lib/auth.php';
require_auth();
require_once $basePath . '/config/database.php';
require_once $basePath . '/src/models/Company.php';
require_once $basePath . '/src/lib/helpers.php';

/**
 * Gerencia o upload de um arquivo de logo.
 *
 * @param array|null $file O arquivo de $_FILES.
 * @param string|null $current_logo O caminho do logo atual (para substituição).
 * @return string|null O novo caminho do arquivo ou o caminho do logo atual se nenhum novo arquivo for enviado.
 */
function handle_logo_upload($file, $current_logo = null) {
    $basePath = realpath(__DIR__ . '/../../');
    $upload_dir = $basePath . '/public/uploads/logos/';

    // Garante que o diretório de upload exista. Tenta criá-lo se não existir.
    if (!is_dir($upload_dir)) {
        if (!mkdir($upload_dir, 0775, true)) {
            // Se a criação do diretório falhar, retorna null para indicar o erro.
            error_log("Falha ao criar o diretório de upload: " . $upload_dir);
            return null;
        }
    }

    // Se nenhum arquivo novo for enviado, retorna o logo atual
    if (!isset($file) || $file['error'] === UPLOAD_ERR_NO_FILE) {
        return $current_logo;
    }

    // Verifica outros erros de upload
    if ($file['error'] !== UPLOAD_ERR_OK) {
        error_log("Erro de upload de arquivo: " . $file['error']);
        return null; // Indica falha no upload
    }

    $file_tmp_path = $file['tmp_name'];
    $file_name = basename($file['name']);
    $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
    $allowed_ext = ['jpg', 'jpeg', 'png', 'gif', 'svg'];

    if (in_array($file_ext, $allowed_ext)) {
        $new_file_name = uniqid('', true) . '.' . $file_ext;
        $destination = $upload_dir . $new_file_name;

        if (move_uploaded_file($file_tmp_path, $destination)) {
            // Remove o logo antigo se um novo foi enviado com sucesso
            if ($current_logo && file_exists($basePath . '/public' . $current_logo)) {
                unlink($basePath . '/public' . $current_logo);
            }
            // Retorna o caminho relativo para salvar no banco
            return '/uploads/logos/' . $new_file_name;
        }
    }

    error_log("Falha ao mover o arquivo ou tipo de arquivo inválido.");
    return null; // Retorna null se o tipo de arquivo for inválido ou se move_uploaded_file falhar
}

// Inicialização
$db = Database::getInstance();
$company = new Company($db);
$redirect_url = '?page=empresas';

$action = $_GET['action'] ?? '';

// Roteamento de ações
switch ($action) {
    case 'create':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $logo_path = handle_logo_upload($_FILES['logo'] ?? null);

            if ($logo_path === null && (!isset($_FILES['logo']) || $_FILES['logo']['error'] !== UPLOAD_ERR_NO_FILE)) {
                $_SESSION['message'] = 'Erro: O upload de uma imagem de logo válida é obrigatório e falhou.';
                redirect('?page=empresas-form');
                break;
            }

            $company->nome = $_POST['nome'];
            $company->url_site = $_POST['url_site'];
            $company->url_logo = $logo_path;

            $_SESSION['message'] = $company->create()
                ? 'Empresa criada com sucesso!'
                : 'Erro ao criar a empresa.';
            redirect($redirect_url);
        }
        break;

    case 'update':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $current_logo = $_POST['current_logo'] ?? null;
            $logo_path = handle_logo_upload($_FILES['logo'] ?? null, $current_logo);
            
            if ($logo_path === null && (!isset($_FILES['logo']) || $_FILES['logo']['error'] !== UPLOAD_ERR_NO_FILE)) {
                $_SESSION['message'] = 'Erro: O arquivo de logo enviado não é válido ou falhou ao salvar.';
                redirect('?page=empresas-form&id=' . $_POST['id']);
                break;
            }

            $company->id = $_POST['id'];
            $company->nome = $_POST['nome'];
            $company->url_site = $_POST['url_site'];
            $company->url_logo = $logo_path;

            $_SESSION['message'] = $company->update()
                ? 'Empresa atualizada com sucesso!'
                : 'Erro ao atualizar a empresa.';
            redirect($redirect_url);
        }
        break;

    case 'delete':
        if (isset($_GET['id'])) {
            // Busca a empresa para obter o caminho do logo antes de deletar
            if ($company->findById($_GET['id'])) {
                $logo_file = $basePath . '/public' . $company->url_logo;
                if ($company->url_logo && file_exists($logo_file)) {
                    unlink($logo_file);
                }
            }

            $company->id = $_GET['id'];
            $_SESSION['message'] = $company->delete()
                ? 'Empresa excluída com sucesso!'
                : 'Erro ao excluir a empresa.';
        }
        redirect($redirect_url);
        break;

    default:
        redirect($redirect_url);
        break;
}