<?php
require_once __DIR__ . "/../app/Models/Usuario.php";

$mensagem = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = trim($_POST["nome"]);
    $email = trim($_POST["email"]);
    $senha = $_POST["senha"];
    
    if (!empty($nome) && !empty($email) && !empty($senha)) {
        $usuario = new Usuario();
        if ($usuario->cadastrar($nome, $email, $senha)) {
            $mensagem = "Cadastro realizado com sucesso! Faça login.";
        } else {
            $mensagem = "Erro ao cadastrar. Tente novamente.";
        }
    } else {
        $mensagem = "Preencha todos os campos!";
    }
}
?>

<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro</title>
</head>

<body>
    <h2>Cadastro</h2>
    <?php if ($mensagem) echo "<p>$mensagem</p>"; ?>
    <form method="POST">
        <input type="text" name="nome" placeholder="Nome" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="senha" placeholder="Senha" required>
        <button type="submit">Cadastrar</button>
    </form>
    <a href="login.php">Já tem conta? Faça login</a>
</body>

</html>