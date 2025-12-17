<?php

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

session_start();
require_once __DIR__ . '/../db/db_connection.php';
require_once __DIR__ . '/../controllers/RecuperarSenhaController.php';


$controller = new RecuperarSenhaController($conn);

$action = $_GET['action'] ?? 'solicitarEmail';

if ($action === 'solicitarEmail') {
    $controller->solicitarEmail();
} elseif ($action === 'confirmarCodigo') {
    $controller->confirmarCodigo();
} elseif ($action === 'definirNovaSenha') {
    $controller->definirNovaSenha();
}
