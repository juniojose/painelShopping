<?php

class Setting {
    private $conn;
    private $table = 'settings';

    public function __construct($db) {
        $this->conn = $db;
    }

    /**
     * Busca todas as configurações e as retorna como um array associativo.
     * @return array Um array de chave => valor com todas as configurações.
     */
    public function getAllSettings() {
        $settings = [];
        $query = 'SELECT setting_key, setting_value FROM ' . $this->table;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $settings[$row['setting_key']] = $row['setting_value'];
        }
        return $settings;
    }

    /**
     * Atualiza o valor de uma configuração específica.
     * Usa INSERT ... ON DUPLICATE KEY UPDATE para criar a chave se ela não existir.
     * @param string $key A chave da configuração.
     * @param string $value O novo valor da configuração.
     * @return bool True em caso de sucesso, false em caso de falha.
     */
    public function updateSetting($key, $value) {
        $query = 'INSERT INTO ' . $this->table . ' (setting_key, setting_value) VALUES (:key, :value) ON DUPLICATE KEY UPDATE setting_value = :value';
        $stmt = $this->conn->prepare($query);

        $key = htmlspecialchars(strip_tags($key));
        // Não aplicar strip_tags no valor para permitir cores como '#FFFFFF'
        $value = htmlspecialchars($value);

        $stmt->bindParam(':key', $key);
        $stmt->bindParam(':value', $value);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
}
