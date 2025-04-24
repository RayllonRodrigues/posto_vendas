<?php
include '../config/db.php';
include '../models/Venda.php';

$vendaModel = new Venda($conn);

$data = json_decode(file_get_contents("php://input"), true);
$cliente_id = $data['cliente_id'];
$itens = $data['itens'];

if ($cliente_id && count($itens) > 0) {
    $venda_id = $vendaModel->registrarVenda($cliente_id, $itens);
    echo json_encode(["status" => "success", "venda_id" => $venda_id]);
} else {
    echo json_encode(["status" => "error"]);
}
?>
