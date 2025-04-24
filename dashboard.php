<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_nivel'] !== 'administrador') {
    header("Location: index.php");
    exit();
}

include 'config/db.php';
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel Administrativo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-dark bg-dark">
    <div class="container">
            <a class="navbar-brand" href="/posto/dashboard.php">Painel do Administrador</a>
            <a href="logout.php" class="btn btn-danger">Sair</a>
        </div>
    </nav>

    <div class="container mt-4">
        <h3>Bem-vindo, <?php echo $_SESSION['user_name']; ?>!</h3>
        <a href="views/produtos.php" class="btn btn-primary mt-2">Gerenciar Produtos</a>
        <a href="views/caixa.php" class="btn btn-primary mt-2">Gerenciar Vendas</a>
        <a href="views/fluxo_caixa.php" class="btn btn-primary mt-2">Fluxo de Caixa</a>
        <a href="views/usuarios.php" class="btn btn-primary mt-2">Gerenciar Usuários</a>
    </div>

    
</body>
</html>
