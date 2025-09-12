<?php
$basePath = realpath(__DIR__ . '/../../../../');

// Proteção da página
require_once $basePath . '/src/lib/auth.php';
require_auth();

// Inclui arquivos necessários
require_once $basePath . '/config/database.php';
require_once $basePath . '/src/models/Banner.php';

// Obtém a instância do banco de dados e cria o modelo
$db = Database::getInstance();
$banner = new Banner($db);

// Busca todos os banners
$result = $banner->findAll();
$num = $result->rowCount();


?>

<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Gerenciar Banners</h2>
        <a href="?page=banners-form" class="btn btn-success">Adicionar Novo Banner</a>
    </div>

    <?php 
    // Exibe mensagens de sucesso ou erro (se houver)
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
                            <th>Imagem</th>
                            <th>Nome</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $result->fetch(PDO::FETCH_ASSOC)): ?>
                            <?php extract($row); ?>
                            <tr>
                                <td><img src="<?= htmlspecialchars($url_imagem_banner) ?>" alt="Banner" style="width: 150px; height: auto;"></td>
                                <td><?= htmlspecialchars($nome) ?></td>
                                <td>
                                    <a href="?page=banners-form&id=<?= $id ?>" class="btn btn-primary btn-sm">Editar</a>
                                    <a href="?controller=banner&action=delete&id=<?= $id ?>" class="btn btn-danger btn-sm" onclick="return confirm('Tem certeza que deseja excluir este banner?')">Excluir</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p class="text-center">Nenhum banner encontrado.</p>
            <?php endif; ?>
        </div>
    </div>
</div>


