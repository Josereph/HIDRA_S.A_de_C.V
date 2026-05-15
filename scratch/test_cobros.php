<?php
require_once 'c:/wamp64/www/HIDRA_S.A_de_C.V/config/database.php';
$pdo = Database::getInstance();

try {
    $pdo->exec("INSERT INTO usuarios (codigo_usuario, id_tipo_usuario, id_sector, nombres, direccion, fecha_registro) VALUES ('TEST-01', 1, 1, 'Cliente Prueba', 'Direccion', CURDATE())");
    $id_usuario = $pdo->lastInsertId();

    $pdo->exec("INSERT INTO facturas (numero_factura, id_usuario, id_lectura, id_tarifa, mes, anio, fecha_emision, fecha_vencimiento, consumo_m3, total, saldo_pendiente, estado) 
                VALUES ('FAC-TEST', $id_usuario, 9999, 1, 5, 2026, CURDATE(), DATE_SUB(CURDATE(), INTERVAL 1 DAY), 10, 50.00, 50.00, 'vencida')");

    echo "Factura creada.";
} catch(Exception $e) {
    echo $e->getMessage();
}
