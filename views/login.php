<?php
session_start();
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../app/Models/Usuario.php';
//var_dump($pdo);

/*
* Debug da conexão com o banco
if (!isset($pdo)) {
    die("Erro: A conexão com o banco de dados não foi estabelecida.");
} else {
    echo "✅ Conexão estabelecida com sucesso!";
    $stmt = $pdo->query("SELECT * FROM usuarios LIMIT 1");
    $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
    var_dump($resultado);
}
    */


$mensagem = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST["email"]);
    $senha = $_POST["senha"];

    if (!empty($email) && !empty($senha)) {
        $usuario = new Usuario($pdo);
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
<!-- 
<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../public/css/formStyle.css">
    <title>Login</title>
</head>

<body>
    <h2>Login</h2>
    <?php if ($mensagem)
        echo "<p>$mensagem</p>"; ?>
    <form method="POST">
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="senha" placeholder="Senha" required>
        <button type="submit">Entrar</button>
    </form>
    <a href="cadastro.php">Criar conta</a>
</body>

</html>

<!DOCTYPE html>
<html lang="pt-BR"> -->

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nexus - Login</title>
    <link rel="stylesheet" href="../public/css/formStyle.css">
</head>

<body>
    <div class="auth-container">
        <div class="auth-box">
            <h2>Login</h2>
            <?php if ($mensagem)
                echo "<p>$mensagem</p>"; ?>
            <form method="POST">
                <input type="email" name="email" placeholder="Email" required>
                <input type="password" name="senha" placeholder="Senha" required>
                <button type="submit">Entrar</button>
            </form>
            <p>Não tem uma conta? <a href="cadastro.php">Cadastre-se</a></p>
        </div>
    </div>
</body>

</html>