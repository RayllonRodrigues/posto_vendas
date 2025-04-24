<?php
session_start();
include '../config/db.php';
include '../controllers/produtosController.php';

// Verifica se o usuário é administrador
if (!isset($_SESSION['user_id']) || $_SESSION['user_nivel'] !== 'administrador') {
    header("Location: ../index.php");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: produtos.php");
    exit();
}

$id = $_GET['id'];
$produto = $produtoModel->obterProdutoPorId($id);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Produto</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h2 class="text-center">Editar Produto</h2>
        <a href="produtos.php" class="btn btn-secondary mb-3">Voltar</a>
        
        <!-- Formulário de edição de produto -->
        <form method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?= $produto['id'] ?>">
            <div class="row">
                <div class="col-md-3">
                    <label class="form-label">Nome</label>
                    <input type="text" name="nome" class="form-control" value="<?= $produto['nome'] ?>" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Descrição</label>
                    <input type="text" name="descricao" class="form-control" value="<?= $produto['descricao'] ?>" required>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Preço</label>
                    <input type="number" name="preco" class="form-control" step="0.01" value="<?= $produto['preco'] ?>" required>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Quantidade</label>
                    <input type="number" name="quantidade" class="form-control" value="<?= $produto['quantidade'] ?>" required>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Categoria</label>
                    <select name="categoria" class="form-select">
                        <option value="eletronicos" <?= $produto['categoria'] == 'eletronicos' ? 'selected' : '' ?>>Eletrônicos</option>
                        <option value="vestuario" <?= $produto['categoria'] == 'vestuario' ? 'selected' : '' ?>>Vestuário</option>
                        <option value="alimentos" <?= $produto['categoria'] == 'alimentos' ? 'selected' : '' ?>>Alimentos</option>
                    </select>
                </div>
                <div class="col-md-3 mt-3">
                    <label class="form-label">Imagem Atual</label><br>
                    <img src="data:image/jpeg;base64,<?= base64_encode($produto['imagem']) ?>" width="100">
                </div>
                <div class="col-md-3 mt-3">
                    <label class="form-label">Nova Imagem (opcional)</label>
                    <input type="file" name="imagem" class="form-control">
                </div>
                <div class="col-md-3 mt-4">
                    <button type="submit" name="edit_product" class="btn btn-success w-100">Salvar Alterações</button>
                </div>
            </div>
        </form>
    </div>
</body>
</html>