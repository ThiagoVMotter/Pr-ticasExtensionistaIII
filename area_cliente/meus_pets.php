<?php
require_once '../includes/header.php';

// Proteção: Apenas clientes acessam esta tela
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_perfil'] !== 'cliente') {
    header("Location: " . BASE_URL . "index.php");
    exit;
}

// Busca os pets pertencentes EXCLUSIVAMENTE ao cliente logado
$stmt = $pdo->prepare("SELECT * FROM pet WHERE id_cliente = :id_cliente ORDER BY nome ASC");
$stmt->execute([':id_cliente' => $_SESSION['usuario_id']]);
$meus_pets = $stmt->fetchAll();
?>

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
    <h2>Meus Pets</h2>
    <a href="cadastrar_pet.php" class="btn" style="background-color: var(--success-color);">+ Adicionar Novo Pet</a>
</div>

<?php 
if (isset($_GET['msg']) && $_GET['msg'] == 'sucesso') {
    echo "<div style='padding: 10px; background: #dcfce7; color: #166534; margin-bottom: 1rem; border-radius: 4px;'>Pet cadastrado com sucesso!</div>";
}
?>

<div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 1.5rem;">
    <?php if (count($meus_pets) > 0): ?>
        <?php foreach ($meus_pets as $pet): ?>
            <div style="background: #fff; padding: 1.5rem; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); border-left: 4px solid var(--primary-color);">
                <h3 style="margin-bottom: 0.5rem; color: var(--text-color);"><?php echo htmlspecialchars($pet['nome']); ?></h3>
                <p style="color: #64748b; font-size: 0.95rem; margin-bottom: 0.3rem;"><strong>Espécie:</strong> <?php echo htmlspecialchars($pet['especie']); ?></p>
                <p style="color: #64748b; font-size: 0.95rem; margin-bottom: 0.3rem;"><strong>Raça:</strong> <?php echo htmlspecialchars($pet['raca'] ?: 'Não informada'); ?></p>
                <p style="color: #64748b; font-size: 0.95rem; margin-bottom: 0.3rem;"><strong>Sexo:</strong> <?php echo htmlspecialchars($pet['sexo']); ?></p>
                <p style="color: #64748b; font-size: 0.95rem;"><strong>Peso/Cor:</strong> <?php echo htmlspecialchars($pet['peso']) . 'kg / ' . htmlspecialchars($pet['cor']); ?></p>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div style="grid-column: 1 / -1; background: #fff; padding: 2rem; text-align: center; border-radius: 8px; color: #64748b;">
            Você ainda não tem nenhum pet cadastrado. Clique no botão acima para adicionar!
        </div>
    <?php endif; ?>
</div>

<?php require_once '../includes/footer.php'; ?>