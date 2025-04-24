<?php
include '../config/db.php';
include '../models/Produto.php';
include '../models/FluxoCaixa.php'; // Importa o modelo do fluxo de caixa

$produtoModel = new Produto($conn);
$fluxoCaixa = new FluxoCaixa($conn);

// Adicionar novo produto e registrar custo no fluxo de caixa
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_product'])) {
    $nome = $_POST['nome'];
    $descricao = $_POST['descricao'];
    $preco = $_POST['preco'];
    $quantidade = $_POST['quantidade'];
    $categoria = $_POST['categoria'];
    $custo = $_POST['custo']; // Custo de compra do produto
    $imagem = file_get_contents($_FILES['imagem']['tmp_name']);

    if ($produtoModel->adicionarProduto($nome, $descricao, $preco, $quantidade, $categoria, $imagem)) {
        // Registra a saída no fluxo de caixa
        if ($custo > 0) {
            $descricao_saida = "Compra de estoque: " . $nome;
            $fluxoCaixa->adicionarMovimentacao('saida', $descricao_saida, $custo);
        }
        header("Location: ../views/produtos.php");
        exit();
    } else {
        die("Erro ao adicionar produto.");
    }
}

// Deletar produto
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    if ($produtoModel->excluirProduto($id)) {
        header("Location: ../views/produtos.php");
        exit();
    } else {
        die("Erro ao excluir produto.");
    }
}

// Editar produto
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['edit_product'])) {
    $id = $_POST['id'];
    $nome = $_POST['nome'];
    $descricao = $_POST['descricao'];
    $preco = $_POST['preco'];
    $quantidade = $_POST['quantidade'];
    $categoria = $_POST['categoria'];
    
    $imagem = isset($_FILES['imagem']['tmp_name']) && !empty($_FILES['imagem']['tmp_name']) ? file_get_contents($_FILES['imagem']['tmp_name']) : null;
    
    if ($produtoModel->atualizarProduto($id, $nome, $descricao, $preco, $quantidade, $categoria, $imagem)) {
        header("Location: ../views/produtos.php");
        exit();
    } else {
        die("Erro ao editar produto.");
    }
}

// Obter dados do produto para edição
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $produto = $produtoModel->obterProdutoPorId($id);
}
