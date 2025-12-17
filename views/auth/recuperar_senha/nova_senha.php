<?php
    //identificador via get
    $identificador = $_GET['identificador'] ?? null;
    $msg = $_GET['msg'] ?? null;
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <?php include __DIR__ . '../../../shared/head-icons.php'; ?>

    <title>Nova Senha</title>
    <link rel="stylesheet" href="../views/assets/css/auth/recuperar_senha/nova_senha.css">

</head>
<body>

    <div class="card-wrapper">

        <div class="icon-key">
            <img src="https://cdn-icons-png.flaticon.com/512/807/807241.png" alt="chave">
        </div>

        <?php if ($identificador): ?>
            <h2>Definir Nova Senha</h2>
            <p class="subtitle">Crie uma senha forte para proteger sua conta.</p>

            <?php if ($msg): ?>
                <div class="msg-box msg-error">
                    ⚠ <?php echo htmlspecialchars($msg); ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="" id="formSenha">
                <input type="hidden" name="identificador" value="<?php echo htmlspecialchars($identificador); ?>">

                <div class="input-group">
                    <label for="senha">Nova Senha</label>
                    <input type="password" name="senha" id="senha" required minlength="6" placeholder="Mínimo 6 caracteres">
                    <div class="password-hint">Use letras e números para maior segurança.</div>
                </div>

                <div class="input-group">
                    <label for="confirmar">Confirmar Senha</label>
                    <input type="password" name="confirmar" id="confirmar" required minlength="6" placeholder="Repita a senha">
                </div>

                <button type="submit" class="btn-submit">Salvar Nova Senha</button>
            </form>

            <script>
                // validar senhas
                document.getElementById('formSenha').addEventListener('submit', function(e) {
                    const senha = document.getElementById('senha').value;
                    const confirmar = document.getElementById('confirmar').value;
                    
                    if (senha !== confirmar) {
                        e.preventDefault();
                        alert('As senhas não conferem. Por favor, verifique.');
                        document.getElementById('confirmar').focus();
                        document.getElementById('confirmar').style.borderColor = '#ef4444';
                    }
                });
            </script>

        <?php else: ?>
            <!-- erro-->
            <h2>Link Inválido</h2>
            <p class="subtitle">Não foi possível identificar sua conta. Por favor, inicie o processo novamente.</p>
            <a href="../../../public/recuperar_senha.php" class="btn-submit" style="text-decoration: none; display: inline-block; box-sizing: border-box;">Voltar ao Início</a>
        <?php endif; ?>
    </div>

</body>
</html>