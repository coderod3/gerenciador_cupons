<!DOCTYPE html>
<html lang="pt-br">
<head>
    <?php include __DIR__ . '/../shared/head-icons.php'; ?>
    <title>Cadastrar Cupom</title>
    <link rel="stylesheet" href="../../views/assets/css/comerciante/cadastrar_cupons.css"
</head>

<body>

    <?php include __DIR__ . '/../shared/header_comerciante.php'; ?>

    <!-- main -->
    <div class="main-wrapper">
        
        <div class="page-header">
            <h2>Cadastrar Novo Cupom</h2>
            <p>Preencha os detalhes da sua oferta abaixo</p>
        </div>

        <div class="form-card">
            
            <?php if (!empty($msg)): ?>
                <div class="message-success">
                    <i class="ph-bold ph-check"> </i> <?php echo htmlspecialchars($msg); ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="../../public/comercio/cadastrar_cupom.php">
                
                <div class="form-group">
                    <label for="titulo">Título da promoção</label>
                    <input type="text" id="titulo" name="titulo" required maxlength="80" placeholder="Ex: 50% OFF em todo o site">
                </div>

                <div class="form-group">
                    <label for="data_inicio">Data de início</label>
                    <input type="date" id="data_inicio" name="data_inicio" required>
                </div>

                <div class="form-group">
                    <label for="data_termino">Data de término</label>
                    <input type="date" id="data_termino" name="data_termino" required>
                </div>

                <div class="form-group">
                    <label for="percentual_desc">Percentual de desconto (%)</label>
                    <input type="number" id="percentual_desc" name="percentual_desc" step="1" min="1" max="100" required placeholder="Ex: 20">
                </div>

                <div class="form-group">
                    <label for="quantidade">Quantidade de cupons</label>
                    <input type="number" id="quantidade" name="quantidade" min="1" required placeholder="Ex: 100">
                </div>

                <button type="submit">Cadastrar Cupom</button>
            </form>
        </div>
    </div>

    <?php include __DIR__ . '/../shared/footer.php'; ?>

</body>
</html>