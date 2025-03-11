<?php
require_once __DIR__ . "/../app/Models/Usuario.php";
session_start();

$mensagem = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST["email"]);
    $senha = $_POST["senha"];

    if (!empty($email) && !empty($senha)) {
        $usuario = new Usuario();
        $dadosUsuario = $usuario->verificarLogin($email, $senha);
        
        if ($dadosUsuario) {
            $_SESSION["usuario_id"] = $dadosUsuario["id_usuario"];
            $_SESSION["usuario_nome"] = $dadosUsuario["nome"];
            header("Location: home.php"); // Redireciona para a home
            exit();
        } else {
            $mensagem = "Email ou senha incorretos!";
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
    <title>Login</title>
</head>

<body>
    <h2>Login</h2>
    <?php if ($mensagem) echo "<p>$mensagem</p>"; ?>
    <form method="POST">
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="senha" placeholder="Senha" required>
        <button type="submit">Entrar</button>
    </form>
    <a href="cadastro.php">Criar conta</a>
</body>

</html>