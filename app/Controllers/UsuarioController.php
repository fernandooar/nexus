<?php 
require_once '../Models/Usuario.php'

class UsuarioController {


    /**
     * Cadastra um novo usuário com as informações fornecidas.
     *
     * @param string $nome O nome do usuário.
     * @param string $email O email do usuário.
     * @param string $senha A senha do usuário.
     * @return string Mensagem indicando o sucesso ou falha do cadastro.
     */
    public function cadastrarUsuario($nome, $email, $senha) {
        $usuario = new Usuario();
        $usuario->nome = $nome;
        $usuario->email = $email;
        $usuario->senha =$senha;

        if ($usuario->novoUsuario) {
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

    public function login($email, $senha) {
        $usuario = new Usuario();
        $dadosUsuario = $usuario->buscarPorEmail($email);

        if ($dadosUsuario && password_verify($senha, $dadosUsuario['senha'])){
            return 'Login realizado com sucesso!';
        } else {
            return 'Email ou senha incorretos';
        }
    }
}

?>