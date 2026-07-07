<?php
require_once '../includes/header.php';

if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_perfil'] !== 'administrador') {
    header("Location: " . BASE_URL . "index.php");
    exit;
}

$erro = '';
$sucesso = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome']);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $senha = $_POST['senha'];
    $perfil = $_POST['perfil'];

    if (!empty($nome) && !empty($email) && !empty($senha) && !empty($perfil)) {
        try {
            $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO funcionario (nome, email, senha, perfil) VALUES (:nome, :email, :senha, :perfil)");
            $stmt->execute([
                ':nome' => $nome,
                ':email' => $email,
                ':senha' => $senha_hash,
                ':perfil' => $perfil
            ]);
            $sucesso = "Membro da equipe cadastrado com sucesso!";
        } catch (PDOException $e) {
            if ($e->getCode() == '23000' || $e->errorInfo[1] == 1062) {
                $erro = "Este e-mail já está em uso.";
            } else {
                $erro = "Erro ao cadastrar: " . $e->getMessage();
            }
        }
    } else {
        $erro = "Todos os campos são obrigatórios.";
    }
}

$stmtUsuarios = $pdo->query("SELECT id_funcionario, nome, email, perfil FROM funcionario ORDER BY nome ASC");
$usuarios = $stmtUsuarios->fetchAll();
?>

<div style="max-width: 900px; margin: 0 auto; margin-bottom: 2rem;">
    
    <?php 
    if (isset($_GET['msg'])) {
        if ($_GET['msg'] == 'sucesso_edit') echo "<div style='padding: 10px; background: #dcfce7; color: #166534; margin-bottom: 1rem; border-radius: 4px; text-align:center;'>Usuário atualizado com sucesso!</div>";
        if ($_GET['msg'] == 'sucesso_del') echo "<div style='padding: 10px; background: #dcfce7; color: #166534; margin-bottom: 1rem; border-radius: 4px; text-align:center;'>Usuário removido do sistema!</div>";
        if ($_GET['msg'] == 'erro_self') echo "<div style='padding: 10px; background: #fee2e2; color: #991b1b; margin-bottom: 1rem; border-radius: 4px; text-align:center;'>Ação negada: Você não pode excluir o seu próprio usuário enquanto está logado.</div>";
        if ($_GET['msg'] == 'erro_fk') echo "<div style='padding: 10px; background: #fee2e2; color: #991b1b; margin-bottom: 1rem; border-radius: 4px; text-align:center;'>Erro: Este funcionário possui agendamentos vinculados a ele. Reatribua os serviços antes de excluí-lo.</div>";
        if ($_GET['msg'] == 'erro') echo "<div style='padding: 10px; background: #fee2e2; color: #991b1b; margin-bottom: 1rem; border-radius: 4px; text-align:center;'>Ocorreu um erro na operação.</div>";
    }
    ?>

    <div style="background: #fff; padding: 2rem; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); margin-bottom: 2rem;">
        <h2 style="margin-bottom: 1.5rem; color: var(--danger-color); text-align: center;">Cadastrar Nova Conta de Acesso</h2>
        
        <?php if ($erro) echo "<div style='padding: 10px; background: #fee2e2; color: #991b1b; margin-bottom: 1rem; border-radius: 4px;'>$erro</div>"; ?>
        <?php if ($sucesso) echo "<div style='padding: 10px; background: #dcfce7; color: #166534; margin-bottom: 1rem; border-radius: 4px;'>$sucesso</div>"; ?>

        <form action="cadastrar_usuario.php" method="POST" style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
            <div>
                <label style="font-weight: 500; display: block; margin-bottom: 0.3rem;">Nome do Profissional *</label>
                <input type="text" name="nome" required style="width: 100%; padding: 0.5rem; border: 1px solid var(--border-color); border-radius: 4px;">
            </div>
            <div>
                <label style="font-weight: 500; display: block; margin-bottom: 0.3rem;">E-mail de Login *</label>
                <input type="email" name="email" required style="width: 100%; padding: 0.5rem; border: 1px solid var(--border-color); border-radius: 4px;">
            </div>
            <div>
                <label style="font-weight: 500; display: block; margin-bottom: 0.3rem;">Senha Temporária *</label>
                <input type="password" name="senha" required style="width: 100%; padding: 0.5rem; border: 1px solid var(--border-color); border-radius: 4px;">
            </div>
            <div>
                <label style="font-weight: 500; display: block; margin-bottom: 0.3rem;">Nível de Acesso *</label>
                <select name="perfil" required style="width: 100%; padding: 0.5rem; border: 1px solid var(--border-color); border-radius: 4px;">
                    <option value="">Selecione...</option>
                    <option value="funcionario">Funcionário (Padrão)</option>
                    <option value="administrador">Administrador (Acesso Total)</option>
                </select>
            </div>
            <div style="grid-column: span 2; margin-top: 1rem;">
                <button type="submit" class="btn btn-danger" style="width: 100%;">Salvar Usuário</button>
            </div>
        </form>
    </div>

    <div style="background: #fff; padding: 2rem; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
        <h3 style="margin-bottom: 1.5rem; color: var(--text-color); border-bottom: 2px solid var(--border-color); padding-bottom: 0.5rem;">Membros da Equipe Cadastrados</h3>
        
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; text-align: left;">
                <thead>
                    <tr style="border-bottom: 2px solid var(--border-color); background-color: var(--bg-color);">
                        <th style="padding: 12px;">Nome</th>
                        <th style="padding: 12px;">E-mail</th>
                        <th style="padding: 12px;">Perfil</th>
                        <th style="padding: 12px; text-align: center;">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($usuarios as $u): ?>
                        <tr style="border-bottom: 1px solid var(--border-color);">
                            <td style="padding: 12px; font-weight: 500;"><?php echo htmlspecialchars($u['nome']); ?></td>
                            <td style="padding: 12px;"><?php echo htmlspecialchars($u['email']); ?></td>
                            <td style="padding: 12px;">
                                <?php 
                                    $corBadge = $u['perfil'] === 'administrador' ? 'var(--danger-color)' : '#64748b';
                                ?>
                                <span style="background-color: <?php echo $corBadge; ?>; color: white; padding: 4px 8px; border-radius: 4px; font-size: 0.85rem;">
                                    <?php echo ucfirst(htmlspecialchars($u['perfil'])); ?>
                                </span>
                            </td>
                            <td style="padding: 12px; text-align: center;">
                                <a href="editar_usuario.php?id=<?php echo $u['id_funcionario']; ?>" class="btn" style="padding: 0.3rem 0.6rem; font-size: 0.85rem; background-color: var(--primary-color);">Editar</a>
                                
                                <?php if ($u['id_funcionario'] !== $_SESSION['usuario_id']): ?>
                                    <a href="excluir_usuario.php?id=<?php echo $u['id_funcionario']; ?>" class="btn btn-danger" style="padding: 0.3rem 0.6rem; font-size: 0.85rem;" onclick="return confirm('ATENÇÃO: Deseja realmente excluir o acesso de <?php echo htmlspecialchars($u['nome']); ?>?');">Excluir</a>
                                <?php else: ?>
                                    <button disabled class="btn" style="padding: 0.3rem 0.6rem; font-size: 0.85rem; background-color: #cbd5e1; cursor: not-allowed;" title="Você não pode excluir a si mesmo">Excluir</button>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>