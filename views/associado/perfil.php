<!DOCTYPE html>
<html lang="pt-br">
<head>
    <?php include __DIR__ . '/../shared/head-icons.php'; ?>
    <title>Meu Perfil</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <link rel="stylesheet" href="../../views/assets/css/associado/perfil.css">
</head>
<body>

    <?php include __DIR__ . '/../shared/header.php'; ?>

    <div class="main-wrapper">
        
        <div class="page-header">
            <h2>Meu Perfil</h2>
            <p>Mantenha seus dados pessoais sempre atualizados.</p>
        </div>

        <?php if (!empty($msg)): ?>
            <div class="message <?php echo strpos($msg, 'sucesso') !== false ? 'success' : 'error'; ?>">
                <?php echo htmlspecialchars($msg); ?>
            </div>
        <?php endif; ?>

        <div class="form-container">
            <form method="POST" action="">
                
                <div class="form-grid">
                    
                    <h3>Dados Pessoais</h3>
                    
                    <div class="input-group">
                        <label>CPF</label>
                        <input type="text" value="<?php echo htmlspecialchars($associado['cpf']); ?>" disabled class="input-disabled">
                        <small class="field-note">O CPF não pode ser alterado.</small>
                    </div>

                    <div class="input-group">
                        <label for="nome">Nome Completo</label>
                        <input type="text" name="nome" id="nome" 
                               value="<?php echo htmlspecialchars($associado['nome']); ?>" required>
                    </div>

                    <div class="input-group">
                        <label for="data_nascimento">Data de Nascimento</label>
                        <input type="date" name="data_nascimento" id="data_nascimento" 
                               value="<?php echo htmlspecialchars($associado['data_nascimento']); ?>" required>
                    </div>

                    <div class="input-group">
                        <label for="celular">Celular</label>
                        <input type="text" name="celular" id="celular" 
                               value="<?php echo htmlspecialchars($associado['celular']); ?>" placeholder="(XX) XXXXX-XXXX">
                    </div>

                    <div class="input-group full-width">
                        <label for="email">E-mail</label>
                        <input type="email" name="email" id="email" 
                               value="<?php echo htmlspecialchars($associado['email']); ?>" required>
                    </div>

                    <h3>Endereço</h3>
                    
                    <div class="input-group">
                        <label for="cep">CEP</label>
                        <input type="text" name="cep" id="cep" value="<?php echo htmlspecialchars($associado['cep']); ?>">
                    </div>

                    <div class="input-group">
                        <label for="uf">UF</label>
                        <input type="text" name="uf" id="uf" maxlength="2" value="<?php echo htmlspecialchars($associado['uf']); ?>">
                    </div>

                    <div class="input-group">
                        <label for="cidade">Cidade</label>
                        <input type="text" name="cidade" id="cidade" value="<?php echo htmlspecialchars($associado['cidade']); ?>">
                    </div>

                    <div class="input-group">
                        <label for="bairro">Bairro</label>
                        <input type="text" name="bairro" id="bairro" value="<?php echo htmlspecialchars($associado['bairro']); ?>">
                    </div>

                    <div class="input-group full-width">
                        <label for="endereco">Endereço Completo</label>
                        <input type="text" name="endereco" id="endereco" value="<?php echo htmlspecialchars($associado['endereco']); ?>">
                    </div>

                    <div class="form-actions">
                        <a href="home.php" class="btn-cancel">Voltar</a>
                        <button type="submit" class="btn-save">Salvar Alterações</button>
                    </div>

                </div>
            </form>
        </div>
    </div>

    <?php include __DIR__ . '/../shared/footer.php'; ?>

</body>
</html>
