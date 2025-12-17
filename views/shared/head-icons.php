<?php
    // logica para corrigir o caminho dependendo de quem chamou o arquivo
    // se a url atual termina em subpasta ex /associado, volta duas vezes
    // se esta na raiz do public ex index volta uma vez

    $pasta_atual = basename(getcwd()); // nome da pasta

    if ($pasta_atual === 'associado' || $pasta_atual === 'comercio' || $pasta_atual === 'recuperar_senha') {
        $path = '../../views/assets'; 
    } else {
        $path = '../views/assets';
    }
?>

<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">

<link rel="icon" type="image/png" sizes="32x32" href="<?php echo $path; ?>/icons/icon-32.png">
<link rel="icon" type="image/png" sizes="64x64" href="<?php echo $path; ?>/icons/icon-64.png">
<link rel="apple-touch-icon" sizes="180x180" href="<?php echo $path; ?>/icons/icon-64.png">
<link rel="manifest" href="<?php echo $path; ?>/manifest.json">

<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-title" content="CupomApp">
<meta name="theme-color" content="#3c49da">
<meta name="msapplication-navbutton-color" content="#3c49da">
<meta name="apple-mobile-web-app-status-bar-style" content="#3c49da">
<script src="https://unpkg.com/@phosphor-icons/web"></script>


<!-- <title>CupomApp</title> -->

<style>
    html, body {
        margin: 0;
        padding: 0;
    }
    /* alinhamento vertical para ícones Phosphor */
    i.ph {
        vertical-align: text-bottom;
        display: inline-block;
    }
</style>