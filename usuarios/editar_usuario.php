<?php
require_once '../includes/header.php';

if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_perfil'] !== 'administrador') {
    header("Location: " . BASE_URL . "index.php");
    exit;
}

$erro = '';
$id = $_GET['id'] ?? null;

if (!$id) {
    header("Location: cadastrar_usuario.php");
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM funcionario WHERE id_funcionario = :id");
$stmt->execute([':id' => $id]);
$usuario = $stmt->fetch();

if (!$usuario) {
    header("Location: cadastrar_usuario.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome']);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $senha_nova = trim($_POST['senha']);
    $perfil = $_POST['perfil'];

    if (!empty($nome) && !empty($email) && !empty($perfil)) {
        try {
            if (!empty($senha_nova)) {
                $senha_hash = password_hash($senha_nova, PASSWORD_DEFAULT);
                $sql = "UPDATE funcionario SET nome = :nome, email = :email, senha = :senha, perfil = :perfil WHERE id_funcionario = :id";
                $parametros = [':nome' => $nome, ':email' => $email, ':senha' => $senha_hash, ':perfil' => $perfil, ':id' => $id];
            } else {
                $sql = "UPDATE funcionario SET nome = :nome, email = :email, perfil = :perfil WHERE id_funcionario = :id";
                $parametros = [':nome' => $nome, ':email' => $email, ':perfil' => $perfil, ':id' => $id];
            }

            $stmt = $pdo->prepare($sql);
            $stmt->execute($parametros);
            
            header("Location: cadastrar_usuario.php?msg=sucesso_edit");
            exit;
            
        } catch (PDOException $e) {
            if ($e->getCode() == '23000' || $e->errorInfo[1] == 1062) {
                $erro = "Este e-mail já está sendo utilizado por outro usuário.";
            } else {
                $erro = "Erro ao atualizar: " . $e->getMessage();
            }
        }
    } else {
        $erro = "Nome, E-mail e Perfil são obrigatórios.";
    }
}
?>

<div style="max-width: 600px; margin: 0 auto; background: #fff; padding: 2rem; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
    <h2 style="margin-bottom: 1.5rem; color: var(--danger-color);">Editar Membro da Equipe</h2>
    
    <?php if ($erro) echo "<div style='padding: 10px; background: #fee2e2; color: #991b1b; margin-bottom: 1rem; border-radius: 4px;'>$erro</div>"; ?>

    <form action="editar_usuario.php?id=<?php echo $id; ?>" method="POST" style="display: flex; flex-direction: column; gap: 1rem;">
        <div>
            <label style="font-weight: 500;">Nome do Profissional *</label>
            <input type="text" name="nome" value="<?php echo htmlspecialchars($usuario['nome']); ?>" required style="width: 100%; padding: 0.5rem; border: 1px solid var(--border-color); border-radius: 4px;">
        </div>
        <div>
            <label style="font-weight: 500;">E-mail de Login *</label>
            <input type="email" name="email" value="<?php echo htmlspecialchars($usuario['email']); ?>" required style="width: 100%; padding: 0.5rem; border: 1px solid var(--border-color); border-radius: 4px;">
        </div>
        <div>
            <label style="font-weight: 500;">Nova Senha</label>
            <input type="password" name="senha" placeholder="Deixe em branco para manter a senha atual" style="width: 100%; padding: 0.5rem; border: 1px solid var(--border-color); border-radius: 4px;">
        </div>
        <div>
            <label style="font-weight: 500;">Nível de Acesso *</label>
            <select name="perfil" required style="width: 100%; padding: 0.5rem; border: 1px solid var(--border-color); border-radius: 4px;">
                <option value="funcionario" <?php if($usuario['perfil'] == 'funcionario') echo 'selected'; ?>>Funcionário (Padrão)</option>
                <option value="administrador" <?php if($usuario['perfil'] == 'administrador') echo 'selected'; ?>>Administrador (Acesso Total)</option>
            </select>
        </div>
        
        <div style="display: flex; gap: 1rem; margin-top: 1rem;">
            <button type="submit" class="btn btn-danger">Salvar Alterações</button>
            <a href="cadastrar_usuario.php" class="btn" style="background-color: #64748b;">Voltar</a>
        </div>
    </form>
</div>

<?php require_once '../includes/footer.php'; ?>