<?php
session_start();
require_once __DIR__ . '/../config/database.php'; // Inclui a conexão com o banco
require_once __DIR__ . '/../app/Models/Usuario.php';

// Verifica se o usuário está logado
if (!isset($_SESSION["usuario_id"])) {
    header("Location: login.php");
    exit();
}

$usuario = new Usuario(pdo: $pdo);
$dadosUsuario = $usuario->buscarPorId($_SESSION["usuario_id"]);


// Definir o caminho da imagem do perfil
//$fotoPerfil = !empty($dadosUsuario["foto_perfil"]) ? "uploads/" . $dadosUsuario["foto_perfil"] : "uploads/default.png";
//$fotoPerfil = $dadosUsuario["foto_perfil"] ? "uploads/" . $dadosUsuario["foto_perfil"] : "uploads/default.png";
// Verifica se o usuário tem uma foto de perfil
$fotoPerfil = "/nexus/uploads/" . $dadosUsuario["foto_perfil"];

var_dump($fotoPerfil);

?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nexus</title>
    <link rel="stylesheet" href="../public/css/home.css">
</head>

<body>
    <!-- Cabeçalho -->
    <header>
        <div class="logo">Nexus</div>
        <div class="search-bar">
            <input type="text" placeholder="Buscar...">
        </div>
        <div class="nav-icons">
            <a href="messages.php">📩</a>
            <a href="notifications.php">🔔</a>
            <a href="profile.php">👤</a>
        </div>
    </header>

    <!-- Menu Lateral -->
    <nav>
        <div class="sidebar">
            <!-- Informações do Usuário -->
            <div class="user-info">
                <img src="<?= $fotoPerfil ?>" alt="Foto de Perfil" width="150" height="150">
                <h3><?= $dadosUsuario['nome'] ?></h3>
                <p><?= $dadosUsuario['email'] ?></p>
                <div>


                </div>

            </div>

            <!-- Botão de Logout -->
            <a href="logout.php" class="logout-btn">Sair</a>
        </div>
        <ul>
            <li><a href="home.php">Home</a></li>
            <li><a href="explore.php">Explorar</a></li>
            <li><a href="profile.php">Perfil</a></li>
            <li><a href="editar_perfil.php">Editar Perfil</a></li>
            <li><a href="settings.php">Configurações</a></li>
            <li><a href="logout.php">Sair</a></li>
        </ul>
    </nav>

    <!-- Feed de Postagens -->
    <main>
        <div>
            <?= var_dump($dadosUsuario); ?>
        </div>
        <div class="post-box">
            <textarea placeholder="O que está acontecendo, [Nome]?" rows="3"></textarea>
            <button>Postar</button>
        </div>

        <div class="feed">
            <!-- Exemplo de Postagem -->
            <div class="post">
                <div class="post-header">
                    <img src="perfil.jpg" alt="Foto de Perfil" class="profile-pic">
                    <span class="username">João Silva</span>
                </div>
                <p>Essa é uma postagem incrível!</p>
                <div class="post-actions">
                    <span>👍 Curtir</span>
                    <span>💬 Comentar</span>
                    <span>🔁 Compartilhar</span>
                </div>
            </div>

            <!-- Exemplo de outra Postagem -->
            <div class="post">
                <div class="post-header">
                    <img src="perfil2.jpg" alt="Foto de Perfil" class="profile-pic">
                    <span class="username">Maria Oliveira</span>
                </div>
                <p>Adorei a nova atualização do Nexus!</p>
                <div class="post-actions">
                    <span>👍 Curtir</span>
                    <span>💬 Comentar</span>
                    <span>🔁 Compartilhar</span>
                </div>
            </div>
        </div>
    </main>

    <!-- Rodapé -->
    <footer>
        <p>&copy; 2025 Nexus - Todos os direitos reservados.</p>
    </footer>
</body>

</html>