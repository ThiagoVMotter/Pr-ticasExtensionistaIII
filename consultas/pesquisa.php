<?php
require_once '../includes/header.php';

if (!isset($_SESSION['usuario_id'])) {
    header("Location: " . BASE_URL . "login/login.php");
    exit;
}

$resultados = [];
$termo_pesquisa = $_GET['q'] ?? '';

if (!empty($termo_pesquisa)) {
    try {
        $sql = "SELECT p.id_pet, p.nome AS nome_pet, p.especie, p.raca, 
                       c.nome AS nome_cliente, c.telefone 
                FROM pet p
                INNER JOIN cliente c ON p.id_cliente = c.id_cliente
                WHERE p.nome LIKE :q OR c.nome LIKE :q
                ORDER BY c.nome ASC, p.nome ASC";
                
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':q', '%' . $termo_pesquisa . '%');
        $stmt->execute();
        
        $resultados = $stmt->fetchAll();
    } catch (PDOException $e) {
        $erro = "Erro ao realizar a pesquisa: " . $e->getMessage();
    }
}
?>

<div style="background: #fff; padding: 2rem; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); margin-bottom: 2rem;">
    <h2 style="margin-bottom: 1.5rem; color: var(--primary-color);">Consulta de Pets e Tutores</h2>
    
    <form action="pesquisa.php" method="GET" style="display: flex; gap: 1rem; align-items: center;">
        <div style="flex: 1;">
            <input type="text" name="q" value="<?php echo htmlspecialchars($termo_pesquisa); ?>" placeholder="Digite o nome do cliente ou do pet..." style="width: 100%; padding: 0.75rem; border: 1px solid var(--border-color); border-radius: 4px; font-size: 1rem;">
        </div>
        <button type="submit" class="btn" style="padding: 0.75rem 2rem; background-color: var(--success-color);">Pesquisar</button>
        
        <?php if (!empty($termo_pesquisa)): ?>
            <a href="pesquisa.php" class="btn" style="background-color: #64748b; padding: 0.75rem 1rem;">Limpar Busca</a>
        <?php endif; ?>
    </form>
</div>

<?php if (isset($erro)): ?>
    <div style="padding: 10px; background: #fee2e2; color: #991b1b; margin-bottom: 1rem; border-radius: 4px;">
        <?php echo $erro; ?>
    </div>
<?php endif; ?>

<?php if (!empty($termo_pesquisa)): ?>
    <div style="overflow-x: auto; background: #fff; padding: 1rem; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
        <h3 style="margin-bottom: 1rem; font-size: 1.1rem; color: #64748b;">
            Resultados para: "<?php echo htmlspecialchars($termo_pesquisa); ?>"
        </h3>
        
        <table style="width: 100%; border-collapse: collapse; text-align: left;">
            <thead>
                <tr style="border-bottom: 2px solid var(--border-color); background-color: var(--bg-color);">
                    <th style="padding: 12px;">Pet</th>
                    <th style="padding: 12px;">Espécie / Raça</th>
                    <th style="padding: 12px;">Tutor (Cliente)</th>
                    <th style="padding: 12px;">Telefone</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($resultados) > 0): ?>
                    <?php foreach ($resultados as $r): ?>
                        <tr style="border-bottom: 1px solid var(--border-color);">
                            <td style="padding: 12px; font-weight: 500;"><?php echo htmlspecialchars($r['nome_pet']); ?></td>
                            <td style="padding: 12px;"><?php echo htmlspecialchars($r['especie'] . ' - ' . $r['raca']); ?></td>
                            <td style="padding: 12px;"><?php echo htmlspecialchars($r['nome_cliente']); ?></td>
                            <td style="padding: 12px;"><?php echo htmlspecialchars($r['telefone']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" style="padding: 20px; text-align: center; color: #64748b; font-style: italic;">
                            Nenhum registro encontrado para a sua busca.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>

<?php require_once '../includes/footer.php'; ?>