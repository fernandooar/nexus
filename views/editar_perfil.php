<?php
session_start();
require_once __DIR__ . "/../app/Models/Usuario.php";

// Verifica se o usuário está logado
if (!isset($_SESSION["usuario_id"])) {
    header("Location: login.php");
    exit();
}

$usuario = new Usuario($pdo);
$mensagem = "";

// ID do usuário logado
$id_usuario = $_SESSION["usuario_id"];

// Busca os dados do usuário (incluindo foto de perfil)
$dadosUsuario = $usuario->buscarPorId($id_usuario);
$fotoPerfil = $dadosUsuario["foto_perfil"] ? "uploads/" . $dadosUsuario["foto_perfil"] : "uploads/default.png";

// Verifica se foi enviado um arquivo de foto
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["foto"])) {
    $arquivo = $_FILES["foto"];

    // Verifica se houve erro no envio do arquivo
    if ($arquivo["error"] == 0) {
        // Garante que a pasta de uploads exista
        // Garante que o diretório de uploads exista
        $diretorioUploads = "../uploads/";
        if (!is_dir($diretorioUploads)) {
            mkdir($diretorioUploads, 0777, true);
        }

        // Gera um nome único para a imagem
        $extensao = pathinfo($arquivo["name"], PATHINFO_EXTENSION);
        $nomeArquivo = "perfil_" . $id_usuario . "_" . time() . "." . $extensao;
        $caminhoFinal = $diretorioUploads . $nomeArquivo;  // Caminho completo da imagem

        // Move o arquivo para o diretório de uploads
        if (move_uploaded_file($arquivo["tmp_name"], $caminhoFinal)) {
            // Atualiza o banco de dados com o nome do arquivo (apenas o nome do arquivo, sem o caminho)
            $sql = "UPDATE usuarios SET foto_perfil = ? WHERE id_usuario = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$nomeArquivo, $id_usuario]);

            $mensagem = "Perfil atualizado com sucesso!";
            $fotoPerfil = "uploads/" . $nomeArquivo;  // Atualiza o caminho da foto de perfil
        } else {
            $mensagem = "Erro ao salvar a imagem.";
        }


    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Perfil</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <h2>Editar Perfil</h2>

    <?php if ($mensagem): ?>
        <p><?= $mensagem ?></p>
    <?php endif; ?>

    <!-- Exibe a imagem de perfil -->
    <img src="<?= $fotoPerfil ?>" alt="Foto de Perfil" width="150" height="150">

    <form action="editar_perfil.php" method="POST" enctype="multipart/form-data">
        <label for="foto">Alterar foto de perfil:</label>
        <input type="file" name="foto" id="foto">
        <br><br>
        <button type="submit">Salvar</button>
    </form>

    <br>
    <a href="home.php">Voltar para o perfil</a>
</body>

</html>