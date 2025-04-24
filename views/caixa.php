<?php
session_start();
include '../config/db.php';

// Buscar todos os clientes
$clientes = $conn->query("SELECT id, nome FROM usuarios WHERE nivel = 'cliente'");

// Buscar todos os produtos
$produtos = $conn->query("SELECT id, nome, preco, quantidade FROM produtos WHERE quantidade > 0");
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Caixa - Registrar Venda</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h2 class="text-center">Registrar Venda</h2>

        <!-- Buscar Cliente -->
        <div class="mb-3">
            <label class="form-label">Buscar Cliente:</label>
            <input type="text" id="buscarCliente" class="form-control" placeholder="Digite o nome do cliente">
        </div>
        <div class="mb-3">
            <label class="form-label">Selecionar Cliente:</label>
            <select id="cliente" class="form-select">
                <option value="">Selecione o cliente</option>
                <?php while ($cliente = $clientes->fetch_assoc()): ?>
                    <option value="<?= $cliente['id'] ?>"><?= $cliente['nome'] ?></option>
                <?php endwhile; ?>
            </select>
        </div>

        <!-- Buscar Produto -->
        <h4>Buscar Produto:</h4>
        <input type="text" id="buscarProduto" class="form-control mb-3" placeholder="Digite o nome do produto">

        <!-- Selecionar Produtos -->
        <table class="table" id="tabelaProdutos">
            <thead>
                <tr>
                    <th>Produto</th>
                    <th>Preço</th>
                    <th>Quantidade</th>
                    <th>Adicionar</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($produto = $produtos->fetch_assoc()): ?>
                <tr class="produto">
                    <td><?= $produto['nome'] ?></td>
                    <td>R$ <?= number_format($produto['preco'], 2, ',', '.') ?></td>
                    <td><input type="number" min="1" max="<?= $produto['quantidade'] ?>" value="1" class="form-control quantidade" data-id="<?= $produto['id'] ?>" data-nome="<?= $produto['nome'] ?>" data-preco="<?= $produto['preco'] ?>"></td>
                    <td><button class="btn btn-success add-produto">Adicionar</button></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <!-- Carrinho -->
        <h4 class="mt-4">Carrinho</h4>
        <table class="table" id="carrinho">
            <thead>
                <tr>
                    <th>Produto</th>
                    <th>Quantidade</th>
                    <th>Subtotal</th>
                    <th>Remover</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>

        <!-- Finalizar Venda -->
        <div class="mt-4">
            <h3>Total: R$ <span id="total">0.00</span></h3>
            <button id="finalizarVenda" class="btn btn-primary w-100">Finalizar Venda</button>
        </div>
    </div>

    <script>
        let carrinho = [];
        const totalEl = document.getElementById('total');

        // Filtrar clientes dinamicamente
        document.getElementById('buscarCliente').addEventListener('input', function() {
            let termo = this.value.toLowerCase();
            let select = document.getElementById('cliente');

            for (let i = 1; i < select.options.length; i++) {
                let texto = select.options[i].text.toLowerCase();
                select.options[i].style.display = texto.includes(termo) ? "" : "none";
            }
        });

        // Filtrar produtos dinamicamente
        document.getElementById('buscarProduto').addEventListener('input', function() {
            let termo = this.value.toLowerCase();
            document.querySelectorAll('.produto').forEach(row => {
                let nomeProduto = row.cells[0].textContent.toLowerCase();
                row.style.display = nomeProduto.includes(termo) ? "" : "none";
            });
        });

        // Adicionar produto ao carrinho
        document.querySelectorAll('.add-produto').forEach(btn => {
            btn.addEventListener('click', function() {
                let row = this.closest('tr');
                let id = row.querySelector('.quantidade').dataset.id;
                let nome = row.querySelector('.quantidade').dataset.nome;
                let preco = parseFloat(row.querySelector('.quantidade').dataset.preco);
                let quantidade = parseInt(row.querySelector('.quantidade').value);

                let item = carrinho.find(p => p.id == id);
                if (item) {
                    item.quantidade += quantidade;
                } else {
                    carrinho.push({ id, nome, preco, quantidade });
                }

                atualizarCarrinho();
            });
        });

        function atualizarCarrinho() {
            let tbody = document.querySelector('#carrinho tbody');
            tbody.innerHTML = '';
            let total = 0;

            carrinho.forEach((item, index) => {
                let subtotal = item.preco * item.quantidade;
                total += subtotal;
                tbody.innerHTML += `
                    <tr>
                        <td>${item.nome}</td>
                        <td>${item.quantidade}</td>
                        <td>R$ ${subtotal.toFixed(2)}</td>
                        <td><button class="btn btn-danger" onclick="removerItem(${index})">X</button></td>
                    </tr>
                `;
            });

            totalEl.textContent = total.toFixed(2);
        }

        function removerItem(index) {
            carrinho.splice(index, 1);
            atualizarCarrinho();
        }

        document.getElementById('finalizarVenda').addEventListener('click', function() {
            let clienteId = document.getElementById('cliente').value;

            if (!clienteId) {
                alert("Selecione um cliente antes de finalizar a venda.");
                return;
            }

            fetch('../controllers/vendasController.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ cliente_id: clienteId, itens: carrinho })
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    alert("Venda registrada com sucesso!");
                    window.location.reload();
                } else {
                    alert("Erro ao registrar a venda.");
                }
            });
        });
    </script>
</body>
</html>
