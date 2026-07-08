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
        <p>Seu perfil de acesso atual é: <strong><?php echo ucfirst($_SESSION['usuario_perfil']); ?></strong>.</p>
    </div>
    
    <?php if ($_SESSION['usuario_perfil'] === 'cliente'): ?>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem; padding: 1rem;">
            
            <div style="background: #fff; padding: 1.5rem; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); text-align: center; border-top: 4px solid var(--primary-color);">
                <h3 style="margin-bottom: 1rem;">Meus Pets</h3>
                <p style="margin-bottom: 1.5rem; font-size: 0.9rem; color: #64748b;">Cadastre seus animais e gerencie o perfil de cada um deles.</p>
                <a href="<?php echo BASE_URL; ?>area_cliente/meus_pets.php" class="btn" style="width: 100%;">Acessar Pets</a>
            </div>
            
            <div style="background: #fff; padding: 1.5rem; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); text-align: center; border-top: 4px solid #8b5cf6;">
                <h3 style="margin-bottom: 1rem;">Meus Agendamentos</h3>
                <p style="margin-bottom: 1.5rem; font-size: 0.9rem; color: #64748b;">Marque serviços, banhos ou consultas para os seus pets.</p>
                <a href="<?php echo BASE_URL; ?>area_cliente/meus_agendamentos.php" class="btn" style="width: 100%; background-color: #8b5cf6;">Agendar Serviço</a>
            </div>

            <div style="background: #fff; padding: 1.5rem; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); text-align: center; border-top: 4px solid #f59e0b;">
                <h3 style="margin-bottom: 1rem;">Fale Conosco</h3>
                <p style="margin-bottom: 1.5rem; font-size: 0.9rem; color: #64748b;">Envie dúvidas ou solicitações diretamente para a nossa equipe.</p>
                <a href="<?php echo BASE_URL; ?>contato/contato.php" class="btn" style="width: 100%; background-color: #f59e0b;">Enviar Mensagem</a>
            </div>
        </div>

    <?php else: ?>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem; padding: 1rem;">
            
            <?php if ($_SESSION['usuario_perfil'] === 'administrador'): ?>
                <div style="background: #fff; padding: 1.5rem; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); text-align: center; border-top: 4px solid var(--danger-color);">
                    <h3 style="margin-bottom: 1rem;">Gestão de Acessos</h3>
                    <p style="margin-bottom: 1.5rem; font-size: 0.9rem; color: #64748b;">Cadastre a equipe e defina as permissões no sistema.</p>
                    <a href="<?php echo BASE_URL; ?>usuarios/cadastrar_usuario.php" class="btn btn-danger" style="width: 100%;">Gerenciar Equipe</a>
                </div>
            <?php endif; ?>

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
                <p style="margin-bottom: 1.5rem; font-size: 0.9rem; color: #64748b;">Acesse os contatos e solicitações enviadas ao PetShop.</p>
                <a href="<?php echo BASE_URL; ?>contato/contato.php" class="btn" style="width: 100%; background-color: #f59e0b;">Abrir Contatos</a>
            </div>
            
        </div>
    <?php endif; ?>

<?php else: ?>
    <div style="background: #fff; padding: 3rem 2rem; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); text-align: center; max-width: 600px; margin: 0 auto;">
        <h2 style="margin-bottom: 1rem; color: var(--text-color);">Acesso Restrito</h2>
        <p style="margin-bottom: 2rem; color: #64748b;">Para acessar as funcionalidades de gestão, agendamentos e controle do PetShop, por favor, realize a sua autenticação no sistema.</p>
        <a href="<?php echo BASE_URL; ?>login/login.php" class="btn" style="font-size: 1.1rem; padding: 0.75rem 2rem;">Fazer Login</a>
    </div>
<?php endif; ?>

<?php require_once 'includes/footer.php'; ?>