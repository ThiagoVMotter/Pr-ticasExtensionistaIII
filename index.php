<?php require_once 'includes/header.php'; ?>

<div style="text-align: center; margin-bottom: 2rem;">
    <h1 style="color: var(--primary-color); margin-bottom: 0.5rem;">Sistema de Gestão - PetShop</h1>
    <p style="font-size: 1.1rem; color: #64748b;">
        Solução completa para agendamento de serviços, gestão de clientes, pets e controle de estoque.
    </p>
</div>

<?php if (isset($_SESSION['usuario_id'])): ?>
    <div style="text-align: center; margin-bottom: 2rem;">
        <h3>Bem-vindo(a), <?php echo htmlspecialchars($_SESSION['usuario_nome']); ?>!</h3>
        <p>Selecione uma das opções abaixo para gerenciar o sistema:</p>
    </div>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem; padding: 1rem;">
        
        <div style="background: #fff; padding: 1.5rem; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); text-align: center; border-top: 4px solid #8b5cf6;">
            <h3 style="margin-bottom: 1rem;">Agenda de Serviços</h3>
            <p style="margin-bottom: 1.5rem; font-size: 0.9rem; color: #64748b;">Controle os horários, status de atendimentos e serviços marcados.</p>
            <a href="<?php echo BASE_URL; ?>crud/agendamentos.php" class="btn" style="width: 100%; background-color: #8b5cf6;">Acessar Agenda</a>
        </div>

        <div style="background: #fff; padding: 1.5rem; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); text-align: center; border-top: 4px solid var(--primary-color);">
            <h3 style="margin-bottom: 1rem;">Gestão de Clientes</h3>
            <p style="margin-bottom: 1.5rem; font-size: 0.9rem; color: #64748b;">Cadastre, edite, visualize e exclua os tutores dos pets.</p>
            <a href="<?php echo BASE_URL; ?>crud/listar_clientes.php" class="btn" style="width: 100%;">Acessar Módulo</a>
        </div>

        <div style="background: #fff; padding: 1.5rem; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); text-align: center; border-top: 4px solid var(--success-color);">
            <h3 style="margin-bottom: 1rem;">Consulta Rápida</h3>
            <p style="margin-bottom: 1.5rem; font-size: 0.9rem; color: #64748b;">Pesquise rapidamente por clientes ou pets cadastrados.</p>
            <a href="<?php echo BASE_URL; ?>consultas/pesquisa.php" class="btn" style="width: 100%; background-color: var(--success-color);">Realizar Busca</a>
        </div>

        <div style="background: #fff; padding: 1.5rem; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); text-align: center; border-top: 4px solid #f59e0b;">
            <h3 style="margin-bottom: 1rem;">Fale Conosco</h3>
            <p style="margin-bottom: 1.5rem; font-size: 0.9rem; color: #64748b;">Formulário de contato e suporte integrado ao sistema.</p>
            <a href="<?php echo BASE_URL; ?>contato/contato.php" class="btn" style="width: 100%; background-color: #f59e0b;">Abrir Contato</a>
        </div>

    </div>

<?php else: ?>
    <div style="background: #fff; padding: 3rem 2rem; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); text-align: center; max-width: 600px; margin: 0 auto;">
        <h2 style="margin-bottom: 1rem; color: var(--text-color);">Acesso Restrito</h2>
        <p style="margin-bottom: 2rem; color: #64748b;">Para acessar as funcionalidades de gestão, agendamentos e controle do PetShop, por favor, realize a sua autenticação no sistema.</p>
        <a href="<?php echo BASE_URL; ?>login/login.php" class="btn" style="font-size: 1.1rem; padding: 0.75rem 2rem;">Fazer Login</a>
    </div>
<?php endif; ?>

<?php require_once 'includes/footer.php'; ?>