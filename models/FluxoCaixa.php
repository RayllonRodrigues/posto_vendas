<?php
class FluxoCaixa {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    // Adicionar movimentação
    public function adicionarMovimentacao($tipo, $descricao, $valor) {
        $stmt = $this->conn->prepare("INSERT INTO fluxo_caixa (tipo, descricao, valor) VALUES (?, ?, ?)");
        $stmt->bind_param("ssd", $tipo, $descricao, $valor);
        return $stmt->execute();
    }

    // Obter todas as movimentações
    public function listarMovimentacoes($tipo = '', $data_inicio = '', $data_fim = '', $descricao = '') {
        $query = "SELECT id, descricao, tipo, valor, data_registro FROM fluxo_caixa WHERE 1=1";
    
        if (!empty($tipo)) {
            $query .= " AND tipo = ?";
        }
    
        if (!empty($data_inicio) && !empty($data_fim)) {
            $query .= " AND DATE(data_registro) BETWEEN ? AND ?";
        }
    
        if (!empty($descricao)) {
            $query .= " AND descricao LIKE ?";
        }
    
        $query .= " ORDER BY data_registro DESC";
    
        $stmt = $this->conn->prepare($query);
    
        if (!empty($tipo) && !empty($data_inicio) && !empty($data_fim) && !empty($descricao)) {
            $descricao = "%$descricao%";
            $stmt->bind_param("ssss", $tipo, $data_inicio, $data_fim, $descricao);
        } elseif (!empty($tipo) && !empty($data_inicio) && !empty($data_fim)) {
            $stmt->bind_param("sss", $tipo, $data_inicio, $data_fim);
        } elseif (!empty($tipo) && !empty($descricao)) {
            $descricao = "%$descricao%";
            $stmt->bind_param("ss", $tipo, $descricao);
        } elseif (!empty($data_inicio) && !empty($data_fim) && !empty($descricao)) {
            $descricao = "%$descricao%";
            $stmt->bind_param("sss", $data_inicio, $data_fim, $descricao);
        } elseif (!empty($tipo)) {
            $stmt->bind_param("s", $tipo);
        } elseif (!empty($data_inicio) && !empty($data_fim)) {
            $stmt->bind_param("ss", $data_inicio, $data_fim);
        } elseif (!empty($descricao)) {
            $descricao = "%$descricao%";
            $stmt->bind_param("s", $descricao);
        }
    
        $stmt->execute();
        return $stmt->get_result();
    }
    

    // Obter saldo atual
    public function obterSaldo() {
        $query = "SELECT 
                    SUM(CASE WHEN tipo = 'entrada' THEN valor ELSE 0 END) AS total_entrada, 
                    SUM(CASE WHEN tipo = 'saida' THEN valor ELSE 0 END) AS total_saida 
                  FROM fluxo_caixa";
        $result = $this->conn->query($query);
        return $result->fetch_assoc();
    }

    
}
?>
