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

            header("Location: ../../views/auth/recuperar_senha/confirmar_codigo.php?msg=" . urlencode($msg));
            exit;
        }

        include __DIR__ . '../../views/auth/recuperar_senha/solicitar_email.php';
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

        // Código simples de 6 dígitos
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
            $mail->isSMTP();
            $mail->Host       = 'smtp.mailgun.org';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'cupomapp@sandboxd0ada5c752a04de99456256cf398882e.mailgun.org';
            $mail->Password   = '1eb7d4fd92498c1fd5b0789e2616ee6b-e80d8b76-2946041e';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;
            $mail->CharSet    = 'UTF-8';

            $mail->setFrom('cupomapp@sandboxd0ada5c752a04de99456256cf398882e.mailgun.org', 'Suporte');
            $mail->addAddress($email, $nome);

            $mail->isHTML(true);
            $mail->Subject = 'Recuperação de senha';
            $mail->Body    = "<p>Olá " . htmlspecialchars($nome) . ",</p>"
                           . "<p>Você solicitou redefinição de senha. Use o código abaixo:</p>"
                           . "<h2 style='color:blue;'>" . htmlspecialchars($chave) . "</h2>"
                           . "<p>Este código expira em 30 minutos.</p>"
                           . "<p>Se não foi você, ignore este e-mail.</p>";
            $mail->AltBody = "Olá {$nome}\n\nVocê solicitou redefinição de senha. Use o código: {$chave}\n\nEste código expira em 30 minutos.";

            $mail->send();
            return true;
        } catch (Exception $e) {
            error_log('Erro ao enviar e-mail: ' . $e->getMessage());
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
                // Marca como usado
                $stmtUpd = $this->conn->prepare("UPDATE recuperar_senha SET usado = 1 WHERE id = ?");
                $stmtUpd->bind_param("i", $result['id']);
                $stmtUpd->execute();

                // Redireciona para tela de redefinição de senha
                header("Location: ../views/auth/recuperar_senha/nova_senha.php?identificador=" . urlencode($result['identificador']));
                exit;
            } else {
                $msg = "Código inválido ou expirado.";
                header("Location: ../../views/auth/recuperar_senha/confirmar_codigo.php?msg=" . urlencode($msg));
                exit;
            }
        }

        include __DIR__ . '../../views/auth/recuperar_senha/confirmar_codigo.php';
    }

    public function definirNovaSenha() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['identificador'], $_POST['senha'], $_POST['confirmar'])) {
        $identificador = $_POST['identificador'];
        $senha         = $_POST['senha'];
        $confirmar     = $_POST['confirmar'];

        if ($senha !== $confirmar) {
            $msg = "As senhas não coincidem.";
            header("Location: ../../views/auth/recuperar_senha/nova_senha.php?identificador=" 
                . urlencode($identificador) . "&msg=" . urlencode($msg));
            exit;
        }

        // Criptografa a nova senha
        $hash = password_hash($senha, PASSWORD_DEFAULT);

        // Tenta atualizar em associado
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
        header("Location: ../../views/auth/recuperar_senha/sucesso.php?msg=" . urlencode($msg));
        exit;

    }

    include __DIR__ . '/../views/auth/recuperar_senha/nova_senha.php';
}

}
