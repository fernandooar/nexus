<?php
require_once __DIR__ . "/../../config/database.php";

class Usuario {
    private $conn;
    
    public function __construct() {
        $db = new Database();
        $this->conn = $db->conn;
    }

    // Método para cadastrar um novo usuário
    public function cadastrar($nome, $email, $senha) {
        $senhaHash = password_hash($senha, PASSWORD_DEFAULT);
        $sql = "INSERT INTO usuarios (nome, email, senha, data_cadastro) VALUES (:nome, :email, :senha, NOW())";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":nome", $nome);
        $stmt->bindParam(":email", $email);
        $stmt->bindParam(":senha", $senhaHash);
        
        return $stmt->execute();
    }

    // Método para buscar um usuário pelo email
    public function buscarPorEmail($email) {
        $sql = "SELECT * FROM usuarios WHERE email = :email";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":email", $email);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Método para verificar login
    public function verificarLogin($email, $senha) {
        $usuario = $this->buscarPorEmail($email);
        
        if ($usuario && password_verify($senha, $usuario['senha'])) {
            return $usuario; // Retorna os dados do usuário se a senha estiver correta
        }
        return false;
    }
}
?>