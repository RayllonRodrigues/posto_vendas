<?php
class Usuario {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function listarUsuarios() {
        $query = "SELECT * FROM usuarios";
        return $this->conn->query($query);
    }

    public function adicionarUsuario($nome, $email, $senha, $nivel) {
        $senhaHash = md5($senha); 
        $stmt = $this->conn->prepare("INSERT INTO usuarios (nome, email, senha, nivel) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $nome, $email, $senhaHash, $nivel);
        return $stmt->execute();
    }

    public function excluirUsuario($id) {
        $stmt = $this->conn->prepare("DELETE FROM usuarios WHERE id = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    public function obterUsuarioPorId($id) {
        $stmt = $this->conn->prepare("SELECT * FROM usuarios WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function atualizarUsuario($id, $nome, $email, $senha, $nivel) {
        if ($senha) {
            $senhaHash = md5($senha);
            $stmt = $this->conn->prepare("UPDATE usuarios SET nome=?, email=?, senha=?, nivel=? WHERE id=?");
            $stmt->bind_param("ssssi", $nome, $email, $senhaHash, $nivel, $id);
        } else {
            $stmt = $this->conn->prepare("UPDATE usuarios SET nome=?, email=?, nivel=? WHERE id=?");
            $stmt->bind_param("sssi", $nome, $email, $nivel, $id);
        }
        return $stmt->execute();
    }
}
