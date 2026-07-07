<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/conexao.php';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Gestão - PetShop</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>css/style.css">
</head>
<body>

<header>
    <a href="<?php echo BASE_URL; ?>index.php" class="logo">PetShop Gestão</a>
    <nav>
        <ul>
            <li><a href="<?php echo BASE_URL; ?>index.php">Início</a></li>
            
            <?php if (isset($_SESSION['usuario_id'])): ?>
                
                <?php if ($_SESSION['usuario_perfil'] === 'administrador'): ?>
                    <li><a href="<?php echo BASE_URL; ?>usuarios/cadastrar_usuario.php" style="color: var(--danger-color); font-weight: bold;">Usuários</a></li>
                <?php endif; ?>

                <li><a href="<?php echo BASE_URL; ?>crud/agendamentos.php">Agenda</a></li>
                <li><a href="<?php echo BASE_URL; ?>crud/listar_clientes.php">Clientes</a></li>
                <li><a href="<?php echo BASE_URL; ?>consultas/pesquisa.php">Pesquisa</a></li>
                <li><a href="<?php echo BASE_URL; ?>contato/contato.php">Contato</a></li>
                <li><a href="<?php echo BASE_URL; ?>login/logout.php" class="btn btn-danger">Sair</a></li>
            <?php else: ?>
                <li><a href="<?php echo BASE_URL; ?>login/login.php" class="btn">Entrar</a></li>
            <?php endif; ?>
        </ul>
    </nav>
</header>

<main>