<?php

class Company {
    private $conn;
    private $table = 'empresas';

    // Propriedades da Empresa
    public $id;
    public $nome;
    public $url_site;
    public $url_logo;
    public $url_youtube;
    public $created_at;
    public $updated_at;

    public function __construct($db) {
        $this->conn = $db;
    }

    /**
     * Busca todas as empresas.
     * @return PDOStatement O statement com o resultado.
     */
    public function findAll() {
        $query = 'SELECT id, nome, url_site, url_logo, url_youtube, created_at FROM ' . $this->table . ' ORDER BY nome ASC';
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    /**
     * Busca empresas por nome.
     * @param string $name O termo para buscar no nome da empresa.
     * @return PDOStatement O statement com o resultado.
     */
    public function searchByName($name) {
        $query = 'SELECT id, nome, url_site, url_logo, url_youtube, created_at FROM ' . $this->table . ' WHERE nome LIKE :nome ORDER BY nome ASC';
        $stmt = $this->conn->prepare($query);

        // Limpa e associa o parâmetro de busca
        $searchTerm = '%' . htmlspecialchars(strip_tags($name)) . '%';
        $stmt->bindParam(':nome', $searchTerm);

        $stmt->execute();
        return $stmt;
    }

    /**
     * Busca uma única empresa pelo ID.
     * @return bool True se a empresa for encontrada e as propriedades preenchidas, false caso contrário.
     */
    public function findById($id) {
        $query = 'SELECT id, nome, url_site, url_logo, url_youtube FROM ' . $this->table . ' WHERE id = :id LIMIT 1';
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $this->id = $row['id'];
            $this->nome = $row['nome'];
            $this->url_site = $row['url_site'];
            $this->url_logo = $row['url_logo'];
            $this->url_youtube = $row['url_youtube'];
            return true;
        }
        return false;
    }

    /**
     * Cria uma nova empresa.
     * @return bool True se a criação for bem-sucedida, false caso contrário.
     */
    public function create() {
        $query = 'INSERT INTO ' . $this->table . ' (nome, url_site, url_logo, url_youtube) VALUES (:nome, :url_site, :url_logo, :url_youtube)';
        $stmt = $this->conn->prepare($query);

        // Limpa os dados
        $this->nome = htmlspecialchars(strip_tags($this->nome));
        $this->url_site = htmlspecialchars(strip_tags($this->url_site));
        $this->url_logo = htmlspecialchars(strip_tags($this->url_logo));
        $this->url_youtube = htmlspecialchars(strip_tags($this->url_youtube));

        // Associa os parâmetros
        $stmt->bindParam(':nome', $this->nome);
        $stmt->bindParam(':url_site', $this->url_site);
        $stmt->bindParam(':url_logo', $this->url_logo);
        $stmt->bindParam(':url_youtube', $this->url_youtube);

        if ($stmt->execute()) {
            return true;
        }
        // Imprime o erro se houver (útil para debug)
        printf("Error: %s.\n", $stmt->error);
        return false;
    }

    /**
     * Atualiza uma empresa existente.
     * @return bool True se a atualização for bem-sucedida, false caso contrário.
     */
    public function update() {
        $query = 'UPDATE ' . $this->table . ' SET nome = :nome, url_site = :url_site, url_logo = :url_logo, url_youtube = :url_youtube WHERE id = :id';
        $stmt = $this->conn->prepare($query);

        // Limpa os dados
        $this->id = htmlspecialchars(strip_tags($this->id));
        $this->nome = htmlspecialchars(strip_tags($this->nome));
        $this->url_site = htmlspecialchars(strip_tags($this->url_site));
        $this->url_logo = htmlspecialchars(strip_tags($this->url_logo));
        $this->url_youtube = htmlspecialchars(strip_tags($this->url_youtube));

        // Associa os parâmetros
        $stmt->bindParam(':id', $this->id);
        $stmt->bindParam(':nome', $this->nome);
        $stmt->bindParam(':url_site', $this->url_site);
        $stmt->bindParam(':url_logo', $this->url_logo);
        $stmt->bindParam(':url_youtube', $this->url_youtube);

        if ($stmt->execute()) {
            return true;
        }
        printf("Error: %s.\n", $stmt->error);
        return false;
    }

    /**
     * Deleta uma empresa.
     * @return bool True se a deleção for bem-sucedida, false caso contrário.
     */
    public function delete() {
        $query = 'DELETE FROM ' . $this->table . ' WHERE id = :id';
        $stmt = $this->conn->prepare($query);

        // Limpa os dados
        $this->id = htmlspecialchars(strip_tags($this->id));

        // Associa o parâmetro
        $stmt->bindParam(':id', $this->id);

        if ($stmt->execute()) {
            return true;
        }
        printf("Error: %s.\n", $stmt->error);
        return false;
    }

    /**
     * Conta o número total de empresas.
     * @return int O número total de empresas.
     */
    public function countAll() {
        $query = 'SELECT COUNT(id) as total FROM ' . $this->table;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int)($row['total'] ?? 0);
    }

    /**
     * Conta o número total de empresas para um termo de busca.
     * @param string $name O termo de busca.
     * @return int O número de empresas encontradas.
     */
    public function countAllBySearch($name) {
        $query = 'SELECT COUNT(id) as total FROM ' . $this->table . ' WHERE nome LIKE :nome';
        $stmt = $this->conn->prepare($query);
        $searchTerm = '%' . htmlspecialchars(strip_tags($name)) . '%';
        $stmt->bindParam(':nome', $searchTerm);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int)($row['total'] ?? 0);
    }

    /**
     * Busca empresas com paginação.
     * @param int $limit O número de registros a retornar.
     * @param int $offset O deslocamento inicial.
     * @return PDOStatement O statement com o resultado.
     */
    public function findWithPagination($limit, $offset) {
        $query = 'SELECT id, nome, url_site, url_logo, url_youtube, created_at FROM ' . 
                 $this->table . 
                 ' ORDER BY nome ASC LIMIT :limit OFFSET :offset';
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);

        $stmt->execute();
        return $stmt;
    }

    /**
     * Busca empresas por nome com paginação.
     * @param string $name O termo de busca.
     * @param int $limit O número de registros a retornar.
     * @param int $offset O deslocamento inicial.
     * @return PDOStatement O statement com o resultado.
     */
    public function findWithPaginationAndSearch($name, $limit, $offset) {
        $query = 'SELECT id, nome, url_site, url_logo, url_youtube, created_at FROM ' . 
                 $this->table . 
                 ' WHERE nome LIKE :nome ORDER BY nome ASC LIMIT :limit OFFSET :offset';
        
        $stmt = $this->conn->prepare($query);

        $searchTerm = '%' . htmlspecialchars(strip_tags($name)) . '%';
        $stmt->bindParam(':nome', $searchTerm);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);

        $stmt->execute();
        return $stmt;
    }
}