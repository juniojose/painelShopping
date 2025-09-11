<?php
$basePath = realpath(__DIR__ . '/../../');

// Proteção e includes
require_once $basePath . '/src/lib/auth.php';
require_auth();
require_once $basePath . '/config/database.php';
require_once $basePath . '/src/models/User.php';

// Inicialização
$db = Database::getInstance();
$user = new User($db);

$action = $_GET['action'] ?? '';

// Roteamento de ações
switch ($action) {
    case 'create':
        handlePostRequest(function() use ($user) {
            $user->nome = $_POST['nome'];
            $user->email = $_POST['email'];
            $user->senha = $_POST['senha'];

            if ($user->create()) {
                $_SESSION['message'] = 'Usuário criado com sucesso!';
            } else {
                $_SESSION['message'] = 'Erro ao criar o usuário.';
            }
            redirectToList();
        });
        break;

    case 'update':
        handlePostRequest(function() use ($user) {
            $user->id = $_POST['id'];
            $user->nome = $_POST['nome'];
            $user->email = $_POST['email'];
            
            // Só atualiza a senha se uma nova for fornecida
            if (!empty($_POST['senha'])) {
                $user->senha = $_POST['senha'];
            }

            if ($user->update()) {
                $_SESSION['message'] = 'Usuário atualizado com sucesso!';
            } else {
                $_SESSION['message'] = 'Erro ao atualizar o usuário.';
            }
            redirectToList();
        });
        break;

    case 'delete':
        if (isset($_GET['id'])) {
            // Previne que o usuário logado se auto-exclua
            if ($_GET['id'] == $_SESSION['user_id']) {
                $_SESSION['message'] = 'Erro: Você não pode excluir seu próprio usuário.';
            } else {
                $user->id = $_GET['id'];
                if ($user->delete()) {
                    $_SESSION['message'] = 'Usuário excluído com sucesso!';
                } else {
                    $_SESSION['message'] = 'Erro ao excluir o usuário.';
                }
            }
        }
        redirectToList();
        break;

    default:
        redirectToList();
        break;
}

function handlePostRequest(callable $callback) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $callback();
    } else {
        redirectToList();
    }
}

function redirectToList() {
    header('Location: ../views/admin/usuarios/index.php');
    exit;
}
