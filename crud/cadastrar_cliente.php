<?php
require_once '../includes/header.php';

if (!isset($_SESSION['usuario_id'])) {
    header("Location: " . BASE_URL . "login/login.php");
    exit;
}

$erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Dados do Cliente
    $nome_cliente = trim($_POST['nome_cliente']);
    $telefone = trim($_POST['telefone']);
    $email = trim($_POST['email']);
    $endereco = trim($_POST['endereco']);

    // Dados do Pet
    $nome_pet = trim($_POST['nome_pet']);
    $especie = trim($_POST['especie']);
    $raca = trim($_POST['raca']);
    $sexo = $_POST['sexo'];
    $peso = !empty($_POST['peso']) ? str_replace(',', '.', $_POST['peso']) : null;
    $cor = trim($_POST['cor']);

    if (!empty($nome_cliente) && !empty($telefone) && !empty($nome_pet) && !empty($especie) && !empty($sexo)) {
        try {
            // Inicia a Transação SQL
            $pdo->beginTransaction();

            // 1. Insere o Cliente
            $stmtCliente = $pdo->prepare("INSERT INTO cliente (nome, telefone, email, endereco) VALUES (:nome, :telefone, :email, :endereco)");
            $stmtCliente->execute([
                ':nome' => $nome_cliente,
                ':telefone' => $telefone,
                ':email' => $email,
                ':endereco' => $endereco
            ]);
            
            // Captura o ID do cliente que acabou de ser gerado pelo AUTO_INCREMENT
            $id_cliente_gerado = $pdo->lastInsertId();

            // 2. Insere o Pet vinculado a este Cliente
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

            // Confirma as duas operações no banco de dados
            $pdo->commit();

            header("Location: listar_clientes.php?msg=sucesso");
            exit;
            
        } catch (PDOException $e) {
            // Se algo der errado, desfaz tudo (Rollback) para não deixar dados pela metade
            $pdo->rollBack();
            $erro = "Erro ao realizar o cadastro unificado: " . $e->getMessage();
        }
    } else {
        $erro = "Preencha todos os campos obrigatórios (*) do Tutor e do Pet.";
    }
}
?>

<div style="max-width: 800px; margin: 0 auto; background: #fff; padding: 2rem; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); margin-bottom: 2rem;">
    <h2 style="margin-bottom: 1.5rem; color: var(--primary-color);">Cadastro Unificado: Tutor e Pet</h2>
    
    <?php if ($erro) echo "<div style='padding: 10px; background: #fee2e2; color: #991b1b; margin-bottom: 1rem; border-radius: 4px;'>$erro</div>"; ?>

    <form action="cadastrar_cliente.php" method="POST">
        
        <h3 style="margin-bottom: 1rem; color: #64748b; border-bottom: 2px solid var(--border-color); padding-bottom: 0.5rem;">Dados do Tutor (Cliente)</h3>
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 2rem;">
            <div>
                <label style="font-weight: 500;">Nome Completo *</label>
                <input type="text" name="nome_cliente" required style="width: 100%; padding: 0.5rem; border: 1px solid var(--border-color); border-radius: 4px; margin-top: 0.3rem;">
            </div>
            <div>
                <label style="font-weight: 500;">Telefone *</label>
                <input type="text" name="telefone" required placeholder="(00) 00000-0000" style="width: 100%; padding: 0.5rem; border: 1px solid var(--border-color); border-radius: 4px; margin-top: 0.3rem;">
            </div>
            <div>
                <label style="font-weight: 500;">E-mail</label>
                <input type="email" name="email" style="width: 100%; padding: 0.5rem; border: 1px solid var(--border-color); border-radius: 4px; margin-top: 0.3rem;">
            </div>
            <div>
                <label style="font-weight: 500;">Endereço Completo</label>
                <input type="text" name="endereco" style="width: 100%; padding: 0.5rem; border: 1px solid var(--border-color); border-radius: 4px; margin-top: 0.3rem;">
            </div>
        </div>

        <h3 style="margin-bottom: 1rem; color: #64748b; border-bottom: 2px solid var(--border-color); padding-bottom: 0.5rem;">Dados do Pet</h3>
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 2rem;">
            <div>
                <label style="font-weight: 500;">Nome do Pet *</label>
                <input type="text" name="nome_pet" required style="width: 100%; padding: 0.5rem; border: 1px solid var(--border-color); border-radius: 4px; margin-top: 0.3rem;">
            </div>
            <div>
                <label style="font-weight: 500;">Espécie *</label>
                <input type="text" name="especie" required placeholder="Ex: Cachorro, Gato..." style="width: 100%; padding: 0.5rem; border: 1px solid var(--border-color); border-radius: 4px; margin-top: 0.3rem;">
            </div>
            <div>
                <label style="font-weight: 500;">Raça</label>
                <input type="text" name="raca" style="width: 100%; padding: 0.5rem; border: 1px solid var(--border-color); border-radius: 4px; margin-top: 0.3rem;">
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
                <input type="number" step="0.01" name="peso" placeholder="Ex: 12.5" style="width: 100%; padding: 0.5rem; border: 1px solid var(--border-color); border-radius: 4px; margin-top: 0.3rem;">
            </div>
            <div>
                <label style="font-weight: 500;">Cor</label>
                <input type="text" name="cor" style="width: 100%; padding: 0.5rem; border: 1px solid var(--border-color); border-radius: 4px; margin-top: 0.3rem;">
            </div>
        </div>
        
        <div style="display: flex; gap: 1rem; justify-content: flex-end;">
            <a href="listar_clientes.php" class="btn" style="background-color: #64748b;">Cancelar</a>
            <button type="submit" class="btn" style="background-color: var(--success-color); padding: 0.5rem 2rem;">Salvar Cadastro Completo</button>
        </div>
    </form>
</div>

<?php require_once '../includes/footer.php'; ?>