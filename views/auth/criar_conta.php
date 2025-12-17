<?php
    // recuperar dados antigos do formulario numa atualizacao
    function old($key) {
        return isset($_POST[$key]) ? htmlspecialchars($_POST[$key]) : '';
    }

    //verificar qual campo deu erro
    function hasError($field_name, $error_msg) {
        if (empty($error_msg)) return '';
        
        $map = [
            'cpf' => ['CPF', 'cpf'],
            'cnpj' => ['CNPJ', 'cnpj'],
            'senha' => ['senha', 'senhas'],
            'email' => ['e-mail', 'email'],
            'contato' => ['celular', 'telefone']
        ];

        if (isset($map[$field_name])) {
            foreach ($map[$field_name] as $term) {
                if (stripos($error_msg, $term) !== false) return 'input-error';
            }
        }
        return '';
    }
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <?php include __DIR__ . '/../shared/head-icons.php'; ?>

    <title>Criar Conta</title>
    <link rel="stylesheet" href="../views/assets/css/auth/criar_conta.css">
    <style>
        /* border erro */
        .input-error {
            border-color: #dc3545 !important;
            background-color: #fff8f8 !important;
        }
        .input-error:focus {
            box-shadow: 0 0 0 3px rgba(220, 53, 69, 0.25) !important;
        }
    </style>

