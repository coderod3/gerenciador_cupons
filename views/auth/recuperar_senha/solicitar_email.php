<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Redefinir Senha</title>
    <link rel="stylesheet" href="../../assets/css/auth.css">
</head>
<body>
    <h2>Redefinir Senha</h2>
    <p>Digite seu e-mail para receber um código de confirmação.</p>

    <?php if (isset($_GET['msg'])): ?>
        <p style="color: green;"><?php echo htmlspecialchars($_GET['msg']); ?></p>
    <?php endif; ?>

    <form method="POST" action="../../../public/recuperar_senha.php?action=solicitarEmail">
        <label for="email">E-mail:</label>
        <input type="email" name="email" id="email" required>

        <p>Você é:</p>
        <label>
            <input type="radio" name="tipo_usuario" value="associado" required> Associado
        </label>
        <label>
            <input type="radio" name="tipo_usuario" value="comercio"> Comerciante
        </label>
        <br>
        <br>
        <button type="submit">Enviar código</button>
    </form>

    <p><a href="../public">Voltar ao login</a></p>

</body>
</html>
