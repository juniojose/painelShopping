<?php
// Define a página atual para destacar o link ativo no menu
$currentPage = $_GET['page'] ?? 'dashboard';
?>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="?page=dashboard">Painel</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#adminNavbar" aria-controls="adminNavbar" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="adminNavbar">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                
                <li class="nav-item">
                    <a class="nav-link <?= (str_starts_with($currentPage, 'empresas')) ? 'active' : '' ?>" href="?page=empresas">Empresas</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= (str_starts_with($currentPage, 'banners')) ? 'active' : '' ?>" href="?page=banners">Banners</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= (str_starts_with($currentPage, 'usuarios')) ? 'active' : '' ?>" href="?page=usuarios">Usuários</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= (str_starts_with($currentPage, 'settings')) ? 'active' : '' ?>" href="?page=settings">Configurações</a>
                </li>
            </ul>
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="?controller=auth&action=logout">Sair</a>
                </li>
            </ul>
        </div>
    </div>
</nav>