</head>
<body>

    <div class="main-wrapper" id="mainWrapper">
        <div class="header-box">
            <h2>Criar Conta</h2>
            <a href="index.php" class="back-link">Já tem uma conta? Entrar</a>
        </div>

        <?php if (!empty($msg)): ?>
            <div class="message <?php echo strpos($msg, 'sucesso') !== false ? 'success' : 'error'; ?>">
                <?php echo htmlspecialchars($msg); ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="">
            
            <div class="profile-selector">
                <label class="profile-option">
                    <input type="radio" name="perfil" value="associado" onchange="toggleForm('associado')"
                        <?php echo (old('perfil') === 'associado' || old('perfil') === '') ? 'checked' : ''; ?>>
                    <div class="profile-card">
                        <i class="ph-fill ph-user profile-icon"></i>
                        <span class="profile-label">Associado</span>
                    </div>
                </label>
                <label class="profile-option">
                    <input type="radio" name="perfil" value="comerciante" onchange="toggleForm('comerciante')"
                        <?php echo (old('perfil') === 'comerciante') ? 'checked' : ''; ?>>
                    <div class="profile-card">
                        <!-- <span class="profile-icon">🏪</span> -->
                        <i class="ph-fill ph-building-office profile-icon"></i>
                        <span class="profile-label">Comerciante</span>
                    </div>
                </label>
            </div>

            <div id="form-content" class="form-container">
                
                <div id="fields-associado" class="form-grid" style="display:none;">
                    <h3>Dados Pessoais</h3>
                    <input type="text" name="cpf" placeholder="CPF (000.000.000-00)" id="assoc_cpf" maxlength="14" 
                           class="<?= hasError('cpf', $msg ?? '') ?>" 
                           value="<?= old('cpf') ?>" oninput="mascara(this, mcpf)">
                    
                    <input type="text" name="nome" placeholder="Nome Completo" id="assoc_nome" 
                           value="<?= old('nome') ?>">
                    
                    <input type="date" name="data_nascimento" title="Nascimento" id="assoc_nasc" 
                           value="<?= old('data_nascimento') ?>">
                    
                    <input type="text" name="celular" placeholder="Celular (99) 99999-9999" id="assoc_cel" maxlength="15" 
                           value="<?= old('celular') ?>" oninput="mascara(this, mtel)">
                    
                    <input type="email" name="email" placeholder="E-mail" class="full-width <?= hasError('email', $msg ?? '') ?>" id="assoc_email" 
                           value="<?= old('email') ?>"> <h3>Endereço</h3>
                    <input type="text" name="cep" placeholder="CEP (00000-000)" id="assoc_cep" maxlength="9" 
                           value="<?= old('cep') ?>" oninput="mascara(this, mcep)">
                    
                    <input type="text" name="uf" placeholder="UF" maxlength="2" id="assoc_uf" style="text-transform:uppercase" 
                           value="<?= old('uf') ?>">
                    
                    <input type="text" name="cidade" placeholder="Cidade" id="assoc_cidade" 
                           value="<?= old('cidade') ?>">
                    
                    <input type="text" name="bairro" placeholder="Bairro" id="assoc_bairro" 
                           value="<?= old('bairro') ?>">
                    
                    <input type="text" name="endereco" placeholder="Endereço Completo" class="full-width" id="assoc_end" 
                           value="<?= old('endereco') ?>">
                </div>

                <div id="fields-comerciante" class="form-grid" style="display:none;">
                    <h3>Dados da Empresa</h3>
                    <input type="text" name="cnpj" placeholder="CNPJ (00.000.000/0000-00)" id="com_cnpj" maxlength="18" 
                           class="<?= hasError('cnpj', $msg ?? '') ?>" 
                           value="<?= old('cnpj') ?>" oninput="mascara(this, mcnpj)">
                    
                    <input type="text" name="razao_social" placeholder="Razão Social" id="com_razao" 
                           value="<?= old('razao_social') ?>">
                    
                    <input type="text" name="nome_fantasia" placeholder="Nome Fantasia" class="full-width" id="com_fantasia" 
                           value="<?= old('nome_fantasia') ?>">
                    
                    <input type="email" name="email" placeholder="E-mail Empresarial" class="full-width <?= hasError('email', $msg ?? '') ?>" id="com_email" 
                           value="<?= old('email') ?>"> <input type="text" name="contato" placeholder="Telefone (99) 9999-9999" id="com_contato" maxlength="15" 
                           value="<?= old('contato') ?>" oninput="mascara(this, mtel)">
                    
                    <select name="categoria_id" id="com_cat">
                        <option value="">Categoria</option>
                        <?php foreach ($categorias as $cat): ?>
                            <option value="<?php echo $cat['id']; ?>" <?= (old('categoria_id') == $cat['id']) ? 'selected' : '' ?>>
                                <?php echo htmlspecialchars($cat['nome']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>

                    <p class="input-helper">
                        Não achou sua categoria? <a href="mailto:suporte.cupomapp@gmail.com">Solicite aqui.</a>
                    </p>Estilo extra para destacar o 

                    <h3>Localização</h3>
                    <input type="text" name="cep" placeholder="CEP" id="com_cep" maxlength="9" value="<?= old('cep') ?>" oninput="mascara(this, mcep)">
                    <input type="text" name="uf" placeholder="UF" maxlength="2" id="com_uf" style="text-transform:uppercase" value="<?= old('uf') ?>">
                    <input type="text" name="cidade" placeholder="Cidade" id="com_cidade" value="<?= old('cidade') ?>">
                    <input type="text" name="bairro" placeholder="Bairro" id="com_bairro" value="<?= old('bairro') ?>">
                    <input type="text" name="endereco" placeholder="Endereço" class="full-width" id="com_end" value="<?= old('endereco') ?>">
                </div>

                <div class="form-grid full-width" style="margin-top: 10px;">
                    <h3>Segurança</h3>
                    <input type="password" name="senha" id="senha" placeholder="Senha" required minlength="6"
                           class="<?= hasError('senha', $msg ?? '') ?>">
                    
                    <input type="password" name="confirmar_senha" placeholder="Confirmar Senha" required minlength="6"
                           class="<?= hasError('senha', $msg ?? '') ?>"
                           oninput="this.setCustomValidity(this.value !== document.getElementById('senha').value ? 'Senhas não conferem' : '')">
                    
                    <button type="submit">Finalizar Cadastro</button>
                </div>
            </div>

        </form>
        <div class="auth-footer">
            Precisa de ajuda? <a href="mailto:suporte.cupomapp@gmail.com">Fale com o suporte</a>
        </div>
    </div>
        
    <script>

        document.addEventListener('DOMContentLoaded', function() {
            // verifica se houve um post anterior para decidir qual aba abrir
            const oldPerfil = "<?= old('perfil') ?>";
            if (oldPerfil === 'comerciante') {
                toggleForm('comerciante');
            } else {
                toggleForm('associado');
            }
        });

        function toggleForm(type) {
            const wrapper = document.getElementById('mainWrapper');
            const content = document.getElementById('form-content');
            const fieldsAssoc = document.getElementById('fields-associado');
            const fieldsCom = document.getElementById('fields-comerciante');
            
            wrapper.classList.add('expanded');
            content.style.display = 'block';

            // desabilita tudo primeiro
            toggleInputs(fieldsAssoc, false);
            toggleInputs(fieldsCom, false);
            fieldsAssoc.style.display = 'none';
            fieldsCom.style.display = 'none';

            // habilita so aba escolhida
            if (type === 'associado') {
                fieldsAssoc.style.display = 'grid';
                toggleInputs(fieldsAssoc, true);
            } else {
                fieldsCom.style.display = 'grid';
                toggleInputs(fieldsCom, true);
            }
        }

        function toggleInputs(container, enable) {
            const inputs = container.querySelectorAll('input, select');
            inputs.forEach(el => {
                if (enable) {
                    // required
                    el.required = true; 
                    el.disabled = false;
                } else {
                    el.required = false;
                    el.disabled = true;
                }
            });
        }

        // mascara de input
        function mascara(o, f) {
            setTimeout(function() {
                var v = mphone(o.value);
                if (v != o.value) {
                    o.value = v;
                }
            }, 1);
            o.value = f(o.value);
        }

        function mphone(v) { return v; }

        function mtel(v) {
            v = v.replace(/\D/g, ""); 
            v = v.replace(/^(\d{2})(\d)/g, "($1) $2"); 
            v = v.replace(/(\d)(\d{4})$/, "$1-$2"); 
            return v;
        }

        function mcpf(v) {
            v = v.replace(/\D/g, "")
            v = v.replace(/(\d{3})(\d)/, "$1.$2")
            v = v.replace(/(\d{3})(\d)/, "$1.$2")
            v = v.replace(/(\d{3})(\d{1,2})$/, "$1-$2")
            return v
        }

        function mcnpj(v) {
            v = v.replace(/\D/g, "")
            v = v.replace(/^(\d{2})(\d)/, "$1.$2")
            v = v.replace(/^(\d{2})\.(\d{3})(\d)/, "$1.$2.$3")
            v = v.replace(/\.(\d{3})(\d)/, ".$1/$2")
            v = v.replace(/(\d{4})(\d)/, "$1-$2")
            return v
        }

        function mcep(v) {
            v = v.replace(/\D/g, "")
            v = v.replace(/^(\d{5})(\d)/, "$1-$2")
            return v
        }
    </script>
</body>
</html>