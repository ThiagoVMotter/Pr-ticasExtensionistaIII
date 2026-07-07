<?php
session_start();
require_once '../includes/conexao.php';

if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_perfil'] !== 'administrador') {
    header("Location: " . BASE_URL . "index.php");
    exit;
}

$id = $_GET['id'] ?? null;

if ($id == $_SESSION['usuario_id']) {
    header("Location: cadastrar_usuario.php?msg=erro_self");
    exit;
}

if ($id) {
    try {
        $stmt = $pdo->prepare("DELETE FROM funcionario WHERE id_funcionario = :id");
        $stmt->execute([':id' => $id]);
        
        header("Location: cadastrar_usuario.php?msg=sucesso_del");
        exit;
    } catch (PDOException $e) {
        if ($e->getCode() == '23000' || $e->errorInfo[1] == 1451) {
            header("Location: cadastrar_usuario.php?msg=erro_fk");
        } else {
            header("Location: cadastrar_usuario.php?msg=erro");
        }
        exit;
    }
} else {
    header("Location: cadastrar_usuario.php");
    exit;
}
?>