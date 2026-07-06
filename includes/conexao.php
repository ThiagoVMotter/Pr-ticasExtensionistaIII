<?php
// Configurações do Banco de Dados (Apontando para o MySQL nativo do Workbench)
$host = 'localhost';
$port = '3306'; // A porta padrão do seu MySQL80
$dbname = 'petshop_mvp';
$user = 'root'; 
$pass = 'root'; // A senha que você configurou no seu banco nativo

define('BASE_URL', '/Projeto/');

try {
    // Conexão PDO com a porta 3306 e senha root
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4", $user, $pass);
    
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Erro na conexão com o banco de dados: " . $e->getMessage());
}
?>