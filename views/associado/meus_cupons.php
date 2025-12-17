<!DOCTYPE html>
<html lang="pt-br">
<head>
    <?php include __DIR__ . '/../shared/head-icons.php'; ?>
    <title>Meus Cupons</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <link rel="stylesheet" href="../../views/assets/css/associado/meus_cupons.css">
</head>
<body>
    <?php include __DIR__ . '/../shared/header.php'; ?>

    <div class="page-wrapper">
        <div class="app-container">
            <main class="main-content">
                <!-- filtro -->
                <section class="filter-card">
                    <form method="GET" action="" class="filter-form">
                        <div class="input-group">
                            <label>Status</label>
                            <div class="select-wrapper">
                                <select name="filtro">
                                    <option value="todos" <?php if($filtro==='todos') echo 'selected'; ?>>Todos</option>
                                    <option value="ativos" <?php if($filtro==='ativos') echo 'selected'; ?>>Ativos</option>
                                    <option value="utilizados" <?php if($filtro==='utilizados') echo 'selected'; ?>>Utilizados</option>
                                    <option value="vencidos" <?php if($filtro==='vencidos') echo 'selected'; ?>>Vencidos</option>
                                </select>
                            </div>
                        </div>

                        <div class="input-group">
                            <label>Categoria</label>
                            <div class="select-wrapper">
                                <select name="categoria">
                                    <option value="0">Todas</option>
                                    <?php foreach ($categorias as $cat): ?>
                                        <option value="<?php echo $cat['id']; ?>" <?php if($categoria_id==$cat['id']) echo 'selected'; ?>>
                                            <?php echo htmlspecialchars($cat['nome']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <button type="submit" class="btn-filter">
                            <i class="ph ph-funnel"></i> Filtrar
                        </button>
                    </form>
                </section>

                <!-- tabela -->
                <section class="results-card">
                    <?php if (!empty($meus_cupons)): ?>
                        <div class="table-responsive">
                            <table>
                                <thead>
                                    <tr>
                                        <th>Cód. Reserva</th>
                                        <th>Título</th>
                                        <th>Comércio</th>
                                        <th>Validade</th>
                                        <th>Desconto</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($meus_cupons as $c): 
                                        // texto do status
                                        $statusClass = '';
                                        $statusLabel = '';
                                        
                                        if (!empty($c['data_uso'])) {
                                            $statusLabel = "Utilizado";
                                            $statusClass = "status-used";
                                        } elseif ($c['data_termino'] < date('Y-m-d')) {
                                            $statusLabel = "Vencido";
                                            $statusClass = "status-expired";
                                        } else {
                                            $statusLabel = "Ativo";
                                            $statusClass = "status-active";
                                        }
                                    ?>
                                    
                                    <tr>
                                        <td class="font-mono"><?php echo htmlspecialchars($c['codigo_reserva']); ?></td>
                                        <td>
                                            <div class="coupon-info">
                                                <span class="coupon-title"><?php echo htmlspecialchars($c['titulo']); ?></span>
                                                <span class="coupon-category"><?php echo htmlspecialchars($c['categoria']); ?></span>
                                            </div>
                                        </td>
                                        <td><?php echo htmlspecialchars($c['nome_fantasia']); ?></td>
                                        <td><?php echo date("d/m/Y", strtotime($c['data_termino'])); ?></td>
                                        <td><span class="discount-badge">-<?php echo $c['percentual_desc']; ?>%</span></td>
                                        <td>
                                            <span class="status-badge <?php echo $statusClass; ?>">
                                                <?php echo $statusLabel; ?>
                                            </span>
                                            <?php if(!empty($c['data_uso'])): ?>
                                                <small class="usage-date">em <?php echo date("d/m", strtotime($c['data_uso'])); ?></small>
                                            <?php endif; ?>
                                        </td>
                                    </tr>

                                    <?php endforeach; ?>

                                </tbody>
                            </table>

                        </div>

                    <?php else: ?>
                        <div class="empty-state">
                            <i class="ph ph-ticket generic-icon"></i>
                            <h3>Nenhum cupom encontrado</h3>
                            <p>Tente mudar os filtros ou reserve novos cupons.</p>
                        </div>
                    <?php endif; ?>

                </section>
            </main>
            
        </div>

    </div>

    <!-- footer-->
    <?php include __DIR__ . '/../shared/footer.php'; ?>
    
</body>
</html>