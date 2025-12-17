<!DOCTYPE html>
<html lang="pt-br">
<head>
    <?php include __DIR__ . '/../shared/head-icons.php'; ?>

    <title>Editar Perfil</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../../views/assets/css/comerciante/perfil.css">
</head>
<body>

    <?php include __DIR__ . '/../shared/header_comerciante.php'; ?>

    <div class="main-wrapper">
        
        <div class="page-header">
            <h2>Meu Perfil</h2>
            <p>Mantenha seus dados comerciais e de contato atualizados.</p>
        </div>

        <?php if (!empty($msg)): ?>
            <div class="message <?php echo strpos($msg, 'sucesso') !== false ? 'success' : 'error'; ?>">
                <?php echo htmlspecialchars($msg); ?>
            </div>
        <?php endif; ?>

        <div class="form-container">
            
            <form method="POST" action="">
                
                <div class="form-grid">
                    
                    <h3>Dados da Empresa</h3>
                    
                    <div class="input-group">
                        <label>CNPJ</label>
                        <input type="text" value="<?php echo htmlspecialchars($comerciante['cnpj']); ?>" disabled class="input-disabled">
                        <small class="field-note">O CNPJ não pode ser alterado.</small>
                    </div>

                    <div class="input-group">
                        <label>Razão Social</label>
                        <input type="text" value="<?php echo htmlspecialchars($comerciante['razao_social']); ?>" disabled class="input-disabled">
                    </div>

                    <div class="input-group full-width">
                        <label for="nome_fantasia">Nome Fantasia</label>
                        <input type="text" name="nome_fantasia" id="nome_fantasia" 
                               value="<?php echo htmlspecialchars($comerciante['nome_fantasia']); ?>" placeholder="Nome Fantasia">
                    </div>

                    <div class="input-group full-width">
                        <label for="email">E-mail Empresarial</label>
                        <input type="email" name="email" id="email" 
                               value="<?php echo htmlspecialchars($comerciante['email']); ?>" placeholder="E-mail">
                    </div>

                    <div class="input-group">
                        <label for="contato">Telefone / WhatsApp</label>
                        <input type="text" name="contato" id="contato" 
                               value="<?php echo htmlspecialchars($comerciante['contato']); ?>" placeholder="Telefone">
                    </div>

                    <div class="input-group">
                        <label for="categoria_id">Categoria</label>
                        <select name="categoria_id" id="categoria_id">
                            <option value="">Selecione</option>
                            <?php foreach ($categorias as $cat): ?>
                                <option value="<?php echo $cat['id']; ?>" 
                                    <?php echo (isset($comerciante['categoria_id']) && $comerciante['categoria_id'] == $cat['id']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($cat['nome']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <p class="input-helper">
                            Não achou sua categoria? <a href="mailto:suporte.cupomapp@gmail.com">Solicite aqui.</a>
                        </p>
                    </div>

                    <h3>Localização</h3>
                    
                    <div class="input-group">
                        <label for="cep">CEP</label>
                        <input type="text" name="cep" id="cep" value="<?php echo htmlspecialchars($comerciante['cep']); ?>" placeholder="CEP">
                    </div>

                    <div class="input-group">
                        <label for="uf">UF</label>
                        <input type="text" name="uf" id="uf" maxlength="2" value="<?php echo htmlspecialchars($comerciante['uf']); ?>" placeholder="UF">
                    </div>

                    <div class="input-group">
                        <label for="cidade">Cidade</label>
                        <input type="text" name="cidade" id="cidade" value="<?php echo htmlspecialchars($comerciante['cidade']); ?>" placeholder="Cidade">
                    </div>

                    <div class="input-group">
                        <label for="bairro">Bairro</label>
                        <input type="text" name="bairro" id="bairro" value="<?php echo htmlspecialchars($comerciante['bairro']); ?>" placeholder="Bairro">
                    </div>

                    <div class="input-group full-width">
                        <label for="endereco">Endereço Completo</label>
                        <input type="text" name="endereco" id="endereco" value="<?php echo htmlspecialchars($comerciante['endereco']); ?>" placeholder="Rua, Número, Complemento">
                    </div>

                    <div class="form-actions">
                        <a href="home.php" class="btn-cancel">Cancelar</a>
                        <button type="submit" class="btn-save">Salvar Alterações</button>
                    </div>

                </div>
            </form>
        </div>
    </div>

    <?php include __DIR__ . '/../shared/footer.php'; ?>

</body>
</html>
