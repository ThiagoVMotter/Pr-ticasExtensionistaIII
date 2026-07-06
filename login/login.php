<?php
require_once '../includes/header.php';

if (isset($_SESSION['usuario_id'])) {
    header("Location: " . BASE_URL . "index.php");
    exit;
}

$erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $senha = $_POST['senha'] ?? '';

    if (!empty($email) && !empty($senha)) {
        try {
            $stmt = $pdo->prepare("SELECT id_funcionario, nome, senha, perfil FROM funcionario WHERE email = :email");
            $stmt->bindValue(':email', $email);
            $stmt->execute();
            
            $usuario = $stmt->fetch();

            if ($usuario && password_verify($senha, $usuario['senha'])) {
                $_SESSION['usuario_id'] = $usuario['id_funcionario'];
                $_SESSION['usuario_nome'] = $usuario['nome'];
                $_SESSION['usuario_perfil'] = $usuario['perfil'];
                
                header("Location: " . BASE_URL . "index.php");
                exit;
            } else {
                $erro = "E-mail ou senha incorretos.";
            }
        } catch (PDOException $e) {
            $erro = "Erro ao processar o login. Tente novamente mais tarde.";
        }
    } else {
        $erro = "Por favor, preencha todos os campos.";
    }
}
?>

<div style="max-width: 400px; margin: 2rem auto; padding: 2rem; background: #fff; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
    <h2 style="text-align: center; margin-bottom: 1.5rem; color: var(--primary-color);">Acesso Restrito</h2>
    
    <?php if ($erro): ?>
        <div style="background: #fee2e2; color: var(--danger-color); padding: 0.75rem; border-radius: 4px; margin-bottom: 1rem; text-align: center;">
            <?php echo $erro; ?>
        </div>
    <?php endif; ?>

    <form action="login.php" method="POST" style="display: flex; flex-direction: column; gap: 1rem;">
        <div>
            <label for="email" style="display: block; margin-bottom: 0.5rem; font-weight: 500;">E-mail</label>
            <input type="email" name="email" id="email" required style="width: 100%; padding: 0.5rem; border: 1px solid var(--border-color); border-radius: 4px;">
        </div>
        
        <div>
            <label for="senha" style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Senha</label>
            <input type="password" name="senha" id="senha" required style="width: 100%; padding: 0.5rem; border: 1px solid var(--border-color); border-radius: 4px;">
        </div>
        
        <button type="submit" class="btn" style="margin-top: 1rem; width: 100%;">Entrar no Sistema</button>
    </form>
</div>

<?php require_once '../includes/footer.php'; ?>