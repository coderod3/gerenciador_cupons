<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Login - Sistema de Cupons</title>
    <link rel="stylesheet" href="../views/assets/css/auth.css">
</head>
<body>
    <h2>Login</h2>

    <?php if (!empty($erro)): ?>
        <p style="color:red;"><?php echo htmlspecialchars($erro); ?></p>
    <?php endif; ?>

    <form method="POST" action="" autocomplete="on">
        <label for="login">CPF ou CNPJ:</label><br>

        <!--<input type="text" id="login" name="login" required value="111.222.333-44"><br><br>-->
        <input type="text" id="login" name="login" required value="111.222.333-44"><br><br>

        <label for="senha">Senha:</label><br>
        <!--<input type="password" id="senha" name="senha" required value="HASH_LUCAS"><br><br>-->
        <input type="password" id="senha" name="senha" required value="HASH_LUCAS"><br><br>

        <button type="submit">Login</button>
    </form>

    <br>
    <form method="GET" action="criar_conta.php">
        <button type="submit">Cadastro</button>
    </form>
    
    <form method="GET" action="recuperar_senha.php?action=solicitarEmail">
        <button type="submit">Esqueceu a senha</button>
    </form>

</body>
</html>
