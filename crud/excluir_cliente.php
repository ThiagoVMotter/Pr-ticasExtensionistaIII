<?php
session_start();
require_once '../includes/conexao.php';

// Proteção da página
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_perfil'] === 'cliente') {
    header("Location: " . BASE_URL . "login/login.php");
    exit;
}

$id = $_GET['id'] ?? null;

if ($id) {
    try {
        // Inicia a transação para garantir que, se falhar no meio, nada seja apagado
        $pdo->beginTransaction();

        // 1. Apaga as Vendas do cliente (os itens da venda são apagados automaticamente pelo banco)
        $stmtVenda = $pdo->prepare("DELETE FROM venda WHERE id_cliente = :id");
        $stmtVenda->execute([':id' => $id]);

        // 2. Apaga os Atendimentos médicos ligados aos agendamentos dos pets deste cliente
        $stmtAtendimento = $pdo->prepare("
            DELETE atendimento 
            FROM atendimento 
            INNER JOIN agendamento ON atendimento.id_agendamento = agendamento.id_agendamento 
            INNER JOIN pet ON agendamento.id_pet = pet.id_pet 
            WHERE pet.id_cliente = :id
        ");
        $stmtAtendimento->execute([':id' => $id]);

        // 3. Apaga os Agendamentos dos pets deste cliente
        $stmtAgendamento = $pdo->prepare("
            DELETE agendamento 
            FROM agendamento 
            INNER JOIN pet ON agendamento.id_pet = pet.id_pet 
            WHERE pet.id_cliente = :id
        ");
        $stmtAgendamento->execute([':id' => $id]);

        // 4. Apaga os Pets vinculados ao cliente
        $stmtPet = $pdo->prepare("DELETE FROM pet WHERE id_cliente = :id");
        $stmtPet->execute([':id' => $id]);

        // 5. Finalmente, apaga o próprio Cliente
        $stmtCliente = $pdo->prepare("DELETE FROM cliente WHERE id_cliente = :id");
        $stmtCliente->execute([':id' => $id]);
        
        // Confirma todas as exclusões simultaneamente
        $pdo->commit();
        
        header("Location: listar_clientes.php?msg=sucesso");
        exit;
        
    } catch (PDOException $e) {
        $pdo->rollBack();
        header("Location: listar_clientes.php?msg=erro");
        exit;
    }
} else {
    header("Location: listar_clientes.php");
    exit;
}
?>