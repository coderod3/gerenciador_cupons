<!DOCTYPE html>
<html lang="pt-br">
<head>
    <?php include __DIR__ . '../../../shared/head-icons.php'; ?>

    <title>Recuperar Senha</title>
    <link rel="stylesheet" href="../views/assets/css/auth/recuperar_senha/solicitar_email.css">

</head>
<body>

    <div class="card-wrapper">

        <div class="icon-lock">
            <img src="https://cdn-icons-png.flaticon.com/512/595/595586.png" alt="cadeado">
        </div>

        <h2>Esqueceu a senha?</h2>
        <p class="subtitle">Digite seu e-mail e enviaremos as instruções para você redefinir sua senha.</p>

        <?php if (isset($_GET['msg'])): ?>
            <div class="msg-success">
                <?php echo htmlspecialchars($_GET['msg']); ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="">
            
            <div class="input-group">
                <label>Eu sou:</label>
                <div class="type-selector">
                    <label>
                        <input type="radio" name="tipo_usuario" value="associado" checked>
                        <span>Associado</span>
                    </label>
                    <label>
                        <input type="radio" name="tipo_usuario" value="comercio">
                        <span>Comerciante</span>
                    </label>
                </div>
            </div>

            <div class="input-group">
                <label for="email">Seu e-mail cadastrado</label>
                <input type="email" name="email" id="email" placeholder="nome@exemplo.com" required>
            </div>

            <button type="submit" class="btn-submit">Enviar Código</button>
        </form>

        <a href="../public" class="back-link">Voltar para o Login</a>
    </div>

</body>
</html>