<?php
session_start();
require_once '../includes/conexao.php';

if (!isset($_SESSION['usuario_id'])) {
    header("Location: " . BASE_URL . "login/login.php");
    exit;
}

$id = $_GET['id'] ?? null;
$acao = $_GET['acao'] ?? null;

// Valida se a ação é uma das permitidas no ENUM do banco de dados
$acoes_validas = ['Confirmado', 'Concluido', 'Cancelado'];

if ($id && in_array($acao, $acoes_validas)) {
    try {
        $stmt = $pdo->prepare("UPDATE agendamento SET status = :status WHERE id_agendamento = :id");
        $stmt->execute([
            ':status' => $acao,
            ':id' => $id
        ]);
        
        header("Location: agendamentos.php?msg=status_ok");
        exit;
    } catch (PDOException $e) {
        header("Location: agendamentos.php?msg=erro");
        exit;
    }
} else {
    header("Location: agendamentos.php");
    exit;
}
?>