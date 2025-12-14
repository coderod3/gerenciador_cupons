<?php
// O identificador (CPF ou CNPJ) vem via GET
$identificador = $_GET['identificador'] ?? null;
$msg = $_GET['msg'] ?? null;
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Nova Senha</title>
</head>
<body>
    <h2>Definir Nova Senha</h2>

    <?php if ($msg): ?>
        <p style="color: red;"><?php echo htmlspecialchars($msg); ?></p>
    <?php endif; ?>

    <?php if ($identificador): ?>
        <form method="POST" action="../../../public/recuperar_senha.php?action=definirNovaSenha">
            <input type="hidden" name="identificador" value="<?php echo htmlspecialchars($identificador); ?>">

            <label for="senha">Nova senha:</label>
            <input type="password" name="senha" id="senha" required minlength="6">

            <label for="confirmar">Confirmar senha:</label>
            <input type="password" name="confirmar" id="confirmar" required minlength="6">

            <button type="submit">Salvar nova senha</button>
        </form>
    <?php else: ?>
        <p>Identificador não informado. Volte e tente novamente.</p>
    <?php endif; ?>
</body>
</html>
