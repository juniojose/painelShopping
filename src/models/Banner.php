<?php

class Banner {
    private $conn;
    private $table = 'banners';

    // Propriedades do Banner
    public $id;
    public $nome;
    public $url_link;
    public $url_imagem_banner;
    public $created_at;
    public $updated_at;

    public function __construct($db) {
        $this->conn = $db;
    }

    /**
     * Busca todos os banners.
     * @return PDOStatement O statement com o resultado.
     */
    public function findAll() {
        $query = 'SELECT id, nome, url_link, url_imagem_banner, created_at FROM ' . $this->table . ' ORDER BY nome ASC';
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    /**
     * Busca um único banner pelo ID.
     * @return bool True se o banner for encontrado e as propriedades preenchidas, false caso contrário.
     */
    public function findById($id) {
        $query = 'SELECT id, nome, url_link, url_imagem_banner FROM ' . $this->table . ' WHERE id = :id LIMIT 1';
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $this->id = $row['id'];
            $this->nome = $row['nome'];
            $this->url_link = $row['url_link'];
            $this->url_imagem_banner = $row['url_imagem_banner'];
            return true;
        }
        return false;
    }

    /**
     * Cria um novo banner.
     * @return bool True se a criação for bem-sucedida, false caso contrário.
     */
    public function create() {
        $query = 'INSERT INTO ' . $this->table . ' (nome, url_link, url_imagem_banner) VALUES (:nome, :url_link, :url_imagem_banner)';
        $stmt = $this->conn->prepare($query);

        // Limpa os dados
        $this->nome = htmlspecialchars(strip_tags($this->nome));
        $this->url_link = htmlspecialchars(strip_tags($this->url_link));
        $this->url_imagem_banner = htmlspecialchars(strip_tags($this->url_imagem_banner));

        // Associa os parâmetros
        $stmt->bindParam(':nome', $this->nome);
        $stmt->bindParam(':url_link', $this->url_link);
        $stmt->bindParam(':url_imagem_banner', $this->url_imagem_banner);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    /**
     * Atualiza um banner existente.
     * @return bool True se a atualização for bem-sucedida, false caso contrário.
     */
    public function update() {
        $query = 'UPDATE ' . $this->table . ' SET nome = :nome, url_link = :url_link, url_imagem_banner = :url_imagem_banner WHERE id = :id';
        $stmt = $this->conn->prepare($query);

        // Limpa os dados
        $this->id = htmlspecialchars(strip_tags($this->id));
        $this->nome = htmlspecialchars(strip_tags($this->nome));
        $this->url_link = htmlspecialchars(strip_tags($this->url_link));
        $this->url_imagem_banner = htmlspecialchars(strip_tags($this->url_imagem_banner));

        // Associa os parâmetros
        $stmt->bindParam(':id', $this->id);
        $stmt->bindParam(':nome', $this->nome);
        $stmt->bindParam(':url_link', $this->url_link);
        $stmt->bindParam(':url_imagem_banner', $this->url_imagem_banner);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    /**
     * Deleta um banner.
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
        return false;
    }
}
