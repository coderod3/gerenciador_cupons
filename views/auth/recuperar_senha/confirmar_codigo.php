<?php
    // mensagem passada ex email enviado
    $msg = $_GET['msg'] ?? null;
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <?php include __DIR__ . '../../../shared/head-icons.php'; ?>

    <title>Confirmar Código</title>
    <link rel="stylesheet" href="../views/assets/css/auth/recuperar_senha/confirmar_codigo.css">
</head>
<body>

    <div class="card-wrapper">
        <div class="icon-mail">
            <img src="https://cdn-icons-png.flaticon.com/512/5089/5089889.png" alt="envelope" >
        </div>

        <h2>Verifique seu E-mail</h2>
        <p class="subtitle">Enviamos um código de 6 dígitos para o seu e-mail. Digite-o abaixo para continuar.</p>

        <?php if ($msg): ?>
            <div class="msg-success">
                <?php echo htmlspecialchars($msg); ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="">
            
            <div class="input-group">
                <label for="codigo">Código de Verificação</label>
                <input type="text" name="codigo" id="codigo" maxlength="6" required placeholder="000000" autocomplete="off">
            </div>

            <button type="submit" class="btn-submit">Validar Código</button>
        </form>
        <div class="auth-footer">
            Precisa de ajuda? <a href="mailto:suporte.cupomapp@gmail.com">Fale com o suporte</a>
        </div>
        <a href="../public" class="back-link">Voltar / Reenviar</a>
    </div>

</body>
</html>