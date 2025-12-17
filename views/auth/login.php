<!DOCTYPE html>
<html lang="pt-br">
<head>

    <?php include __DIR__ . '/../shared/head-icons.php'; ?>

    <title>Entrar - CupomApp</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../views/assets/css/auth/login.css">

</head>
<body>

    <div class="login-container">
        
        <div class="info-section">
            <div class="info-content">
                
                <div class="header-block">
                    <div class="logo-display">
                        <img src="../views/assets/icons/icon-64.png" alt="Logo">
                    </div>
                    <h1 class="brand-name">CupomApp</h1>
                    <p class="tagline">Economize em cada compra com cupons exclusivos das melhores lojas da sua região.</p>
                </div>
                
                <ul class="features-list">
                    <li class="feature-item">
                        <div class="feature-icon"><i class="ph ph-tag"></i></div>
                        <div class="feature-text">
                            <span class="title">Descontos Reais</span>
                            <span class="desc">Ofertas verificadas de parceiros</span>
                        </div>
                    </li>
                    <li class="feature-item">
                        <div class="feature-icon"><i class="ph ph-storefront"></i></div>
                        <div class="feature-text">
                            <span class="title">Comércio Local</span>
                            <span class="desc">Encontre o que precisa perto de você</span>
                        </div>
                    </li>
                    <li class="feature-item">
                        <div class="feature-icon"><i class="ph ph-lightning"></i></div>
                        <div class="feature-text">
                            <span class="title">Simples e Rápido</span>
                            <span class="desc">Reserve e utilize em segundos</span>
                        </div>
                    </li>
                </ul>

            </div>
        </div>

        <div class="form-section">
            <div class="form-wrapper">
                
                <div class="form-header">
                    <h2>Bem-vindo de volta!</h2>
                    <p>Entre com suas credenciais para acessar sua conta.</p>
                </div>

                <?php if (!empty($erro)): ?>
                    <div class="error-msg">
                        <i class="ph ph-warning-circle"></i>
                        <span><?php echo htmlspecialchars($erro); ?></span>
                    </div>
                <?php endif; ?>

                <form method="POST" action="" autocomplete="on">
                    <div class="input-group">
                        <label for="login">CPF ou CNPJ</label>
                        <div class="input-wrapper">
                            <i class="ph ph-user"></i>
                            <input type="text" id="login" name="login" required 
                                        value=""
                                        placeholder="000.000.000-00" maxlength="18"
                                        oninput="mascaraLogin(this)">
                        </div>
                    </div>

                    <div class="input-group">
                        <div class="label-row">
                            <label for="senha">Senha</label>
                            <a href="recuperar_senha.php?action=solicitarEmail" class="forgot-link">Esqueceu?</a>
                        </div>
                        <div class="input-wrapper">
                            <i class="ph ph-lock-key"></i>
                            <input type="password" id="senha" name="senha" required 
                                   value="" placeholder="••••••••">
                        </div>
                    </div>

                    <button type="submit" class="btn-primary">
                        Entrar na Plataforma <i class="ph ph-arrow-right"></i>
                    </button>
                </form>

                <div class="form-footer">
                    <p>Não tem uma conta? <a href="criar_conta.php">Crie gratuitamente</a></p>
                </div>

            </div>
        </div>

    </div>

</body>
<script>
    function mascaraLogin(o) {
        setTimeout(function() {
            var v = o.value.replace(/\D/g, ""); // remove tudo que não é digito
            
            if (v.length <= 11) {
                // mascara cpf (000.000.000-00)
                v = v.replace(/(\d{3})(\d)/, "$1.$2");
                v = v.replace(/(\d{3})(\d)/, "$1.$2");
                v = v.replace(/(\d{3})(\d{1,2})$/, "$1-$2");
            } else {
                // máscara cnpj (00.000.000/0000-00)
                v = v.replace(/^(\d{2})(\d)/, "$1.$2");
                v = v.replace(/^(\d{2})\.(\d{3})(\d)/, "$1.$2.$3");
                v = v.replace(/\.(\d{3})(\d)/, ".$1/$2");
                v = v.replace(/(\d{4})(\d)/, "$1-$2");
            }
            
            o.value = v;
        }, 1);
    }
</script>
</html>