<?php
session_start();
include '../config/db.php';
include '../controllers/usuariosController.php';

// Verifica se o usuário é administrador
if (!isset($_SESSION['user_id']) || $_SESSION['user_nivel'] !== 'administrador') {
    header("Location: ../index.php");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: usuarios.php");
    exit();
}

$id = $_GET['id'];
$usuario = $usuarioModel->obterUsuarioPorId($id);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Usuário</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h2 class="text-center">Editar Usuário</h2>
        <a href="/views/usuarios.php" class="btn btn-secondary mb-3">Voltar</a>
        
        <!-- Formulário de edição de usuário -->
        <form method="POST">
            <input type="hidden" name="id" value="<?= $usuario['id'] ?>">
            <div class="row">
                <div class="col-md-4">
                    <label class="form-label">Nome</label>
                    <input type="text" name="nome" class="form-control" value="<?= $usuario['nome'] ?>" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">E-mail</label>
                    <input type="email" name="email" class="form-control" value="<?= $usuario['email'] ?>" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Nova Senha (opcional)</label>
                    <input type="password" name="senha" class="form-control" placeholder="Deixe em branco para manter a mesma">
                </div>
                <div class="col-md-4 mt-3">
                    <label class="form-label">Nível de Acesso</label>
                    <select name="nivel" class="form-select">
                        <option value="cliente" <?= $usuario['nivel'] == 'cliente' ? 'selected' : '' ?>>Cliente</option>
                        <option value="administrador" <?= $usuario['nivel'] == 'administrador' ? 'selected' : '' ?>>Administrador</option>
                    </select>
                </div>
                <div class="col-md-4 mt-4">
                    <button type="submit" name="edit_user" class="btn btn-success w-100">Salvar Alterações</button>
                </div>
            </div>
        </form>
    </div>
</body>
</html>
