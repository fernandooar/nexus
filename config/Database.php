<?php


try {
    global $pdo;
    $pdo = new PDO("mysql:host=localhost;dbname=rede_social;charset=utf8", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    //echo "✅ Conexão estabelecida com sucesso!";
} catch (PDOException $e) {
    die("❌ Erro ao conectar ao banco de dados: " . $e->getMessage());
}
?>