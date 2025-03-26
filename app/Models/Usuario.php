<?php
require_once __DIR__ . '/../../config/database.php';

// if (!isset($pdo)) {
//     die("Erro: Conexão com banco de dados não encontrada.");
// }
class Usuario
{
    private $pdo;

    public function __construct($pdo)
    {

        $this->pdo = $pdo;
    }


    public function cadastrar($nome, $email, $senha)
    {
        $senhaHash = password_hash($senha, PASSWORD_DEFAULT);
        $sql = "INSERT INTO usuarios (nome, email, senha, data_cadastro) VALUES (:nome, :email, :senha, NOW())";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(":nome", $nome);
        $stmt->bindParam(":email", $email);
        $stmt->bindParam(":senha", $senhaHash);

        return $stmt->execute();
    }

    /**
     * Busca um usuário pelo email.
     *
     * @param string $email O email do usuário a ser buscado.
     * @return array|false Retorna um array associativo contendo os dados do usuário se encontrado, ou false se não encontrado.
     */

    public function buscarPorEmail($email)
    {
        $sql = "SELECT * FROM usuarios WHERE email = :email";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(":email", $email);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }


    /**
     * Verifica o login do usuário com base no email e senha fornecidos.
     *
     * @param string $email O email do usuário.
     * @param string $senha A senha do usuário.
     * @return array|false Retorna os dados do usuário se a senha estiver correta, ou false se a verificação falhar.
     */
    public function verificarLogin($email, $senha)
    {
        $usuario = $this->buscarPorEmail($email);

        if ($usuario && password_verify($senha, $usuario['senha'])) {
            return $usuario; // Retorna os dados do usuário se a senha estiver correta
        }
        return false;
    }


    /**
     * Busca o perfil de um usuário pelo ID.
     *
     * @param int $id O ID do usuário a ser buscado.
     * @return array|false Um array associativo contendo os dados do perfil do usuário, ou false se não encontrado.
     */
    public function buscarPorId($id)
    {
        $query = "SELECT id_usuario, nome, email, foto_perfil FROM usuarios WHERE id_usuario = :id";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }


    /**
     * Atualiza o perfil do usuário com base no ID fornecido.
     *
     * @param int $id O ID do usuário.
     * @param string $nome O novo nome do usuário.
     * @param string $email O novo email do usuário.
     * @param string|null $foto (Opcional) O caminho para a nova foto de perfil do usuário.
     * @return bool Retorna true em caso de sucesso ou false em caso de falha.
     */
    public function atualizarPerfil($id, $nome, $email, $foto = null)
    {
        if ($foto) {
            $query = "UPDATE usuarios SET nome = :nome, email = :email, foto_perfil = :foto WHERE id_usuario = :id";
        } else {
            $query = "UPDATE usuarios SET nome = :nome, email = :email WHERE id_usuario = :id";
        }

        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        if ($foto) {
            $stmt->bindParam(':foto', $foto);
        }

        return $stmt->execute();
    }

    /**
     * Atualiza a foto de perfil de um usuário.
     *
     * @param int $id O ID do usuário.
     * @param string $foto O caminho da nova foto de perfil.
     * @return bool Retorna true em caso de sucesso ou false em caso de falha.
     */
    public function atualizarFotoPerfil($id, $foto)
    {
        $sql = "UPDATE usuarios SET foto_perfil = ? WHERE id_usuario = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$foto, $id]);
    }




}