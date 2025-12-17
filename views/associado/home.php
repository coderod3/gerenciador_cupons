<!DOCTYPE html>
<html lang="pt-br">
<head>
    <?php include __DIR__ . '/../shared/head-icons.php'; ?>

    <title>Home Associado</title>
    <link rel="stylesheet" href="../../views/assets/css/associado/associado.css">
</head>
<body>
    <main class="page-content">

        <!-- header-->
        <?php include __DIR__ . '/../shared/header.php'; ?>

        <!-- main -->
        <div class="main-wrapper">
            <div class="title-message">
                <h1>Economize em Cada Compra</h1>
                <p>Descubra cupons e ofertas exclusivas de suas lojas favoritas pertinho de você</p>
            </div>

            <!-- msg sucesso -->
            <?php if (!empty($_GET['msg'])): ?>
                <div class="success-banner">
                    <i class="ph ph-check-circle" style="font-size: 24px;"></i>
                    <span><?php echo htmlspecialchars($_GET['msg']); ?></span>
                </div>
            <?php endif; ?>

            <div class="search-section">
                <form method="GET" action="" class="search-form">
                    <input type="text" name="q" id="searchInput" placeholder="Pesquisar cupons, comércios, categorias, descontos..." value="<?php echo htmlspecialchars($_GET['q'] ?? ''); ?>">
                    <input type="hidden" name="categoria" value="<?php echo htmlspecialchars($_GET['categoria'] ?? '0'); ?>">
                    <button type="submit">Buscar</button>
                </form>
            </div>

            <div class="categories-section">
                <div class="filter-title">Categorias</div>
                <div class="categories-list">
                    <a href="<?php echo htmlspecialchars('?categoria=0' . (!empty($_GET['q']) ? '&q='.urlencode($_GET['q']) : '')); ?>" class="category-btn <?php if (empty($categoria_id) || $categoria_id == 0) echo 'active'; ?>">Todas</a>
                    <?php foreach ($categorias as $cat): ?>
                        <a href="<?php echo htmlspecialchars('?categoria=' . $cat['id'] . (!empty($_GET['q']) ? '&q='.urlencode($_GET['q']) : '')); ?>" class="category-btn <?php if (!empty($categoria_id) && $categoria_id == $cat['id']) echo 'active'; ?>">
                            <?php echo htmlspecialchars($cat['nome']); ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="section-title-wrapper">
                <h3 class="section-title">Cupons Disponíveis</h3>
                <?php if (!empty($cupons)): ?>
                    <div class="sort-dropdown">
                        <label for="sortSelect">Ordenar:</label>
                        <select id="sortSelect" onchange="sortCoupons(this.value)">
                            <option value="default">Padrão</option>
                            <option value="discount-high">Maior Desconto</option>
                            <option value="discount-low">Menor Desconto</option>
                            <option value="expiring">Vencendo</option>
                            <option value="quantity-high">Mais Disponíveis</option>
                            <option value="quantity-low">Menos Disponíveis</option>
                        </select>
                    </div>
                <?php endif; ?>
            </div>

            <!-- tabela -->
            <div class="coupons-section">
                <?php
                    // verifica se há um termo de busca na URL
                    if (isset($_GET['q']) && !empty($_GET['q'])) {
                        $termo = mb_strtolower(trim($_GET['q']), 'UTF-8'); // Converte para minúsculo
                        $cupons_filtrados = [];

                        if (!empty($cupons)) {
                            foreach ($cupons as $item) {
                                // junta os dados em uma string para verificar
                                $texto_completo = mb_strtolower(
                                    $item['titulo'] . ' ' . 
                                    $item['nome_fantasia'] . ' ' . 
                                    $item['categoria'] . ' ' . 
                                    $item['percentual_desc'] . '%',
                                    'UTF-8'
                                );

                                // ve se o termo ta na string
                                if (strpos($texto_completo, $termo) !== false) {
                                    $cupons_filtrados[] = $item;
                                }
                            }
                            //atualiza a variável para mostrar o resultado da busca
                            $cupons = $cupons_filtrados;
                        }
                    }
                ?>

                <div class="section-header">
                    <?php if (!empty($cupons)): ?>
                        <div class="coupons-count"><?php echo count($cupons); ?> cupons encontrados</div>
                    <?php endif; ?>
                </div>

                <?php if (!empty($cupons)): ?>
                    <div class="table-wrapper">
                        <table>
                            <thead>
                                <tr>
                                    <th>Cupom</th>
                                    <th>Comércio</th>
                                    <th>Categoria</th>
                                    <th>Disponível</th>
                                    <th>Período de Validade</th>
                                    <th>Desconto</th>
                                    <th>Ação</th>
                                </tr>
                            </thead>
                            <tbody id="couponsTableBody">
                                <?php foreach ($cupons as $row): ?>
                                    <tr data-discount="<?php echo $row['percentual_desc']; ?>" 
                                        data-quantity="<?php echo $row['quantidade']; ?>"
                                        data-end-date="<?php echo strtotime($row['data_termino']); ?>">
                                        <td data-label="Cupom">
                                            <div class="coupon-title"><?php echo htmlspecialchars($row['titulo']); ?></div>
                                        </td>
                                        <td data-label="Comércio">
                                            <span class="store-name"><?php echo htmlspecialchars($row['nome_fantasia']); ?></span>
                                        </td>
                                        <td data-label="Categoria">
                                            <span class="category-badge"><?php echo htmlspecialchars($row['categoria']); ?></span>
                                        </td>
                                        <td data-label="Disponível">
                                            <span class="quantity-badge"><?php echo htmlspecialchars($row['quantidade']); ?> unid.</span>
                                        </td>
                                        <td data-label="Validade">
                                            <div class="validity-text">
                                                <div><?php echo date("d/m/Y", strtotime($row['data_inicio'])); ?></div>
                                                <div>até <?php echo date("d/m/Y", strtotime($row['data_termino'])); ?></div>
                                            </div>
                                        </td>
                                        <td data-label="Desconto">
                                            <span class="discount-badge"><?php echo $row['percentual_desc']; ?>% OFF</span>
                                        </td>
                                        <td data-label="Ação">
                                            <form method="POST" action="reservar.php" class="reserve-form">
                                                <input type="hidden" name="num_cupom" value="<?php echo $row['num_cupom']; ?>">
                                                <button type="submit" class="reserve-btn">Reservar</button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="empty-state">
                        <div class="empty-icon">
                            <img src="https://cdn-icons-png.flaticon.com/512/18432/18432200.png" 
                            alt="lupa" 
                            width="64" 
                            height="64"> 
                        </div>
                        <div class="empty-text">Nenhum resultado encontrado</div>
                        <div class="empty-subtext">Não encontramos cupons para o termo "<strong><?php echo htmlspecialchars($_GET['q'] ?? ''); ?></strong>". Tente outras palavras.</div>
                        <?php if(isset($_GET['q'])): ?>
                            <br>
                            <a href="?" style="text-decoration: none; color: #007bff; font-weight: 600;">Limpar busca e ver todos</a>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>

            <div class="info-section">
                <div class="info-card">
                    <h4><i class="ph-bold ph-lightbulb-filament"> </i> Como Funciona</h4>
                    <p>Escolha o cupom desejado, clique em "Reservar" e utilize-o no estabelecimento parceiro dentro do prazo de validade.</p>
                </div>
                <div class="info-card">
                    <h4><i class="ph-bold ph-timer"> </i> Validade</h4>
                    <p>Fique atento às datas de validade dos cupons. Após reservar, você tem um período limitado para utilização.</p>
                </div>
                <div class="info-card">
                    <h4><i class="ph-bold ph-phone"> </i> Suporte</h4>
                    <p>Dúvidas sobre algum cupom? Entre em contato com o estabelecimento ou nossa equipe de suporte.</p>
                </div>
            </div>

        </div>

    </main>
    


    <script>
        // ordenação dos cupons
        function sortCoupons(sortType) {
            const tbody = document.getElementById('couponsTableBody');
            const rows = Array.from(tbody.querySelectorAll('tr'));
            
            rows.sort((a, b) => {
                switch(sortType) {
                    case 'discount-high':
                        return parseInt(b.dataset.discount) - parseInt(a.dataset.discount);
                    
                    case 'discount-low':
                        return parseInt(a.dataset.discount) - parseInt(b.dataset.discount);
                    
                    case 'expiring':
                        return parseInt(a.dataset.endDate) - parseInt(b.dataset.endDate);
                    
                    case 'quantity-high':
                        return parseInt(b.dataset.quantity) - parseInt(a.dataset.quantity);
                    
                    case 'quantity-low':
                        return parseInt(a.dataset.quantity) - parseInt(b.dataset.quantity);
                    
                    default:
                        return 0;
                }
            });
            
            // reordenar as linhas na tabela
            rows.forEach(row => tbody.appendChild(row));
        }
        
    </script>

    <?php include __DIR__ . '/../shared/footer.php'; ?>

</body>
</html>