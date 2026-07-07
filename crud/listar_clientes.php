<?php
require_once '../includes/header.php';

// Proteção da página: bloqueia se não estiver logado OU se for um cliente tentando acessar
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_perfil'] === 'cliente') {
    header("Location: " . BASE_URL . "index.php");
    exit;
}

// Busca todos os clientes ordenados por nome
$stmt = $pdo->query("SELECT * FROM cliente ORDER BY nome ASC");
$clientes = $stmt->fetchAll();
?>

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
    <h2>Gestão de Clientes</h2>
    <a href="cadastrar_cliente.php" class="btn" style="background-color: var(--success-color);">+ Novo Cliente</a>
</div>

<?php 
// Exibição de mensagens de retorno (sucesso ou erro)
if (isset($_GET['msg'])) {
    if ($_GET['msg'] == 'sucesso') echo "<div style='padding: 10px; background: #dcfce7; color: #166534; margin-bottom: 1rem; border-radius: 4px;'>Operação realizada com sucesso!</div>";
    
    // Nova mensagem de erro de chave estrangeira (agora foca em Agendamentos e Vendas)
    if ($_GET['msg'] == 'erro_fk') echo "<div style='padding: 10px; background: #fee2e2; color: #991b1b; margin-bottom: 1rem; border-radius: 4px;'>Erro: Não é possível excluir este cliente pois existem Agendamentos ou Vendas vinculadas a ele ou aos seus pets no histórico do sistema.</div>";
    
    if ($_GET['msg'] == 'erro') echo "<div style='padding: 10px; background: #fee2e2; color: #991b1b; margin-bottom: 1rem; border-radius: 4px;'>Erro ao realizar a operação.</div>";
}
?>

<div style="overflow-x: auto; background: #fff; padding: 1rem; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
    <table style="width: 100%; border-collapse: collapse; text-align: left;">
        <thead>
            <tr style="border-bottom: 2px solid var(--border-color);">
                <th style="padding: 10px;">ID</th>
                <th style="padding: 10px;">Nome</th>
                <th style="padding: 10px;">Telefone</th>
                <th style="padding: 10px;">E-mail</th>
                <th style="padding: 10px;">Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($clientes) > 0): ?>
                <?php foreach ($clientes as $c): ?>
                    <tr style="border-bottom: 1px solid var(--border-color);">
                        <td style="padding: 10px;"><?php echo htmlspecialchars($c['id_cliente']); ?></td>
                        <td style="padding: 10px;"><?php echo htmlspecialchars($c['nome']); ?></td>
                        <td style="padding: 10px;"><?php echo htmlspecialchars($c['telefone']); ?></td>
                        <td style="padding: 10px;"><?php echo htmlspecialchars($c['email']); ?></td>
                        <td style="padding: 10px;">
                            <a href="editar_cliente.php?id=<?php echo $c['id_cliente']; ?>" class="btn" style="padding: 0.3rem 0.6rem; font-size: 0.9rem;">Editar</a>
                            <a href="excluir_cliente.php?id=<?php echo $c['id_cliente']; ?>" class="btn btn-danger" style="padding: 0.3rem 0.6rem; font-size: 0.9rem;" onclick="return confirm('Tem certeza que deseja excluir o cliente <?php echo htmlspecialchars($c['nome']); ?> e todos os seus pets?');">Excluir</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5" style="padding: 10px; text-align: center; color: #64748b;">Nenhum cliente cadastrado.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php require_once '../includes/footer.php'; ?>