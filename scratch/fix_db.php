<?php
require 'c:/wamp64/www/HIDRA_S.A_de_C.V/config/database.php';
$pdo = Database::getInstance();
$hash = password_hash('admin123', PASSWORD_DEFAULT);
// Actualizar la contraseña del usuario admin
$pdo->exec("UPDATE operadores SET password_hash = '$hash', correo = 'admin' WHERE usuario = 'admin'");
echo "DB Updated\n";
