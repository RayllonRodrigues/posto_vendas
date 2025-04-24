<?php
include '../config/db.php';
include '../models/FluxoCaixa.php';

$fluxoCaixa = new FluxoCaixa($conn);

// Adicionar movimentação
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_movimentacao'])) {
    $tipo = $_POST['tipo'];
    $descricao = $_POST['descricao'];
    $valor = $_POST['valor'];

    if ($fluxoCaixa->adicionarMovimentacao($tipo, $descricao, $valor)) {
        header("Location: ../views/fluxo_caixa.php");
        exit();
    } else {
        die("Erro ao registrar movimentação.");
    }
}

// Listar movimentações
$movimentacoes = $fluxoCaixa->listarMovimentacoes();
$saldo = $fluxoCaixa->obterSaldo();
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fluxo de Caixa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-4">
        <h2 class="text-center">Fluxo de Caixa</h2>
        <a href="../dashboard.php" class="btn btn-secondary mb-3">Voltar</a>

        <!-- Exibir saldo -->
        <div class="alert alert-info">
            <strong>Saldo Atual:</strong> R$ <?= number_format($saldo['total_entrada'] - $saldo['total_saida'], 2, ',', '.') ?>
        </div>

        <!-- Formulário para adicionar movimentação -->
        <form method="POST" class="mb-4">
            <h4>Adicionar Movimentação</h4>
            <div class="row">
                <div class="col-md-3">
                    <select name="tipo" class="form-select">
                        <option value="entrada">Entrada</option>
                        <option value="saida">Saída</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <input type="text" name="descricao" class="form-control" placeholder="Descrição" required>
                </div>
                <div class="col-md-2">
                    <input type="number" name="valor" class="form-control" placeholder="Valor" step="0.01" required>
                </div>
                <div class="col-md-2">
                    <button type="submit" name="add_movimentacao" class="btn btn-primary w-100">Registrar</button>
                </div>
            </div>
        </form>


        <!-- Exibir filtros -->
        <div class="alert alert-info">
            <h5>Filtrar:</h4>
        </div>

        <!--Filtrar movimentações -->
        <form method="GET" class="mb-2">

            <div class="row">
                <div class="col-md-2">
                    <select name="tipo" class="form-select">
                        <option value="">Todos os Tipos</option>
                        <option value="entrada">Entrada</option>
                        <option value="saida">Saída</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <input type="date" name="data_inicio" class="form-control" placeholder="Data Início">
                </div>
                <div class="col-md-2">
                    <input type="date" name="data_fim" class="form-control" placeholder="Data Fim">
                </div>
                <div class="col-md-2">
                    <input type="text" name="descricao" class="form-control" placeholder="Pesquisar por descrição">
                </div>
                <div class="col-md-1 mt-1">
                    <button type="submit" class="btn btn-primary w-100">Filtrar</button>
                </div>
            </div>
        </form>


        <!-- Tabela de movimentações -->
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Data</th>
                    <th>Tipo</th>
                    <th>Descrição</th>
                    <th>Valor</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $movimentacoes->fetch_assoc()): ?>
                    <tr>
                        <td><?= date("d/m/Y H:i", strtotime($row['data_registro'])) ?></td>
                        <td><?= ucfirst($row['tipo']) ?></td>
                        <td><?= $row['descricao'] ?></td>
                        <td>R$ <?= number_format($row['valor'], 2, ',', '.') ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>

</html>