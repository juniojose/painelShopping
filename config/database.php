<?php

class Database {
    private static $instance = null;
    private $conn;

    private function __construct() {
        // As configurações de conexão são importadas do arquivo config.php
        require_once __DIR__ . '/config.php';

        $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        try {
            $this->conn = new PDO($dsn, DB_USER, DB_PASS, $options);
        } catch (PDOException $e) {
            // Em um ambiente de produção, logar o erro em vez de exibi-lo
            throw new PDOException($e->getMessage(), (int)$e->getCode());
        }
    }

    /**
     * Padrão Singleton para garantir uma única instância da conexão.
     * @return PDO A instância da conexão PDO.
     */
    public static function getInstance() {
        if (self::$instance === null) {
            // Cria a instância da classe Database, que por sua vez cria a conexão PDO
            self::$instance = new Database();
        }
        // Retorna o objeto de conexão PDO
        return self::$instance->conn;
    }

    // Previne a clonagem da instância
    private function __clone() { }

    // Previne a desserialização da instância
    public function __wakeup() { }
}
