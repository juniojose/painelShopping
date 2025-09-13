<?php
$basePath = realpath(__DIR__ . '/../../');

// Includes
require_once $basePath . '/src/lib/auth.php';
require_auth();
require_once $basePath . '/config/database.php';
require_once $basePath . '/src/models/Company.php';
require_once $basePath . '/src/lib/helpers.php';

// A função de upload foi movida para helpers.php

// Inicialização
$db = Database::getInstance();
$company = new Company($db);
$redirect_url = '?page=empresas';

$action = $_GET['action'] ?? '';

// Roteamento de ações
switch ($action) {
    case 'create':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $logo_path = handle_file_upload($_FILES['logo'] ?? null, 'logos');

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
            $logo_path = handle_file_upload($_FILES['logo'] ?? null, 'logos', $current_logo);
            
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
