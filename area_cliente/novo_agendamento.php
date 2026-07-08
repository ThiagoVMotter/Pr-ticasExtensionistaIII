<?php
require_once '../includes/header.php';

if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_perfil'] !== 'cliente') {
    header("Location: " . BASE_URL . "index.php");
    exit;
}

$erro = '';

// Carrega APENAS os pets do cliente logado
$stmtPets = $pdo->prepare("SELECT id_pet, nome FROM pet WHERE id_cliente = :id_cliente ORDER BY nome ASC");
$stmtPets->execute([':id_cliente' => $_SESSION['usuario_id']]);
$meus_pets = $stmtPets->fetchAll();

// Carrega serviços e profissionais
$servicos = $pdo->query("SELECT id_servico, nome, preco FROM servico ORDER BY nome ASC")->fetchAll();
$funcionarios = $pdo->query("SELECT id_funcionario, nome FROM funcionario WHERE perfil != 'administrador' ORDER BY nome ASC")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_pet = $_POST['id_pet'];
    $id_servico = $_POST['id_servico'];
    $id_funcionario = $_POST['id_funcionario'];
    $data_agendamento = $_POST['data_agendamento'];
    $horario = $_POST['horario'];

    // Validação extra de segurança: Garante que o pet selecionado realmente pertence ao cliente
    $pet_valido = false;
    foreach ($meus_pets as $p) {
        if ($p['id_pet'] == $id_pet) $pet_valido = true;
    }

    if (!empty($id_pet) && !empty($id_servico) && !empty($id_funcionario) && !empty($data_agendamento) && !empty($horario)) {
        if ($pet_valido) {
            try {
                // O status padrão no banco já é 'Pendente', então não precisamos passar no INSERT
                $stmt = $pdo->prepare("INSERT INTO agendamento (id_pet, id_servico, id_funcionario, data_agendamento, horario) VALUES (:id_pet, :id_servico, :id_funcionario, :data, :horario)");
                $stmt->execute([
                    ':id_pet' => $id_pet,
                    ':id_servico' => $id_servico,
                    ':id_funcionario' => $id_funcionario,
                    ':data' => $data_agendamento,
                    ':horario' => $horario
                ]);
                
                header("Location: meus_agendamentos.php?msg=sucesso");
                exit;
                
            } catch (PDOException $e) {
                // Tratamento da trava de concorrência do banco de dados
                if ($e->getCode() == '23000' || $e->errorInfo[1] == 1062) {
                    $erro = "Ops! Este profissional já possui um serviço marcado para este exato dia e horário. Por favor, escolha outro horário.";
                } else {
                    $erro = "Erro ao processar agendamento: " . $e->getMessage();
                }
            }
        } else {
            $erro = "Operação inválida. O Pet selecionado não pertence a você.";
        }
    } else {
        $erro = "Todos os campos são de preenchimento obrigatório.";
    }
}
?>

<div style="max-width: 600px; margin: 0 auto; background: #fff; padding: 2rem; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
    <h2 style="margin-bottom: 1.5rem; color: var(--primary-color);">Agendar um Serviço</h2>
    <p style="margin-bottom: 1.5rem; color: #64748b; font-size: 0.9rem;">Preencha os dados abaixo. Após o agendamento, nossa equipe revisará a solicitação e confirmará o horário.</p>
    
    <?php if ($erro) echo "<div style='padding: 10px; background: #fee2e2; color: #991b1b; margin-bottom: 1rem; border-radius: 4px;'>$erro</div>"; ?>

    <form action="novo_agendamento.php" method="POST" style="display: flex; flex-direction: column; gap: 1rem;">
        
        <div>
            <label style="font-weight: 500; display:block; margin-bottom: 0.3rem;">Qual dos seus pets será atendido? *</label>
            <select name="id_pet" required style="width: 100%; padding: 0.5rem; border: 1px solid var(--border-color); border-radius: 4px;">
                <option value="">-- Escolha um Pet --</option>
                <?php foreach ($meus_pets as $p): ?>
                    <option value="<?php echo $p['id_pet']; ?>"><?php echo htmlspecialchars($p['nome']); ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div>
            <label style="font-weight: 500; display:block; margin-bottom: 0.3rem;">Serviço Desejado *</label>
            <select name="id_servico" required style="width: 100%; padding: 0.5rem; border: 1px solid var(--border-color); border-radius: 4px;">
                <option value="">-- Escolha o Serviço --</option>
                <?php foreach ($servicos as $s): ?>
                    <option value="<?php echo $s['id_servico']; ?>">
                        <?php echo htmlspecialchars($s['nome']) . ' - R$ ' . number_format($s['preco'], 2, ',', '.'); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div>
            <label style="font-weight: 500; display:block; margin-bottom: 0.3rem;">Profissional *</label>
            <select name="id_funcionario" required style="width: 100%; padding: 0.5rem; border: 1px solid var(--border-color); border-radius: 4px;">
                <option value="">-- Escolha o Profissional --</option>
                <?php foreach ($funcionarios as $f): ?>
                    <option value="<?php echo $f['id_funcionario']; ?>"><?php echo htmlspecialchars($f['nome']); ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div style="display: flex; gap: 1rem;">
            <div style="flex: 1;">
                <label style="font-weight: 500; display:block; margin-bottom: 0.3rem;">Data *</label>
                <input type="date" name="data_agendamento" required min="<?php echo date('Y-m-d'); ?>" style="width: 100%; padding: 0.5rem; border: 1px solid var(--border-color); border-radius: 4px;">
            </div>
            <div style="flex: 1;">
                <label style="font-weight: 500; display:block; margin-bottom: 0.3rem;">Horário *</label>
                <input type="time" name="horario" required style="width: 100%; padding: 0.5rem; border: 1px solid var(--border-color); border-radius: 4px;">
            </div>
        </div>
        
        <div style="display: flex; gap: 1rem; margin-top: 1rem;">
            <button type="submit" class="btn" style="background-color: var(--success-color);">Confirmar Solicitação</button>
            <a href="meus_agendamentos.php" class="btn" style="background-color: #64748b;">Cancelar</a>
        </div>
    </form>
</div>

<?php require_once '../includes/footer.php'; ?>