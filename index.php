<?php
session_start();
include 'config/db.php';

// Consulta para buscar produtos disponíveis
$query = "SELECT id, nome, descricao, preco, quantidade, categoria, imagem FROM produtos WHERE quantidade > 0";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Posto de Vendas - IFTO Araguatins</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .card img { height: 200px; object-fit: cover; }
        .filter-section { background: #f8f9fa; padding: 20px; border-radius: 10px; box-shadow: 0px 0px 10px rgba(0,0,0,0.1); }
        .filter-section h5 { margin-bottom: 15px; }
        .filter-group { display: flex; gap: 15px; }
        .filter-group select { flex: 1; }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="index.php">Posto de Vendas - IFTO Araguatins</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <?php if(isset($_SESSION['user_id'])): ?>
                        <li class="nav-item"><a class="nav-link" href="dashboard.php">Painel</a></li>
                        <li class="nav-item"><a class="nav-link" href="logout.php">Sair</a></li>
                    <?php else: ?>
                        <li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <h2 class="text-center mb-4">Pesquise o seu Produtos</h2>
        
        <!-- Campo de pesquisa -->
        <div class="row mb-3">
            <div class="col-md-6 offset-md-3">
                <input type="text" id="search" class="form-control" placeholder="Pesquisar produtos...">
            </div>
        </div>

        <!-- Seção de filtros estilizada -->
        <div class="row mb-4">
            <div class="col-md-5">
                <div class="filter-section">
                    <h5>Filtrar por:</h5>
                    <div class="filter-group">
                        <select id="categoryFilter" class="form-select">
                            <option value="">Todas as categorias</option>
                            <option value="eletronicos">Eletrônicos</option>
                            <option value="vestuario">Vestuário</option>
                            <option value="alimentos">Alimentos</option>
                        </select>
                        <select id="priceFilter" class="form-select">
                            <option value="">Todos os preços</option>
                            <option value="low">Menos de R$ 50</option>
                            <option value="medium">R$ 50 - R$ 200</option>
                            <option value="high">Mais de R$ 200</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <div class="row" id="productList">
            <?php while($row = $result->fetch_assoc()): ?>
                <div class="col-md-3 mb-3 product-card" data-category="<?= $row['categoria'] ?>" data-price="<?= $row['preco'] ?>">
                    <div class="card">
                        <img src="data:image/jpeg;base64,<?= base64_encode($row['imagem']) ?>" class="card-img-top" alt="<?= $row['nome'] ?>">
                        <div class="card-body">
                            <h5 class="card-title"><?= $row['nome'] ?></h5>
                            <p class="card-text">Preço: R$ <?= number_format($row['preco'], 2, ',', '.') ?></p>
                            <p class="card-text">Disponível: <?= $row['quantidade'] ?> unidades</p>
                            <a href="login.php" class="btn btn-primary">Comprar</a>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>

    <footer class="bg-dark text-light text-center py-3 mt-4">
        <p>&copy; <?php echo date('Y'); ?> Posto de Vendas - IFTO Araguatins</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('search').addEventListener('input', function() {
            let searchValue = this.value.toLowerCase();
            document.querySelectorAll('.product-card').forEach(card => {
                let productName = card.querySelector('.card-title').textContent.toLowerCase();
                card.style.display = productName.includes(searchValue) ? '' : 'none';
            });
        });
        
        document.getElementById('categoryFilter').addEventListener('change', function() {
            let filterValue = this.value;
            document.querySelectorAll('.product-card').forEach(card => {
                let category = card.getAttribute('data-category');
                card.style.display = (filterValue === '' || category === filterValue) ? '' : 'none';
            });
        });

        document.getElementById('priceFilter').addEventListener('change', function() {
            let filterValue = this.value;
            document.querySelectorAll('.product-card').forEach(card => {
                let price = parseFloat(card.getAttribute('data-price'));
                let show = false;
                if (filterValue === '' || 
                   (filterValue === 'low' && price < 50) ||
                   (filterValue === 'medium' && price >= 50 && price <= 200) ||
                   (filterValue === 'high' && price > 200)) {
                    show = true;
                }
                card.style.display = show ? '' : 'none';
            });
        });
    </script>
</body>
</html>
