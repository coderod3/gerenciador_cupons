<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Home Associado</title>
    <link rel="stylesheet" href="../../views/assets/css/associado.css">
</head>
<body>
    <h2>Bem-vindo, <?php echo htmlspecialchars($_SESSION['nome']); ?></h2>
    <p><a href="../logout.php">Sair</a> | <a href="meus_cupons.php">Meus Cupons</a></p>

    <?php if (!empty($_GET['msg'])): ?>
        <p style="color:green;"><?php echo htmlspecialchars($_GET['msg']); ?></p>
    <?php endif; ?>

    <h3>Filtrar por categoria</h3>
    <form method="GET" action="">
        <select name="categoria">
            <option value="0">Todas</option>
            <?php foreach ($categorias as $cat): ?>
                <option value="<?php echo $cat['id']; ?>"
                    <?php if (!empty($categoria_id) && $categoria_id == $cat['id']) echo 'selected'; ?>>
                    <?php echo htmlspecialchars($cat['nome']); ?>
                </option>
            <?php endforeach; ?>
        </select>
        <button type="submit">Filtrar</button>
    </form>

    <h3>Cupons disponíveis para você</h3>
    <?php if (!empty($cupons)): ?>
        <table border="1" cellpadding="5">
            <tr>
                <th>Título</th>
                <th>Comércio</th>
                <th>Categoria</th>
                <th>Quantidade</th>
                <th>Validade</th>
                <th>Desconto</th>
                <th>Reserva</th>
            </tr>
            <?php foreach ($cupons as $row): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['titulo']); ?></td>
                    <td><?php echo htmlspecialchars($row['nome_fantasia']); ?></td>
                    <td><?php echo htmlspecialchars($row['categoria']); ?></td>
                    <td><?php echo htmlspecialchars($row['quantidade']); ?></td>
                    <td>
                        <?php 
                            echo date("d-m-Y", strtotime($row['data_inicio'])) 
                                 . " até " . 
                                 date("d-m-Y", strtotime($row['data_termino'])); 
                        ?>
                    </td>
                    <td><?php echo $row['percentual_desc'] . "%"; ?></td>
                    <td>
                        <form method="POST" action="reservar.php">
                            <input type="hidden" name="num_cupom" value="<?php echo $row['num_cupom']; ?>">
                            <button type="submit">Reservar</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php else: ?>
        <p>Nenhum cupom disponível no momento.</p>
    <?php endif; ?>
</body>
</html>
