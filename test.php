<?php
if (file_exists(__DIR__ . '/config/db.php')) {
    echo "db.php encontrado!<br>";
} else {
    echo "Erro: db.php não encontrado!<br>";
}

if (file_exists(__DIR__ . '/controllers/usuarios/usuariosController.php')) {
    echo "usuariosController.php encontrado!<br>";
} else {
    echo "Erro: usuariosController.php não encontrado!<br>";
}
?>
