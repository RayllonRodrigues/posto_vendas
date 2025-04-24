<?php
class Venda {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function registrarVenda($cliente_id, $itens) {
        $total = 0;
        foreach ($itens as $item) {
            $total += $item['preco'] * $item['quantidade'];
        }

        $stmt = $this->conn->prepare("INSERT INTO vendas (usuario_id, total, status) VALUES (?, ?, 'debito')");
        $stmt->bind_param("id", $cliente_id, $total);
        $stmt->execute();
        $venda_id = $this->conn->insert_id;

        foreach ($itens as $item) {
            $stmt = $this->conn->prepare("INSERT INTO itens_venda (venda_id, produto_id, quantidade, preco_unitario) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("iiid", $venda_id, $item['id'], $item['quantidade'], $item['preco']);
            $stmt->execute();
        }

        return $venda_id;
    }
}
?>
