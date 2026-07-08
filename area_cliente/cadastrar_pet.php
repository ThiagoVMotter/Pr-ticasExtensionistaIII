<?php
require_once '../includes/header.php';

if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_perfil'] !== 'cliente') {
    header("Location: " . BASE_URL . "index.php");
    exit;
}

$erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome']);
    $raca = trim($_POST['raca']);
    $sexo = $_POST['sexo'];
    $peso = !empty($_POST['peso']) ? str_replace(',', '.', $_POST['peso']) : null;
    $cor = trim($_POST['cor']);

    if (!empty($nome) && !empty($raca) && !empty($sexo)) {
        try {
            $stmt = $pdo->prepare("INSERT INTO pet (id_cliente, nome, raca, sexo, peso, cor) VALUES (:id_cliente, :nome, :raca, :sexo, :peso, :cor)");
            $stmt->execute([
                ':id_cliente' => $_SESSION['usuario_id'],
                ':nome' => $nome,
                ':raca' => $raca,
                ':sexo' => $sexo,
                ':peso' => $peso,
                ':cor' => $cor
            ]);
            
            header("Location: meus_pets.php?msg=sucesso");
            exit;
        } catch (PDOException $e) {
            $erro = "Erro ao cadastrar o pet: " . $e->getMessage();
        }
    } else {
        $erro = "Nome, Raça e Sexo são campos obrigatórios.";
    }
}
?>

<div style="max-width: 600px; margin: 0 auto; background: #fff; padding: 2rem; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
    <h2 style="margin-bottom: 1.5rem; color: var(--primary-color);">Adicionar Novo Pet</h2>
    
    <?php if ($erro) echo "<div style='padding: 10px; background: #fee2e2; color: #991b1b; margin-bottom: 1rem; border-radius: 4px;'>$erro</div>"; ?>

    <form action="cadastrar_pet.php" method="POST" style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
        <div>
            <label style="font-weight: 500;">Nome do Pet *</label>
            <input type="text" name="nome" required style="width: 100%; padding: 0.5rem; border: 1px solid var(--border-color); border-radius: 4px; margin-top: 0.3rem;">
        </div>
        
        <div>
            <label style="font-weight: 500;">Raça *</label>
            <input type="text" name="raca" required placeholder="Ex: Poodle, Persa, Vira-lata..." style="width: 100%; padding: 0.5rem; border: 1px solid var(--border-color); border-radius: 4px; margin-top: 0.3rem;">
        </div>

        <div>
            <label style="font-weight: 500;">Sexo *</label>
            <select name="sexo" required style="width: 100%; padding: 0.5rem; border: 1px solid var(--border-color); border-radius: 4px; margin-top: 0.3rem;">
                <option value="">Selecione...</option>
                <option value="Macho">Macho</option>
                <option value="Femea">Fêmea</option>
            </select>
        </div>
        
        <div>
            <label style="font-weight: 500;">Peso (kg)</label>
            <input type="number" step="0.01" name="peso" style="width: 100%; padding: 0.5rem; border: 1px solid var(--border-color); border-radius: 4px; margin-top: 0.3rem;">
        </div>
        
        <div>
            <label style="font-weight: 500;">Cor</label>
            <input type="text" name="cor" style="width: 100%; padding: 0.5rem; border: 1px solid var(--border-color); border-radius: 4px; margin-top: 0.3rem;">
        </div>
        
        <div style="grid-column: 1 / -1; display: flex; gap: 1rem; margin-top: 1rem; justify-content: flex-end;">
            <a href="meus_pets.php" class="btn" style="background-color: #64748b;">Cancelar</a>
            <button type="submit" class="btn" style="background-color: var(--success-color);">Salvar Pet</button>
        </div>
    </form>
</div>

<?php require_once '../includes/footer.php'; ?>