<?php
session_start();
require_once '../includes/conexao.php';

// Proteção
if (!isset($_SESSION['usuario_id'])) {
    header("Location: " . BASE_URL . "login/login.php");
    exit;
}

$id = $_GET['id'] ?? null;

if ($id) {
    try {
        $stmt = $pdo->prepare("DELETE FROM cliente WHERE id_cliente = :id");
        $stmt->execute([':id' => $id]);
        
        header("Location: listar_clientes.php?msg=sucesso");
        exit;
    } catch (PDOException $e) {
        // Código 1451 do MySQL significa que ocorreu um erro de Foreign Key (o cliente tem um pet)
        if ($e->getCode() == '23000' || $e->errorInfo[1] == 1451) {
            header("Location: listar_clientes.php?msg=erro_fk");
        } else {
            header("Location: listar_clientes.php?msg=erro");
        }
        exit;
    }
} else {
    header("Location: listar_clientes.php");
    exit;
}
?>