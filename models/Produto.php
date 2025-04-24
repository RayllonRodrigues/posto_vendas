<?php
class Produto {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function listarProdutos() {
        $query = "SELECT * FROM produtos";
        return $this->conn->query($query);
    }

    public function adicionarProduto($nome, $descricao, $preco, $quantidade, $categoria, $imagem) {
            // Inserindo o produto na tabela produtos
            $stmt = $this->conn->prepare("INSERT INTO produtos (nome, descricao, preco, quantidade, categoria, imagem) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssdiss", $nome, $descricao, $preco, $quantidade, $categoria, $imagem);
            
            if (!$stmt->execute()) {
                die("Erro ao adicionar produto: " . $stmt->error);
            }
            
            // Calculando o total de entrada
            $total = $quantidade * $preco;
            $tipo_entrada = "entrada";
            $descricao_entrada = "Entrada no estoque: " . $nome;
            
            // Inserindo a entrada no fluxo de caixa
            $stmt = $this->conn->prepare("INSERT INTO fluxo_caixa (descricao, tipo, valor) VALUES (?, ?, ?)");
            $stmt->bind_param("ssd", $descricao_entrada, $tipo_entrada, $total);
            
            if (!$stmt->execute()) {
                die("Erro ao registrar entrada no fluxo de caixa: " . $stmt->error);
            }
            
            return true;
        
    }
    
    

    public function excluirProduto($id) {
        $stmt = $this->conn->prepare("DELETE FROM produtos WHERE id = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    public function obterProdutoPorId($id) {
        $stmt = $this->conn->prepare("SELECT * FROM produtos WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function atualizarProduto($id, $nome, $descricao, $preco, $quantidade, $categoria, $imagem) {
        if ($imagem) {
            $stmt = $this->conn->prepare("UPDATE produtos SET nome=?, descricao=?, preco=?, quantidade=?, categoria=?, imagem=? WHERE id=?");
            $stmt->bind_param("ssdissi", $nome, $descricao, $preco, $quantidade, $categoria, $imagem, $id);
        } else {
            $stmt = $this->conn->prepare("UPDATE produtos SET nome=?, descricao=?, preco=?, quantidade=?, categoria=? WHERE id=?");
            $stmt->bind_param("ssdisi", $nome, $descricao, $preco, $quantidade, $categoria, $id);
        }
        return $stmt->execute();
    }
}
