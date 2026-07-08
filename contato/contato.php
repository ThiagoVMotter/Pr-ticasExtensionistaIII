<?php
require_once '../includes/header.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../includes/PHPMailer/Exception.php';
require '../includes/PHPMailer/PHPMailer.php';
require '../includes/PHPMailer/SMTP.php';

if (!isset($_SESSION['usuario_id'])) {
    header("Location: " . BASE_URL . "login/login.php");
    exit;
}

$erro = '';
$sucesso = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_SPECIAL_CHARS);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $assunto = filter_input(INPUT_POST, 'assunto', FILTER_SANITIZE_SPECIAL_CHARS);
    $mensagem = filter_input(INPUT_POST, 'mensagem', FILTER_SANITIZE_SPECIAL_CHARS);

    if (!empty($nome) && !empty($email) && !empty($assunto) && !empty($mensagem)) {
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            try {
                $stmt = $pdo->prepare("INSERT INTO mensagens_contato (nome, email, assunto, mensagem) VALUES (:nome, :email, :assunto, :mensagem)");
                $stmt->execute([
                    ':nome' => $nome,
                    ':email' => $email,
                    ':assunto' => $assunto,
                    ':mensagem' => $mensagem
                ]);
                
                $mail = new PHPMailer(true);

                try {
                    $mail->isSMTP();                                            
                    $mail->Host       = 'smtp.gmail.com';                     
                    $mail->SMTPAuth   = true;                                   
                    $mail->Username   = 'thiagovmotter@gmail.com';
                    $mail->Password   = 'tpyb dbhn isos zbap';
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;            
                    $mail->Port       = 587;                                    

                    $mail->CharSet = 'UTF-8';

                    $mail->setFrom('seu_email@gmail.com', 'Sistema PetShop'); 
                    $mail->addAddress('seu_email@gmail.com', 'Admin PetShop'); 
                    $mail->addReplyTo($email, $nome); 

                    $mail->isHTML(true);                                  
                    $mail->Subject = 'Novo Contato via Sistema: ' . $assunto;
                    
                    $corpo_email = "
                        <h2>Novo contato recebido - PetShop</h2>
                        <p><strong>Nome:</strong> {$nome}</p>
                        <p><strong>E-mail:</strong> {$email}</p>
                        <p><strong>Assunto:</strong> {$assunto}</p>
                        <br>
                        <p><strong>Mensagem:</strong><br>" . nl2br($mensagem) . "</p>
                        <hr>
                        <p><small>Enviado via sistema de Gestão PetShop.</small></p>
                    ";
                    
                    $mail->Body    = $corpo_email;
                    $mail->AltBody = "Novo contato recebido.\nNome: {$nome}\nE-mail: {$email}\nAssunto: {$assunto}\nMensagem: {$mensagem}";

                    $mail->send();
                    
                    $sucesso = "Sua mensagem foi salva no banco e enviada por e-mail com sucesso!";
                    $nome = $email = $assunto = $mensagem = '';
                    
                } catch (Exception $e) {
                    $erro = "Mensagem salva no banco, mas o e-mail falhou. Erro do Mailer: {$mail->ErrorInfo}";
                }
                
            } catch (PDOException $e) {
                $erro = "Erro ao registrar no banco de dados: " . $e->getMessage();
            }
        } else {
            $erro = "Por favor, informe um endereço de e-mail válido.";
        }
    } else {
        $erro = "Todos os campos são de preenchimento obrigatório.";
    }
}
?>

<div style="max-width: 700px; margin: 0 auto; background: #fff; padding: 2.5rem; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); margin-bottom: 2rem;">
    <h2 style="margin-bottom: 1rem; color: var(--primary-color); text-align: center;">Fale Conosco</h2>
    <p style="text-align: center; color: #64748b; margin-bottom: 2rem;">Preencha o formulário abaixo. Sua mensagem será registrada no sistema e enviada para o administrador.</p>
    
    <?php if ($erro): ?>
        <div style="padding: 12px; background: #fee2e2; color: #991b1b; margin-bottom: 1.5rem; border-radius: 4px; text-align: center;">
            <?php echo $erro; ?>
        </div>
    <?php endif; ?>

    <?php if ($sucesso): ?>
        <div style="padding: 12px; background: #dcfce7; color: #166534; margin-bottom: 1.5rem; border-radius: 4px; text-align: center; font-weight: 500;">
            <?php echo $sucesso; ?>
        </div>
    <?php endif; ?>

    <form action="contato.php" method="POST" style="display: flex; flex-direction: column; gap: 1.2rem;">
        
        <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
            <div style="flex: 1; min-width: 250px;">
                <label style="font-weight: 500; display: block; margin-bottom: 0.3rem;">Nome Completo *</label>
                <input type="text" name="nome" value="<?php echo htmlspecialchars($nome ?? ''); ?>" required style="width: 100%; padding: 0.75rem; border: 1px solid var(--border-color); border-radius: 4px;">
            </div>
            <div style="flex: 1; min-width: 250px;">
                <label style="font-weight: 500; display: block; margin-bottom: 0.3rem;">E-mail *</label>
                <input type="email" name="email" value="<?php echo htmlspecialchars($email ?? ''); ?>" required style="width: 100%; padding: 0.75rem; border: 1px solid var(--border-color); border-radius: 4px;">
            </div>
        </div>

        <div>
            <label style="font-weight: 500; display: block; margin-bottom: 0.3rem;">Assunto *</label>
            <input type="text" name="assunto" value="<?php echo htmlspecialchars($assunto ?? ''); ?>" required style="width: 100%; padding: 0.75rem; border: 1px solid var(--border-color); border-radius: 4px;">
        </div>
        
        <div>
            <label style="font-weight: 500; display: block; margin-bottom: 0.3rem;">Mensagem *</label>
            <textarea name="mensagem" required rows="5" style="width: 100%; padding: 0.75rem; border: 1px solid var(--border-color); border-radius: 4px; resize: vertical;"><?php echo htmlspecialchars($mensagem ?? ''); ?></textarea>
        </div>
        
        <div style="margin-top: 0.5rem; text-align: right;">
            <button type="submit" class="btn" style="background-color: var(--primary-color); padding: 0.75rem 2.5rem; font-size: 1rem;">Enviar Mensagem</button>
        </div>
    </form>
</div>

<?php require_once '../includes/footer.php'; ?>