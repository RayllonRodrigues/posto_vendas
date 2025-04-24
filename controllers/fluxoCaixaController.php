<?php
include '../config/db.php';
include '../models/FluxoCaixa.php';

$fluxoCaixa = new FluxoCaixa($conn);

// Inicializando variáveis de filtro
$tipo = isset($_GET['tipo']) ? $_GET['tipo'] : '';
$data_inicio = isset($_GET['data_inicio']) ? $_GET['data_inicio'] : '';
$data_fim = isset($_GET['data_fim']) ? $_GET['data_fim'] : '';
$descricao = isset($_GET['descricao']) ? $_GET['descricao'] : '';

$movimentacoes = $fluxoCaixa->listarMovimentacoes($tipo, $data_inicio, $data_fim, $descricao);
$saldo = $fluxoCaixa->obterSaldo();

// Adicionar movimentação
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_movimentacao'])) {
    $tipo = $_POST['tipo'];
    $descricao = $_POST['descricao'];
    $valor = $_POST['valor'];

    if ($fluxoCaixa->adicionarMovimentacao($tipo, $descricao, $valor)) {
        header("Location: ../views/fluxo_caixa.php");
        exit();
    } else {
        die("Erro ao registrar movimentação.");
    }
}




?>
