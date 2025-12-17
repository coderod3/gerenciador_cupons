<?php
    $msg = $_GET['msg'] ?? "Senha redefinida com sucesso!";
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <?php include __DIR__ . '../../../shared/head-icons.php'; ?>

    <title>Senha Redefinida</title>
    <link rel="stylesheet" href="../../assets/css/auth/recuperar_senha/sucesso.css">

</head>
<body>

    <div class="card-wrapper">
        <div class="success-icon">
            <i class="ph-bold ph-check" style="font-size: 24px;"></i>
        </div>

        <h2>Tudo pronto!</h2>

        <p class="message-highlight"><?php echo htmlspecialchars($msg); ?></p>
        
        <p class="subtext">Sua senha foi alterada com segurança. Agora você já pode acessar sua conta utilizando a nova credencial.</p>

        <a href="../../../public/index.php" class="btn-login">
            Ir para o Login
        </a>
    </div>

</body>
</html>