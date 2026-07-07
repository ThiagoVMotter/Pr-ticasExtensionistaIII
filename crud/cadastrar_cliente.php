<?php
require_once '../includes/header.php';

// Bloqueio duplo: Se não estiver logado OU for um cliente tentando acessar, é bloqueado.
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_perfil'] === 'cliente') {
    header("Location: " . BASE_URL . "index.php");
    exit;
}

$erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Dados do Tutor
    $nome_cliente = trim($_POST['nome_cliente']);
    $telefone = trim($_POST['telefone']);
    $email = trim($_POST['email']);
    $senha_cliente = trim($_POST['senha_cliente']); // Novo campo
    $endereco = trim($_POST['endereco']);

    // Dados do Pet
    $nome_pet = trim($_POST['nome_pet']);
    $especie = trim($_POST['especie']);
    $raca = trim($_POST['raca']);
    $sexo = $_POST['sexo'];
    $peso = !empty($_POST['peso']) ? str_replace(',', '.', $_POST['peso']) : null;
    $cor = trim($_POST['cor']);

    if (!empty($nome_cliente) && !empty($telefone) && !empty($senha_cliente) && !empty($nome_pet)) {
        try {
            $pdo->beginTransaction();

            $senha_hash = password_hash($senha_cliente, PASSWORD_DEFAULT);

            // Inserção com a senha
            $stmtCliente = $pdo->prepare("INSERT INTO cliente (nome, telefone, email, senha, endereco) VALUES (:nome, :telefone, :email, :senha, :endereco)");
            $stmtCliente->execute([
                ':nome' => $nome_cliente,
                ':telefone' => $telefone,
                ':email' => $email,
                ':senha' => $senha_hash,
                ':endereco' => $endereco
            ]);
            
            $id_cliente_gerado = $pdo->lastInsertId();

            $stmtPet = $pdo->prepare("INSERT INTO pet (id_cliente, nome, especie, raca, sexo, peso, cor) VALUES (:id_cliente, :nome, :especie, :raca, :sexo, :peso, :cor)");
            $stmtPet->execute([
                ':id_cliente' => $id_cliente_gerado,
                ':nome' => $nome_pet,
                ':especie' => $especie,
                ':raca' => $raca,
                ':sexo' => $sexo,
                ':peso' => $peso,
                ':cor' => $cor
            ]);

            $pdo->commit();
            header("Location: listar_clientes.php?msg=sucesso");
            exit;
            
        } catch (PDOException $e) {
            $pdo->rollBack();
            $erro = "Erro ao cadastrar: " . $e->getMessage();
        }
    } else {
        $erro = "Preencha todos os campos obrigatórios (*).";
    }
}
?>

<div style="max-width: 800px; margin: 0 auto; background: #fff; padding: 2rem; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); margin-bottom: 2rem;">
    <h2 style="margin-bottom: 1.5rem; color: var(--primary-color);">Cadastro Unificado</h2>
    
    <?php if ($erro) echo "<div style='padding: 10px; background: #fee2e2; color: #991b1b; margin-bottom: 1rem; border-radius: 4px;'>$erro</div>"; ?>

    <form action="cadastrar_cliente.php" method="POST">
        <h3 style="margin-bottom: 1rem; color: #64748b; border-bottom: 2px solid var(--border-color); padding-bottom: 0.5rem;">Dados do Tutor (Cliente)</h3>
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 2rem;">
            <div><label>Nome Completo *</label><input type="text" name="nome_cliente" required style="width:100%; padding:0.5rem;"></div>
            <div><label>Telefone *</label><input type="text" name="telefone" required style="width:100%; padding:0.5rem;"></div>
            <div><label>E-mail de Login *</label><input type="email" name="email" required style="width:100%; padding:0.5rem;"></div>
            <div><label>Senha de Acesso *</label><input type="password" name="senha_cliente" required style="width:100%; padding:0.5rem;"></div>
            <div style="grid-column: span 2;"><label>Endereço Completo</label><input type="text" name="endereco" style="width:100%; padding:0.5rem;"></div>
        </div>

        <h3 style="margin-bottom: 1rem; color: #64748b; border-bottom: 2px solid var(--border-color); padding-bottom: 0.5rem;">Dados do Pet</h3>
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 2rem;">
            <div><label>Nome do Pet *</label><input type="text" name="nome_pet" required style="width:100%; padding:0.5rem;"></div>
            <div><label>Espécie *</label><input type="text" name="especie" required style="width:100%; padding:0.5rem;"></div>
            <div><label>Raça</label><input type="text" name="raca" style="width:100%; padding:0.5rem;"></div>
            <div><label>Sexo *</label>
                <select name="sexo" required style="width:100%; padding:0.5rem;">
                    <option value="">Selecione...</option>
                    <option value="Macho">Macho</option>
                    <option value="Femea">Fêmea</option>
                </select>
            </div>
            <div><label>Peso (kg)</label><input type="number" step="0.01" name="peso" style="width:100%; padding:0.5rem;"></div>
            <div><label>Cor</label><input type="text" name="cor" style="width:100%; padding:0.5rem;"></div>
        </div>
        
        <div style="display: flex; justify-content: flex-end;"><button type="submit" class="btn" style="background-color: var(--success-color);">Salvar Cadastro Completo</button></div>
    </form>
</div>
<?php require_once '../includes/footer.php'; ?>