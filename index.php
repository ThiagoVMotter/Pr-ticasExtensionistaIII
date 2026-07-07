<?php require_once 'includes/header.php'; ?>

<div style="text-align: center; margin-bottom: 2rem;">
    <h1 style="color: var(--primary-color); margin-bottom: 0.5rem;">Sistema de Gestão - PetShop</h1>
</div>

<?php if (isset($_SESSION['usuario_id'])): ?>
    
    <div style="text-align: center; margin-bottom: 2rem;">
        <h3>Bem-vindo(a), <?php echo htmlspecialchars($_SESSION['usuario_nome']); ?>!</h3>
        <p>Seu perfil atual é: <strong><?php echo ucfirst($_SESSION['usuario_perfil']); ?></strong>.</p>
    </div>

    <?php if ($_SESSION['usuario_perfil'] === 'cliente'): ?>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem; padding: 1rem;">
            <div style="background: #fff; padding: 1.5rem; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); text-align: center; border-top: 4px solid var(--primary-color);">
                <h3 style="margin-bottom: 1rem;">Meus Pets</h3>
                <p style="margin-bottom: 1.5rem; font-size: 0.9rem; color: #64748b;">Módulo em desenvolvimento para acompanhamento médico.</p>
                <button disabled class="btn" style="width: 100%; background: #cbd5e1; cursor: not-allowed;">Em breve</button>
            </div>
            
            <div style="background: #fff; padding: 1.5rem; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); text-align: center; border-top: 4px solid #f59e0b;">
                <h3 style="margin-bottom: 1rem;">Fale Conosco</h3>
                <p style="margin-bottom: 1.5rem; font-size: 0.9rem; color: #64748b;">Agende serviços ou tire suas dúvidas conosco.</p>
                <a href="<?php echo BASE_URL; ?>contato/contato.php" class="btn" style="width: 100%; background-color: #f59e0b;">Enviar Mensagem</a>
            </div>
        </div>

    <?php else: ?>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem; padding: 1rem;">
            
            <?php if ($_SESSION['usuario_perfil'] === 'administrador'): ?>
                <div style="background: #fff; padding: 1.5rem; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); text-align: center; border-top: 4px solid var(--danger-color);">
                    <h3 style="margin-bottom: 1rem;">Gestão de Acessos</h3>
                    <p style="margin-bottom: 1.5rem; font-size: 0.9rem; color: #64748b;">Cadastre a equipe (Admin/Funcionário).</p>
                    <a href="<?php echo BASE_URL; ?>usuarios/cadastrar_usuario.php" class="btn btn-danger" style="width: 100%;">Gerenciar Equipe</a>
                </div>
            <?php endif; ?>

            <div style="background: #fff; padding: 1.5rem; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); text-align: center; border-top: 4px solid #8b5cf6;">
                <h3 style="margin-bottom: 1rem;">Agenda</h3>
                <p style="margin-bottom: 1.5rem; font-size: 0.9rem; color: #64748b;">Controle os horários e status.</p>
                <a href="<?php echo BASE_URL; ?>crud/agendamentos.php" class="btn" style="width: 100%; background-color: #8b5cf6;">Acessar</a>
            </div>

            <div style="background: #fff; padding: 1.5rem; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); text-align: center; border-top: 4px solid var(--primary-color);">
                <h3 style="margin-bottom: 1rem;">Clientes</h3>
                <p style="margin-bottom: 1.5rem; font-size: 0.9rem; color: #64748b;">Cadastre e edite tutores e pets.</p>
                <a href="<?php echo BASE_URL; ?>crud/listar_clientes.php" class="btn" style="width: 100%;">Acessar</a>
            </div>

            <div style="background: #fff; padding: 1.5rem; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); text-align: center; border-top: 4px solid var(--success-color);">
                <h3 style="margin-bottom: 1rem;">Pesquisa</h3>
                <p style="margin-bottom: 1.5rem; font-size: 0.9rem; color: #64748b;">Busca rápida por tutor ou pet.</p>
                <a href="<?php echo BASE_URL; ?>consultas/pesquisa.php" class="btn" style="width: 100%; background-color: var(--success-color);">Buscar</a>
            </div>
        </div>
    <?php endif; ?>

<?php else: ?>
    <div style="background: #fff; padding: 3rem 2rem; border-radius: 8px; text-align: center; max-width: 600px; margin: 0 auto;">
        <h2>Acesso Restrito</h2>
        <p style="margin-bottom: 2rem;">Realize a sua autenticação no sistema.</p>
        <a href="<?php echo BASE_URL; ?>login/login.php" class="btn">Fazer Login</a>
    </div>
<?php endif; ?>

<?php require_once 'includes/footer.php'; ?>