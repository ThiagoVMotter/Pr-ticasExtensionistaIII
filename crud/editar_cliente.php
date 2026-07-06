<?php
require_once '../includes/header.php';

if (!isset($_SESSION['usuario_id'])) {
    header("Location: " . BASE_URL . "login/login.php");
    exit;
}

$erro = '';
$id = $_GET['id'] ?? null;

if (!$id) {
    header("Location: listar_clientes.php");
    exit;
}

// Busca os dados atuais do cliente para preencher o formulário
$stmt = $pdo->prepare("SELECT * FROM cliente WHERE id_cliente = :id");
$stmt->execute([':id' => $id]);
$cliente = $stmt->fetch();

if (!$cliente) {
    echo "<div style='text-align: center; margin-top: 2rem;'>Cliente não encontrado. <a href='listar_clientes.php'>Voltar</a></div>";
    require_once '../includes/footer.php';
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome']);
    $telefone = trim($_POST['telefone']);
    $email = trim($_POST['email']);
    $endereco = trim($_POST['endereco']);

    if (!empty($nome) && !empty($telefone)) {
        try {
            $stmt = $pdo->prepare("UPDATE cliente SET nome = :nome, telefone = :telefone, email = :email, endereco = :endereco WHERE id_cliente = :id");
            $stmt->execute([
                ':nome' => $nome,
                ':telefone' => $telefone,
                ':email' => $email,
                ':endereco' => $endereco,
                ':id' => $id
            ]);
            header("Location: listar_clientes.php?msg=sucesso");
            exit;
        } catch (PDOException $e) {
            $erro = "Erro ao atualizar: " . $e->getMessage();
        }
    } else {
        $erro = "Os campos Nome e Telefone são obrigatórios.";
    }
}
?>

<div style="max-width: 600px; margin: 0 auto; background: #fff; padding: 2rem; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
    <h2 style="margin-bottom: 1.5rem; color: var(--primary-color);">Editar Cliente</h2>
    
    <?php if ($erro) echo "<div style='padding: 10px; background: #fee2e2; color: #991b1b; margin-bottom: 1rem; border-radius: 4px;'>$erro</div>"; ?>

    <form action="editar_cliente.php?id=<?php echo $id; ?>" method="POST" style="display: flex; flex-direction: column; gap: 1rem;">
        <div>
            <label style="font-weight: 500;">Nome Completo *</label>
            <input type="text" name="nome" value="<?php echo htmlspecialchars($cliente['nome']); ?>" required style="width: 100%; padding: 0.5rem; border: 1px solid var(--border-color); border-radius: 4px; margin-top: 0.3rem;">
        </div>
        <div>
            <label style="font-weight: 500;">Telefone *</label>
            <input type="text" name="telefone" value="<?php echo htmlspecialchars($cliente['telefone']); ?>" required style="width: 100%; padding: 0.5rem; border: 1px solid var(--border-color); border-radius: 4px; margin-top: 0.3rem;">
        </div>
        <div>
            <label style="font-weight: 500;">E-mail</label>
            <input type="email" name="email" value="<?php echo htmlspecialchars($cliente['email']); ?>" style="width: 100%; padding: 0.5rem; border: 1px solid var(--border-color); border-radius: 4px; margin-top: 0.3rem;">
        </div>
        <div>
            <label style="font-weight: 500;">Endereço Completo</label>
            <input type="text" name="endereco" value="<?php echo htmlspecialchars($cliente['endereco']); ?>" style="width: 100%; padding: 0.5rem; border: 1px solid var(--border-color); border-radius: 4px; margin-top: 0.3rem;">
        </div>
        
        <div style="display: flex; gap: 1rem; margin-top: 1rem;">
            <button type="submit" class="btn" style="background-color: var(--success-color);">Atualizar Cliente</button>
            <a href="listar_clientes.php" class="btn" style="background-color: #64748b;">Cancelar</a>
        </div>
    </form>
</div>

<?php require_once '../includes/footer.php'; ?>