<!DOCTYPE html>
<html lang="pt-br">
<head>
    <?php include __DIR__ . '/../shared/head-icons.php'; ?>
    
    <title>Home Comerciante</title>
    <link rel="stylesheet" href="../../views/assets/css/comerciante/comerciante.css">
</head>
<body>

    <?php include __DIR__ . '/../shared/header_comerciante.php'; ?>

    <div class="main-wrapper">
        <div class="page-header">
            <h1>Gerenciar Cupons</h1>
            <p>Acompanhe e gerencie todos os cupons do seu estabelecimento</p>
        </div>

        <?php if (!empty($_GET['msg'])): ?>
            <div class="message success">
                <i class="ph ph-check" style="font-size: 24px;"></i>
                <span><?php echo htmlspecialchars($_GET['msg']); ?></span>
            </div>
        <?php endif; ?>

        <?php if (!empty($_GET['err'])): ?>
            <div class="message error">
                <i class="ph ph-warning" style="font-size: 24px;"></i>
                <span><?php echo htmlspecialchars($_GET['err']); ?></span>
            </div>
        <?php endif; ?>

        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-number"><?php echo count($cupons); ?></div>
                <div class="stat-label">Total de Cupons</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">
                    <?php 
                    $ativos = 0;
                    $hoje = date('Y-m-d');
                    foreach ($cupons as $c) {
                        if ($hoje >= $c['data_inicio'] && $hoje <= $c['data_termino'] && (int)$c['disponiveis'] > 0) {
                            $ativos++;
                        }
                    }
                    echo $ativos;
                    ?>
                </div>
                <div class="stat-label">Cupons Ativos</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">
                    <?php 
                    $total_reservados = 0;
                    foreach ($cupons as $c) {
                        $total_reservados += (int)$c['reservados'];
                    }
                    echo $total_reservados;
                    ?>
                </div>
                <div class="stat-label">Cupons Reservados</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">
                    <?php 
                    $total_utilizados = 0;
                    foreach ($cupons as $c) {
                        $total_utilizados += (int)$c['utilizados'];
                    }
                    echo $total_utilizados;
                    ?>
                </div>
                <div class="stat-label">Cupons Utilizados</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">
                    <?php 
                    $total_disponiveis = 0;
                    foreach ($cupons as $c) {
                        $total_disponiveis += (int)$c['disponiveis'];
                    }
                    echo $total_disponiveis;
                    ?>
                </div>
                <div class="stat-label">Cupons Disponíveis</div>
            </div>
        </div>

        <div class="filter-section">
            <div class="filter-title">
                <img src="https://cdn-icons-png.flaticon.com/512/18432/18432200.png" 
                    alt="lupa" 
                    width="20" 
                    height="20"> 
                Filtrar e Pesquisar
            </div>

            <form method="GET" action="" class="filter-form">
                <div class="form-group">
                    <label for="filtro">Status</label>
                    <select name="filtro" id="filtro">
                        <option value="" <?php if(empty($filtro)) echo 'selected'; ?>>Todos</option>
                        <option value="ativos" <?php if($filtro==='ativos') echo 'selected'; ?>>Ativos</option>
                        <option value="esgotados" <?php if($filtro==='esgotados') echo 'selected'; ?>>Esgotados</option>
                        <option value="vencidos" <?php if($filtro==='vencidos') echo 'selected'; ?>>Vencidos</option>
                        <option value="agendados" <?php if($filtro==='agendados') echo 'selected'; ?>>Agendados</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="searchInput">Pesquisar</label>
                    <input type="text" name="q" id="searchInput" placeholder="Título ou número do cupom" 
                           value="<?php echo isset($_GET['q']) ? htmlspecialchars($_GET['q']) : ''; ?>">
                </div>
                <button type="submit">Aplicar Filtros</button>
            </form>
        </div>

        <h3 class="section-title">Meus Cupons</h3>

        <div class="coupons-section">
            <?php if (!empty($cupons)): ?>
                <div class="table-wrapper">
                    <table>
                        <thead>
                            <tr>
                                <th>Número</th>
                                <th>Título</th>
                                <th>Validade</th>
                                <th>Desconto</th>
                                <th>Total</th>
                                <th>Reservados</th>
                                <th>Utilizados</th>
                                <th>Disponíveis</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($cupons as $c): ?>
                                <tr>
                                    <td data-label="Número">
                                        <span class="coupon-number"><?php echo htmlspecialchars($c['num_cupom']); ?></span>
                                    </td>
                                    <td data-label="Título">
                                        <span class="coupon-title"><?php echo htmlspecialchars($c['titulo']); ?></span>
                                    </td>
                                    <td data-label="Validade">
                                        <div class="validity-text">
                                            <div><?php echo date("d/m/Y", strtotime($c['data_inicio'])); ?></div>
                                            <div>até <?php echo date("d/m/Y", strtotime($c['data_termino'])); ?></div>
                                        </div>
                                    </td>
                                    <td data-label="Desconto">
                                        <span class="discount-badge"><?php echo $c['percentual_desc'] . "%"; ?></span>
                                    </td>
                                    <td data-label="Total">
                                        <span class="quantity-cell"><?php echo (int)$c['total']; ?></span>
                                    </td>
                                    <td data-label="Reservados">
                                        <span class="quantity-cell"><?php echo (int)$c['reservados']; ?></span>
                                    </td>
                                    <td data-label="Utilizados">
                                        <span class="quantity-cell"><?php echo (int)$c['utilizados']; ?></span>
                                    </td>
                                    <td data-label="Disponíveis">
                                        <span class="quantity-cell"><?php echo (int)$c['disponiveis']; ?></span>
                                    </td>
                                    <td data-label="Status">
                                        <?php
                                        $hoje = date('Y-m-d');
                                        if ($hoje < $c['data_inicio']) {
                                            echo '<span class="status-badge agendado">Agendado</span>';
                                        } elseif ($hoje > $c['data_termino']) {
                                            echo '<span class="status-badge vencido">Vencido</span>';
                                        } elseif ((int)$c['disponiveis'] <= 0) {
                                            echo '<span class="status-badge esgotado">Esgotado</span>';
                                        } else {
                                            echo '<span class="status-badge ativo">Ativo</span>';
                                        }
                                        ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="empty-state">
                    <div class="empty-icon">
                        <i class="ph ph-clipboard-text"></i>
                    </div>
                    <div class="empty-text">Nenhum cupom encontrado</div>
                    <div class="empty-subtext">Tente ajustar os filtros ou cadastre um novo cupom</div>
                </div>
            <?php endif; ?>
        </div>
    </div>
    
    <?php include __DIR__ . '/../shared/footer.php'; ?>

</body>
</html>