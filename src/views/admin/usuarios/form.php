<?php
$basePath = realpath(__DIR__ . '/../../../../');

// Proteção e includes
require_once $basePath . '/src/lib/auth.php';
require_auth();
require_once $basePath . '/config/database.php';
require_once $basePath . '/src/models/User.php';

// Inicialização
$db = Database::getInstance();
$user = new User($db);
$pageTitle = 'Adicionar Novo Usuário';
$action = 'create';

// Modo de edição
if (isset($_GET['id'])) {
    if ($user->findById($_GET['id'])) {
        $pageTitle = 'Editar Usuário';
        $action = 'update';
    } else {
        $_SESSION['message'] = 'Erro: Usuário não encontrado.';
        header('Location: ?page=usuarios');
        exit;
    }
}

require_once $basePath . '/templates/header.php';
?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3><?= $pageTitle ?></h3>
                </div>
                <div class="card-body">
                    <form action="?controller=user&action=<?= $action ?>" method="POST">
                        <?php if ($action === 'update'): ?>
                            <input type="hidden" name="id" value="<?= htmlspecialchars($user->id) ?>">
                        <?php endif; ?>

                        <div class="mb-3">
                            <label for="nome" class="form-label">Nome</label>
                            <input type="text" class="form-control" id="nome" name="nome" value="<?= htmlspecialchars($user->nome ?? '') ?>" required>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">E-mail</label>
                            <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($user->email ?? '') ?>" required>
                        </div>

                        <div class="mb-3">
                            <label for="senha" class="form-label">Senha</label>
                            <input type="password" class="form-control" id="senha" name="senha" <?= ($action === 'create') ? 'required' : '' ?>>
                            <?php if ($action === 'update'): ?>
                                <div class="form-text">Deixe em branco para não alterar a senha.</div>
                            <?php endif; ?>
                        </div>

                        <div class="d-flex justify-content-end">
                            <a href="?page=usuarios" class="btn btn-secondary me-2">Cancelar</a>
                            <button type="submit" class="btn btn-primary">Salvar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once $basePath . '/templates/footer.php'; ?>
