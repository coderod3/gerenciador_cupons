<?php
// Exibe mensagem passada via GET (ex.: "Email enviado!")
$msg = $_GET['msg'] ?? null;
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Confirmar Código</title>
</head>
<body>
    <h2>Confirmação de Código</h2>

    <?php if ($msg): ?>
        <p style="color: green;"><?php echo htmlspecialchars($msg); ?></p>
    <?php endif; ?>

    <form method="POST" action="../../../public/recuperar_senha.php?action=confirmarCodigo">
        <label for="codigo">Digite o código recebido:</label>
        <input type="text" name="codigo" id="codigo" maxlength="6" required>

        <button type="submit">Validar</button>
    </form>
</body>
</html>
