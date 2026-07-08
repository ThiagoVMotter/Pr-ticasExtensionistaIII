<?php
require_once '../includes/header.php';

if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_perfil'] !== 'cliente') {
    header("Location: " . BASE_URL . "index.php");
    exit;
}

$sql = "SELECT a.id_agendamento, a.data_agendamento, a.horario, a.status, 
               p.nome AS pet_nome, s.nome AS servico_nome, f.nome AS func_nome 
        FROM agendamento a
        INNER JOIN pet p ON a.id_pet = p.id_pet
        INNER JOIN servico s ON a.id_servico = s.id_servico
        INNER JOIN funcionario f ON a.id_funcionario = f.id_funcionario
        WHERE p.id_cliente = :id_cliente
        ORDER BY a.data_agendamento DESC, a.horario DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute([':id_cliente' => $_SESSION['usuario_id']]);
$meus_agendamentos = $stmt->fetchAll();
?>

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
    <h2>Meus Agendamentos</h2>
    <a href="novo_agendamento.php" class="btn" style="background-color: var(--success-color);">+ Agendar Serviço</a>
</div>

<?php 
if (isset($_GET['msg'])) {
    if ($_GET['msg'] == 'sucesso') echo "<div style='padding: 10px; background: #dcfce7; color: #166534; margin-bottom: 1rem; border-radius: 4px;'>Agendamento realizado com sucesso! Aguarde a confirmação.</div>";
}
?>

<div style="overflow-x: auto; background: #fff; padding: 1rem; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
    <table style="width: 100%; border-collapse: collapse; text-align: left;">
        <thead>
            <tr style="border-bottom: 2px solid var(--border-color); background-color: var(--bg-color);">
                <th style="padding: 12px;">Data / Hora</th>
                <th style="padding: 12px;">Pet</th>
                <th style="padding: 12px;">Serviço</th>
                <th style="padding: 12px;">Profissional</th>
                <th style="padding: 12px;">Status</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($meus_agendamentos) > 0): ?>
                <?php foreach ($meus_agendamentos as $a): ?>
                    <tr style="border-bottom: 1px solid var(--border-color);">
                        <td style="padding: 12px; font-weight: 500;">
                            <?php echo date('d/m/Y', strtotime($a['data_agendamento'])) . ' às ' . date('H:i', strtotime($a['horario'])); ?>
                        </td>
                        <td style="padding: 12px;"><strong><?php echo htmlspecialchars($a['pet_nome']); ?></strong></td>
                        <td style="padding: 12px;"><?php echo htmlspecialchars($a['servico_nome']); ?></td>
                        <td style="padding: 12px;"><?php echo htmlspecialchars($a['func_nome']); ?></td>
                        <td style="padding: 12px;">
                            <?php 
                                $cor_status = '#64748b'; // Pendente
                                if ($a['status'] == 'Confirmado') $cor_status = '#0284c7';
                                if ($a['status'] == 'Concluido') $cor_status = '#22c55e';
                                if ($a['status'] == 'Cancelado') $cor_status = '#ef4444';
                            ?>
                            <span style="background-color: <?php echo $cor_status; ?>; color: white; padding: 4px 8px; border-radius: 4px; font-size: 0.85rem;">
                                <?php echo htmlspecialchars($a['status']); ?>
                            </span>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="5" style="padding: 12px; text-align: center; color: #64748b;">Você ainda não possui agendamentos.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php require_once '../includes/footer.php'; ?>