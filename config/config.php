<?php

// --- CONFIGURAÇÃO DA URL BASE ---
// Detecta automaticamente a URL base da aplicação, funcione na raiz ou em subdiretório.
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https://" : "http://";
$host = $_SERVER['HTTP_HOST'];
// Caminho do diretório do projeto a partir da raiz do servidor web.
$project_path = rtrim(str_replace(basename(dirname(__DIR__)), '', dirname($_SERVER['SCRIPT_NAME'])), '/');
// Se o projeto está na raiz, o caminho pode ser vazio. Se estiver em subpasta, será /nome_da_pasta
$base_url = $protocol . $host . $project_path;
define('BASE_URL', $base_url);
// --------------------------------

// Exibe todos os erros (útil para desenvolvimento)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Define o fuso horário
date_default_timezone_set('America/Sao_Paulo');

// Configurações do Banco de Dados
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'painel_shopping');

// Configurações do Tema
define('THEME_CONFIG', [
    'header_logo_url'   => 'https://via.placeholder.com/150x50.png?text=Sua+Logo',
    'header_cor_fundo'  => '#FFFFFF',
    'header_cor_letra'  => '#000000',
    'footer_cor_fundo'  => '#F8F9FA',
    'footer_cor_letra'  => '#6C757D',
]);
