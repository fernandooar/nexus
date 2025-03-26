<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../Models/Usuario.php';

class UsuarioController
{

    private $pdo;
    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }
    /**
     * Cadastra um novo usuário com as informações fornecidas.
     *
     * @param string $nome O nome do usuário.
     * @param string $email O email do usuário.
     * @param string $senha A senha do usuário.
     * @return string Mensagem indicando o sucesso ou falha do cadastro.
     */
    public function cadastrarUsuario($nome, $email, $senha)
    {
        $usuario = new Usuario($this->pdo);
        $usuario->nome = $nome;
        $usuario->email = $email;
        $usuario->senha = $senha;

        if ($usuario->cadastrar($nome, $email, $senha)) {
            return 'Usuário cadastrado com sucesso!';
        } else {
            return 'Erro ao cadastrar usuário.';
        }
    }

    /**
     * Realiza o login de um usuário.
     *
     * @param string $email O email do usuário.
     * @param string $senha A senha do usuário.
     * @return string Mensagem indicando o sucesso ou falha do login.
     */

    public function login($email, $senha)
    {
        $usuario = new Usuario($this->pdo);
        $dadosUsuario = $usuario->buscarPorEmail($email);

        if ($dadosUsuario && password_verify($senha, $dadosUsuario['senha'])) {
            return 'Login realizado com sucesso!';
        } else {
            return 'Email ou senha incorretos';
        }
    }
}

?>