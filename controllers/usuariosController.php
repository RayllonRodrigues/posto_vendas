<?php
include '../config/db.php';
include '../models/Usuario.php';

$usuarioModel = new Usuario($conn);

// Adicionar novo usuário
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_user'])) {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $senha = $_POST['senha'];
    $nivel = $_POST['nivel'];

    $usuarioModel->adicionarUsuario($nome, $email, $senha, $nivel);
    header("Location: ../views/usuarios.php");
    exit();
}

// Deletar usuário
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $usuarioModel->excluirUsuario($id);
    header("Location: ../views/usuarios.php");
    exit();
}

// Editar usuário
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['edit_user'])) {
    $id = $_POST['id'];
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $senha = $_POST['senha'];
    $nivel = $_POST['nivel'];
    
    $usuarioModel->atualizarUsuario($id, $nome, $email, $senha, $nivel);
    header("Location: ../views/usuarios.php");
    exit();
}

// Obter dados do usuário para edição
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $usuario = $usuarioModel->obterUsuarioPorId($id);
}
