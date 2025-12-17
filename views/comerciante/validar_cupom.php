<!DOCTYPE html>
<html lang="pt-br">
<head>
    <?php include __DIR__ . '/../shared/head-icons.php'; ?>

    <title>Validar Cupom</title>
    <link rel="stylesheet" href="../../views/assets/css/comerciante/validar_cupom.css">
</head>
<body>

    <?php include __DIR__ . '/../shared/header_comerciante.php'; ?>

    <!-- main -->
    <div class="main-wrapper">
        <div class="page-header">
            <h2>Validar Cupom</h2>
            <p>Insira o código apresentado pelo cliente para verificar a validade</p>
        </div>

        <div class="validation-card">
            <!-- form de busca -->
            <form id="formBusca" method="GET" action="../../public/comercio/validar_cupom.php">
                <div class="input-group">
                    <label for="codigo">Código da Reserva</label>
                    <input type="text" id="codigo" name="codigo" class="input-code" 
                           placeholder="EX: A1B2C3" required autocomplete="off"
                           value="<?php echo isset($_GET['codigo']) ? htmlspecialchars($_GET['codigo']) : ''; ?>">
                </div>
                <button type="submit" class="btn-search">Verificar Cupom</button>
            </form>

            <!-- bloco resultado -->
            <div id="resultado">
                <?php if (!empty($msg)): ?>
                    <div class="status-msg <?php echo (strpos($msg, 'sucesso') !== false) ? 'msg-success' : 'msg-error'; ?>">
                        <?php echo htmlspecialchars($msg); ?>
                    </div>
                <?php endif; ?>

                <?php if (!empty($cupom)): ?>
                    <div class="result-box">
                        <h3>Detalhes da Reserva</h3>
                        <table class="details-table">
                            <tr><th>Código</th><td><?php echo htmlspecialchars($cupom['codigo_reserva']); ?></td></tr>
                            <tr><th>Título</th><td><?php echo htmlspecialchars($cupom['titulo']); ?></td></tr>
                            <tr><th>Cliente</th><td><?php echo htmlspecialchars($cupom['nome']); ?></td></tr>
                            <tr><th>Validade</th><td><?php echo date("d/m/Y", strtotime($cupom['data_inicio'])) . " a " . date("d/m/Y", strtotime($cupom['data_termino'])); ?></td></tr>
                            <tr><th>Status</th>
                                <td>
                                    <?php
                                        if (!empty($cupom['data_uso'])) {
                                            echo "<span style='color:#6c757d; font-weight:bold;'>Já utilizado em " . date("d/m/Y", strtotime($cupom['data_uso'])) . "</span>";
                                        } elseif ($cupom['data_termino'] < date('Y-m-d')) {
                                            echo "<span style='color:#dc3545; font-weight:bold;'>Vencido</span>";
                                        } else {
                                            echo "<span style='color:#28a745; font-weight:bold;'>Ativo e Válido</span>";
                                        }
                                    ?>
                                </td>
                            </tr>
                        </table>

                        <?php if (empty($cupom['data_uso']) && $cupom['data_termino'] >= date('Y-m-d')): ?>
                            <form id="formValidar" method="POST" action="../../public/comercio/validar_cupom.php">
                                <input type="hidden" name="codigo" value="<?php echo htmlspecialchars($cupom['codigo_reserva']); ?>">
                                <button type="submit" class="btn-confirm"><i class="ph-bold ph-check"> </i> Confirmar Utilização</button>
                            </form>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script>

        document.getElementById('formBusca').addEventListener('submit', function(e) {
            e.preventDefault();
            const codigo = document.getElementById('codigo').value;

            const btn = this.querySelector('button');
            const originalText = btn.innerText;
            btn.innerText = 'Buscando...';
            btn.disabled = true;

            fetch(this.action + '?codigo=' + encodeURIComponent(codigo))
                .then(res => res.text())
                .then(html => {
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    const resultado = doc.querySelector('#resultado');
                    document.getElementById('resultado').innerHTML = resultado.innerHTML;
                    bindFormValidar();
                    
                    btn.innerText = originalText;
                    btn.disabled = false;
                })
                .catch(err => {
                    console.error(err);
                    btn.innerText = originalText;
                    btn.disabled = false;
                });
        });

        function bindFormValidar() {
            const formValidar = document.getElementById('formValidar');
            if (formValidar) {
                formValidar.addEventListener('submit', function(e) {
                    e.preventDefault();

                    const formData = new FormData(this);
                    const btn = this.querySelector('button');
                    btn.innerText = 'Processando...';
                    btn.disabled = true;

                    fetch(this.action, {
                        method: 'POST',
                        body: formData
                    })
                    .then(res => res.text())
                    .then(html => {
                        const parser = new DOMParser();
                        const doc = parser.parseFromString(html, 'text/html');
                        const resultado = doc.querySelector('#resultado');
                        document.getElementById('resultado').innerHTML = resultado.innerHTML;
                        bindFormValidar();
                    });
                });
            }
        }

        bindFormValidar();
    </script>

    <?php include __DIR__ . '/../shared/footer.php'; ?>

</body>
</html>