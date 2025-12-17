<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/../vendor/autoload.php';

class RecuperarSenhaController {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function solicitarEmail() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email'], $_POST['tipo_usuario'])) {
            $email = trim($_POST['email']);
            $tipo  = $_POST['tipo_usuario']; // 'associado' ou 'comercio'

            $reset = $this->criarSolicitacaoReset($email, $tipo);
            if ($reset) {
                $this->enviarEmail($reset['nome'], $email, $reset['chave']);
                $msg = "Email enviado! Verifique sua caixa de entrada.";
            } else {
                $msg = "Se o e-mail existir, você receberá instruções.";
            }

            header("Location: recuperar_senha.php?action=confirmarCodigo&msg=" . urlencode($msg));
            exit;
        }

        include __DIR__ . '/../views/auth/recuperar_senha/solicitar_email.php';
    }

    private function criarSolicitacaoReset($email, $tipo) {
        if ($tipo === 'associado') {
            $stmt = $this->conn->prepare("SELECT cpf AS identificador, nome FROM associado WHERE email = ?");
        } else {
            $stmt = $this->conn->prepare("SELECT cnpj AS identificador, razao_social AS nome FROM comercio WHERE email = ?");
        }

        $stmt->bind_param("s", $email);
        $stmt->execute();
        $usuario = $stmt->get_result()->fetch_assoc();

        if (!$usuario) {
            return null;
        }

        // código simples de 6 dígitos
        $chave = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $vence_em = date("Y-m-d H:i:s", strtotime("+30 minutes"));

        $stmtIns = $this->conn->prepare(
            "INSERT INTO recuperar_senha (identificador, chave, vence_em, usado) VALUES (?, ?, ?, 0)"
        );
        $stmtIns->bind_param("sss", $usuario['identificador'], $chave, $vence_em);
        $stmtIns->execute();

        return [
            'nome'  => $usuario['nome'],
            'chave' => $chave
        ];
    }

    private function enviarEmail($nome, $email, $chave): bool {
        $mail = new PHPMailer(true);

        try {
            // configurações do servidor (gmails)
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com'; 
            $mail->SMTPAuth   = true;
            $mail->Username   = 'suporte.cupomapp@gmail.com';
            $mail->Password   = 'ltfd tpee yzte zgiu';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // obrigatório para gmail na porta 587
            $mail->Port       = 587; 
            $mail->CharSet    = 'UTF-8';

            // destinatários
            $mail->setFrom('suporte.cupomapp@gmail.com', 'Suporte CupomApp');
            $mail->addAddress($email, $nome);

            // conteúdo email
            $mail->isHTML(true);
            $mail->Subject = 'Recuperação de Senha - CupomApp';
            
            // corpo do email
            $mail->Body    = "
            <div style='font-family: Arial, sans-serif; color: #333; max-width: 600px; margin: 0 auto; border: 1px solid #ddd; padding: 20px; border-radius: 8px;'>
                <h2 style='color: #2c3e50; text-align: center;'>CupomApp</h2>
                <hr style='border: 0; border-top: 1px solid #eee;'>
                <p>Olá, <strong>" . htmlspecialchars($nome) . "</strong>!</p>
                <p>Recebemos uma solicitação para redefinir a senha da sua conta.</p>
                <p>Seu código de verificação é:</p>
                <div style='text-align: center; margin: 20px 0;'>
                    <span style='font-size: 24px; font-weight: bold; color: #ffffff; background-color: #007bff; padding: 10px 20px; border-radius: 5px; letter-spacing: 2px;'>
                        " . htmlspecialchars($chave) . "
                    </span>
                </div>
                <p>Este código expira em 30 minutos.</p>
                <p style='font-size: 12px; color: #777;'>Se você não solicitou esta alteração, por favor ignore este e-mail. Nenhuma alteração será feita em sua conta.</p>
                <hr style='border: 0; border-top: 1px solid #eee; margin-top: 20px;'>
                <p style='text-align: center; font-size: 11px; color: #aaa;'>&copy; " . date('Y') . " CupomApp. Todos os direitos reservados.</p>
            </div>";

            // texto alternativo sem o html (para clientes antigos)
            $mail->AltBody = "Olá {$nome},\n\nRecebemos uma solicitação para redefinir sua senha no CupomApp.\nSeu código de verificação é: {$chave}\n\nEste código expira em 30 minutos.\nSe você não solicitou isso, ignore este e-mail.";

            $mail->send();
            return true;
        } catch (Exception $e) {
            // error_log('Erro PHPMailer: ' . $mail->ErrorInfo); 
            return false;
        }
    }

    public function confirmarCodigo() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['codigo'])) {
            $codigo = trim($_POST['codigo']);

            // Busca código válido e não usado
            $stmt = $this->conn->prepare(
                "SELECT * FROM recuperar_senha 
                WHERE chave = ? AND usado = 0 AND vence_em > NOW()"
            );
            $stmt->bind_param("s", $codigo);
            $stmt->execute();
            $result = $stmt->get_result()->fetch_assoc();

            if ($result) {
                // marca como usado
                $stmtUpd = $this->conn->prepare("UPDATE recuperar_senha SET usado = 1 WHERE id = ?");
                $stmtUpd->bind_param("i", $result['id']);
                $stmtUpd->execute();

                // redireciona para tela de redefinição de senha
                header("Location: recuperar_senha.php?action=definirNovaSenha&identificador=" . urlencode($result['identificador']));
                exit;
            } else {
                $msg = "Código inválido ou expirado.";
                header("Location: recuperar_senha.php?action=confirmarCodigo&msg=" . urlencode($msg));
                exit;
            }
        }

        include __DIR__ . '/../views/auth/recuperar_senha/confirmar_codigo.php';
    }

    public function definirNovaSenha() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['identificador'], $_POST['senha'], $_POST['confirmar'])) {
        $identificador = $_POST['identificador'];
        $senha         = $_POST['senha'];
        $confirmar     = $_POST['confirmar'];

        if ($senha !== $confirmar) {
            $msg = "As senhas não coincidem.";
            header("Location: recuperar_senha.php?action=definirNovaSenha&identificador=" 
                . urlencode($identificador) . "&msg=" . urlencode($msg));
            exit;
        }

        // criptografa a nova senha
        $hash = password_hash($senha, PASSWORD_DEFAULT);

        // tenta atualizar em associado
        $stmt = $this->conn->prepare("UPDATE associado SET senha_hash = ? WHERE cpf = ?");
        $stmt->bind_param("ss", $hash, $identificador);
        $stmt->execute();

        if ($stmt->affected_rows === 0) {
            // Se não atualizou, tenta em comercio
            $stmt = $this->conn->prepare("UPDATE comercio SET senha_hash = ? WHERE cnpj = ?");
            $stmt->bind_param("ss", $hash, $identificador);
            $stmt->execute();
        }

        $msg = "Senha redefinida com sucesso! Agora você já pode fazer login.";
        header("Location: ../views/auth/recuperar_senha/sucesso.php?msg=" . urlencode($msg));
        exit;

    }

    include __DIR__ . '/../views/auth/recuperar_senha/nova_senha.php';
}

}