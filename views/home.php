<?php
session_start();
if (!isset($_SESSION["usuario_id"])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bem-vindo</title>
</head>

<body>
    <h2>Bem-vindo, <?php echo $_SESSION["usuario_nome"]; ?>!</h2>
    <a href="logout.php">Sair</a>
</body>

</html>