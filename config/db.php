<?php
$servername = "localhost";
$username = "usuario";  // Altere conforme necessário
$password = "";  // Altere conforme necessário
$dbname = "banco";

// Criar conexão
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexão
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}
?>
