<?php
// Define o caminho base para facilitar a inclusão de arquivos
$basePath = realpath(__DIR__ . '/../../../');

// Inclui e executa a verificação de autenticação
require_once $basePath . '/src/lib/auth.php';
require_auth();

// Inclui o cabeçalho da página
require_once $basePath . '/templates/header.php';
?>

<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Dashboard</h2>
        <a href="?controller=auth&action=logout" class="btn btn-danger">Sair</a>
    </div>

    <div class="alert alert-success">
        <h4 class="alert-heading">Bem-vindo(a), <?= htmlspecialchars($_SESSION['user_nome']); ?>!</h4>
        <p>Você está na área administrativa do Painel Shopping.</p>
        <hr>
        <p class="mb-0">A partir daqui, você poderá gerenciar usuários, empresas e banners.</p>
    </div>

    <div class="row mt-4">
        <!-- Atalhos rápidos (placeholders) -->
        <div class="col-md-4">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title">Gerenciar Empresas</h5>
                    <p class="card-text">Adicionar, editar ou remover empresas.</p>
                    <a href="?page=empresas" class="btn btn-primary">Ir para Empresas</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title">Gerenciar Banners</h5>
                    <p class="card-text">Adicionar, editar ou remover banners.</p>
                    <a href="?page=banners" class="btn btn-primary">Ir para Banners</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title">Gerenciar Usuários</h5>
                    <p class="card-text">Adicionar, editar ou remover usuários.</p>
                    <a href="?page=usuarios" class="btn btn-primary">Ir para Usuários</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once $basePath . '/templates/footer.php'; ?>
