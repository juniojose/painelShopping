<?php
$basePath = realpath(__DIR__ . '/../../../../');

// Proteção e includes
require_once $basePath . '/src/lib/auth.php';
require_auth();
require_once $basePath . '/config/database.php';
require_once $basePath . '/src/models/Company.php';

// Inicialização de variáveis
$db = Database::getInstance();
$company = new Company($db);
$pageTitle = 'Adicionar Nova Empresa';
$action = 'create';

// Modo de edição: se um ID for passado, busca os dados da empresa
if (isset($_GET['id'])) {
    if ($company->findById($_GET['id'])) {
        $pageTitle = 'Editar Empresa';
        $action = 'update';
    } else {
        // Se o ID não for encontrado, redireciona com erro (opcional)
        $_SESSION['message'] = 'Erro: Empresa não encontrada.';
        header('Location: ?page=empresas');
        exit;
    }
}

// Inclui o cabeçalho
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
                    <form action="?controller=company&action=<?= $action ?>" method="POST">
                        <?php if ($action === 'update'): ?>
                            <input type="hidden" name="id" value="<?= htmlspecialchars($company->id) ?>">
                        <?php endif; ?>

                        <div class="mb-3">
                            <label for="nome" class="form-label">Nome da Empresa</label>
                            <input type="text" class="form-control" id="nome" name="nome" value="<?= htmlspecialchars($company->nome ?? '') ?>" required>
                        </div>

                        <div class="mb-3">
                            <label for="url_site" class="form-label">URL do Site</label>
                            <input type="url" class="form-control" id="url_site" name="url_site" value="<?= htmlspecialchars($company->url_site ?? '') ?>" required>
                        </div>

                        <div class="mb-3">
                            <label for="url_logo" class="form-label">URL da Logo</label>
                            <input type="url" class="form-control" id="url_logo" name="url_logo" value="<?= htmlspecialchars($company->url_logo ?? '') ?>" required>
                        </div>

                        <div class="d-flex justify-content-end">
                            <a href="?page=empresas" class="btn btn-secondary me-2">Cancelar</a>
                            <button type="submit" class="btn btn-primary">Salvar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once $basePath . '/templates/footer.php'; ?>
