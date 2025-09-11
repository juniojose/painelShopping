<?php
$basePath = realpath(__DIR__ . '/../../../../');

// Proteção da página
require_once $basePath . '/src/lib/auth.php';
require_auth();

// Inclui arquivos necessários
require_once $basePath . '/config/database.php';
require_once $basePath . '/src/models/User.php';

// Obtém a instância do banco de dados e cria o modelo
$db = Database::getInstance();
$user = new User($db);

// Busca todos os usuários
$result = $user->findAll();
$num = $result->rowCount();

// Inclui o cabeçalho
require_once $basePath . '/templates/header.php';
?>

<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Gerenciar Usuários</h2>
        <a href="?page=usuarios-form" class="btn btn-success">Adicionar Novo Usuário</a>
    </div>

    <?php 
    if (isset($_SESSION['message'])) {
        echo '<div class="alert alert-success">' . htmlspecialchars($_SESSION['message']) . '</div>';
        unset($_SESSION['message']);
    }
    ?>

    <div class="card">
        <div class="card-body">
            <?php if ($num > 0): ?>
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>Nome</th>
                            <th>Email</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $result->fetch(PDO::FETCH_ASSOC)): ?>
                            <?php extract($row); ?>
                            <tr>
                                <td><?= htmlspecialchars($nome) ?></td>
                                <td><?= htmlspecialchars($email) ?></td>
                                <td>
                                    <a href="?page=usuarios-form&id=<?= $id ?>" class="btn btn-primary btn-sm">Editar</a>
                                    <?php // Não permite excluir o próprio usuário logado
                                    if ($id != $_SESSION['user_id']): ?>
                                        <a href="?controller=user&action=delete&id=<?= $id ?>" class="btn btn-danger btn-sm" onclick="return confirm('Tem certeza que deseja excluir este usuário?')">Excluir</a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p class="text-center">Nenhum usuário encontrado.</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once $basePath . '/templates/footer.php'; ?>
