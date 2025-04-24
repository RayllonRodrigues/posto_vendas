<?php
session_start();
include '../config/db.php';
include '../controllers/produtosController.php';

// Verifica se o usuário é administrador
if (!isset($_SESSION['user_id']) || $_SESSION['user_nivel'] !== 'administrador') {
    header("Location: ../index.php");
    exit();
}

$produtos = $produtoModel->listarProdutos();

// Debug: Verifica se o formulário está enviando os dados corretamente
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_product'])) {
    echo "<pre>";
    print_r($_POST);
    print_r($_FILES);
    echo "</pre>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciar Produtos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h2 class="text-center">Gerenciar Produtos</h2>
        <a href="../dashboard.php" class="btn btn-secondary mb-3">Voltar</a>
        
        <!-- Formulário para adicionar novo produto -->
        <form method="POST" enctype="multipart/form-data" class="mb-4">
            <h4>Adicionar Novo Produto</h4>
            <div class="row">
                <div class="col-md-3">
                    <input type="text" name="nome" class="form-control" placeholder="Nome" required>
                </div>
                <div class="col-md-3">
                    <input type="text" name="descricao" class="form-control" placeholder="Descrição" required>
                </div>
                <div class="col-md-2">
                    <input type="number" name="preco" class="form-control" placeholder="Preço" step="0.01" required>
                </div>
                <div class="col-md-2">
                    <input type="number" name="quantidade" class="form-control" placeholder="Quantidade" required>
                </div>
                <div class="col-md-2">
                    <select name="categoria" class="form-select">
                        <option value="eletronicos">Leilão</option>
                        <option value="vestuario">Vestuário</option>
                        <option value="alimentos">Alimentos</option>
                    </select>
                </div>
                <div class="col-md-3 mt-2">
                    <input type="file" name="imagem" class="form-control">
                </div>
                <div class="col-md-2 mt-2">
                    <button type="submit" name="add_product" class="btn btn-primary w-100">Adicionar</button>
                </div>
            </div>
        </form>

        <!-- Tabela de produtos -->
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Descrição</th>
                    <th>Preço</th>
                    <th>Quantidade</th>
                    <th>Categoria</th>
                    <th>Imagem</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $produtos->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= $row['nome'] ?></td>
                    <td><?= $row['descricao'] ?></td>
                    <td>R$ <?= number_format($row['preco'], 2, ',', '.') ?></td>
                    <td><?= $row['quantidade'] ?></td>
                    <td><?= ucfirst($row['categoria']) ?></td>
                    <td><img src="data:image/jpeg;base64,<?= base64_encode($row['imagem']) ?>" width="50"></td>
                    <td>
                        <a href="editar_produto.php?id=<?= $row['id'] ?>" class="btn btn-warning btn-sm">Editar</a>
                        <a href="../controllers/produtosController.php?delete=<?= $row['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Tem certeza que deseja excluir este produto?');">Excluir</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
