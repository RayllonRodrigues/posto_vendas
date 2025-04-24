<?php
session_start();
include '../config/db.php';
include '../controllers/usuariosController.php';


// Verifica se o usuário é administrador
if (!isset($_SESSION['user_id']) || $_SESSION['user_nivel'] !== 'administrador') {
    header("Location: ../index.php");
    exit();
}

$usuarios = $usuarioModel->listarUsuarios();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciar Usuários</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h2 class="text-center">Gerenciar Usuários</h2>
        <a href="/posto/dashboard.php" class="btn btn-secondary mb-3">Voltar</a>
        
        <!-- Formulário para adicionar novo usuário -->
        <form method="POST" class="mb-4">
            <h4>Adicionar Novo Usuário</h4>
            <div class="row">
                <div class="col-md-3">
                    <input type="text" name="nome" class="form-control" placeholder="Nome" required>
                </div>
                <div class="col-md-3">
                    <input type="email" name="email" class="form-control" placeholder="E-mail" required>
                </div>
                <div class="col-md-2">
                    <input type="password" name="senha" class="form-control" placeholder="Senha" required>
                </div>
                <div class="col-md-2">
                    <select name="nivel" class="form-select">
                        <option value="cliente">Cliente</option>
                        <option value="administrador">Administrador</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" name="add_user" class="btn btn-primary w-100">Adicionar</button>
                </div>
            </div>
        </form>

        <!-- Tabela de usuários -->
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Email</th>
                    <th>Nível</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $usuarios->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= $row['nome'] ?></td>
                    <td><?= $row['email'] ?></td>
                    <td><?= ucfirst($row['nivel']) ?></td>
                    <td>
                        <a href="editar_usuario.php?id=<?= $row['id'] ?>" class="btn btn-warning btn-sm">Editar</a>
                        <a href="../controllers/usuariosController.php?delete=<?= $row['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Tem certeza que deseja excluir este usuário?');">Excluir</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
