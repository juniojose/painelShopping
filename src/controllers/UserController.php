<?php
$basePath = realpath(__DIR__ . '/../../');

// Proteção e includes
require_once $basePath . '/src/lib/auth.php';
require_auth();
require_once $basePath . '/config/database.php';
require_once $basePath . '/src/models/User.php';
require_once $basePath . '/src/lib/helpers.php'; // Inclui o novo helper

// Inicialização
$db = Database::getInstance();
$user = new User($db);
$redirect_url = BASE_URL . '/src/views/admin/usuarios/index.php';

$action = $_GET['action'] ?? '';

// Roteamento de ações
switch ($action) {
    case 'create':
        handlePostRequest(function() use ($user) {
            $user->nome = $_POST['nome'];
            $user->email = $_POST['email'];
            $user->senha = $_POST['senha'];

            $_SESSION['message'] = $user->create() 
                ? 'Usuário criado com sucesso!' 
                : 'Erro ao criar o usuário.';
        }, $redirect_url);
        break;

    case 'update':
        handlePostRequest(function() use ($user) {
            $user->id = $_POST['id'];
            $user->nome = $_POST['nome'];
            $user->email = $_POST['email'];
            
            if (!empty($_POST['senha'])) {
                $user->senha = $_POST['senha'];
            }

            $_SESSION['message'] = $user->update() 
                ? 'Usuário atualizado com sucesso!' 
                : 'Erro ao atualizar o usuário.';
        }, $redirect_url);
        break;

    case 'delete':
        if (isset($_GET['id'])) {
            if ($_GET['id'] == $_SESSION['user_id']) {
                $_SESSION['message'] = 'Erro: Você não pode excluir seu próprio usuário.';
            } else {
                $user->id = $_GET['id'];
                $_SESSION['message'] = $user->delete() 
                    ? 'Usuário excluído com sucesso!' 
                    : 'Erro ao excluir o usuário.';
            }
        }
        redirect($redirect_url);
        break;

    default:
        redirect($redirect_url);
        break;
}
