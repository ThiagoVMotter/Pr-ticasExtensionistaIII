<?php
require_once '../includes/header.php';

if (!isset($_SESSION['usuario_id'])) {
    header("Location: " . BASE_URL . "login/login.php");
    exit;
}

$sql = "SELECT a.id_agendamento, a.data_agendamento, a.horario, a.status, 
               p.nome AS pet_nome, c.nome AS cliente_nome, 
               s.nome AS servico_nome, f.nome AS func_nome 
        FROM agendamento a
        INNER JOIN pet p ON a.id_pet = p.id_pet
        INNER JOIN cliente c ON p.id_cliente = c.id_cliente
        INNER JOIN servico s ON a.id_servico = s.id_servico
        INNER JOIN funcionario f ON a.id_funcionario = f.id_funcionario
        ORDER BY a.data_agendamento ASC, a.horario ASC";

$stmt = $pdo->query($sql);
$agendamentos = $stmt->fetchAll();
?>

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
    <h2>Agenda de Serviços</h2>
    <a href="novo_agendamento.php" class="btn" style="background-color: var(--success-color);">+ Novo Agendamento</a>
</div>

<?php 
if (isset($_GET['msg'])) {
    if ($_GET['msg'] == 'sucesso') echo "<div style='padding: 10px; background: #dcfce7; color: #166534; margin-bottom: 1rem; border-radius: 4px;'>Agendamento registrado com sucesso!</div>";
    if ($_GET['msg'] == 'status_ok') echo "<div style='padding: 10px; background: #dcfce7; color: #166534; margin-bottom: 1rem; border-radius: 4px;'>Status atualizado com sucesso!</div>";
    if ($_GET['msg'] == 'erro') echo "<div style='padding: 10px; background: #fee2e2; color: #991b1b; margin-bottom: 1rem; border-radius: 4px;'>Erro ao processar a solicitação.</div>";
}
?>

<div style="overflow-x: auto; background: #fff; padding: 1rem; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
    <table style="width: 100%; border-collapse: collapse; text-align: left;">
        <thead>
            <tr style="border-bottom: 2px solid var(--border-color); background-color: var(--bg-color);">
                <th style="padding: 12px;">Data / Hora</th>
                <th style="padding: 12px;">Pet (Tutor)</th>
                <th style="padding: 12px;">Serviço</th>
                <th style="padding: 12px;">Profissional</th>
                <th style="padding: 12px;">Status</th>
                <th style="padding: 12px;">Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($agendamentos) > 0): ?>
                <?php foreach ($agendamentos as $a): ?>
                    <tr style="border-bottom: 1px solid var(--border-color);">
                        <td style="padding: 12px; font-weight: 500;">
                            <?php echo date('d/m/Y', strtotime($a['data_agendamento'])) . ' às ' . date('H:i', strtotime($a['horario'])); ?>
                        </td>
                        <td style="padding: 12px;">
                            <strong><?php echo htmlspecialchars($a['pet_nome']); ?></strong><br>
                            <small style="color: #64748b;"><?php echo htmlspecialchars($a['cliente_nome']); ?></small>
                        </td>
                        <td style="padding: 12px;"><?php echo htmlspecialchars($a['servico_nome']); ?></td>
                        <td style="padding: 12px;"><?php echo htmlspecialchars($a['func_nome']); ?></td>
                        <td style="padding: 12px;">
                            <?php 
                                $cor_status = '#64748b';
                                if ($a['status'] == 'Confirmado') $cor_status = '#0284c7';
                                if ($a['status'] == 'Concluido') $cor_status = '#22c55e';
                                if ($a['status'] == 'Cancelado') $cor_status = '#ef4444';
                            ?>
                            <span style="background-color: <?php echo $cor_status; ?>; color: white; padding: 4px 8px; border-radius: 4px; font-size: 0.85rem;">
                                <?php echo htmlspecialchars($a['status']); ?>
                            </span>
                        </td>
                        <td style="padding: 12px;">
                            <?php if ($a['status'] == 'Pendente'): ?>
                                <a href="status_agendamento.php?id=<?php echo $a['id_agendamento']; ?>&acao=Confirmado" class="btn" style="padding: 0.3rem 0.6rem; font-size: 0.85rem; background-color: #0284c7;">Confirmar</a>
                            <?php endif; ?>
                            
                            <?php if ($a['status'] == 'Confirmado'): ?>
                                <a href="status_agendamento.php?id=<?php echo $a['id_agendamento']; ?>&acao=Concluido" class="btn" style="padding: 0.3rem 0.6rem; font-size: 0.85rem; background-color: #22c55e;">Concluir</a>
                            <?php endif; ?>

                            <?php if ($a['status'] != 'Concluido' && $a['status'] != 'Cancelado'): ?>
                                <a href="status_agendamento.php?id=<?php echo $a['id_agendamento']; ?>&acao=Cancelado" class="btn btn-danger" style="padding: 0.3rem 0.6rem; font-size: 0.85rem;" onclick="return confirm('Deseja realmente cancelar este agendamento?');">Cancelar</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="6" style="padding: 12px; text-align: center; color: #64748b;">Nenhum agendamento encontrado.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php require_once '../includes/footer.php'; ?>