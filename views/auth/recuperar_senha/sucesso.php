<?php
$msg = $_GET['msg'] ?? "Senha redefinida com sucesso!";
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Senha Redefinida</title>
</head>
<body>
    <h2>Recuperação concluída!</h2>

    <p style="color: green;"><?php echo htmlspecialchars($msg); ?></p>

    <p>Agora você já pode acessar sua conta com a nova senha.</p>

    <a href="../../../public">
        <button>Ir para Login</button>
    </a>
</body>
</html>
