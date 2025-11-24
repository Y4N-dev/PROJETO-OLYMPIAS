<?php
// Caminho do arquivo SQL
$sql_file = __DIR__ . "/../sql/olympias.sql";

// Credenciais padrão do XAMPP
$host = "localhost";
$user = "root";
$pass = "";

// 1. Conecta sem selecionar banco
$pdo = new PDO("mysql:host=$host;charset=utf8mb4", $user, $pass);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// 2. Verifica se o BD existe
$exists = $pdo->query("SHOW DATABASES LIKE 'olympias'")->fetch();

if (!$exists) {

    // Se o banco NÃO existir → Importa automaticamente
    $sql = file_get_contents($sql_file);

    try {
        $pdo->exec($sql);
    } catch (PDOException $e) {
        die("Erro ao importar o banco automaticamente: " . $e->getMessage());
    }
}
