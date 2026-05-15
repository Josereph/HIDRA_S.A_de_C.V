<?php
session_start();
$_SESSION['operador_id'] = 1;
$_GET['codigo'] = 'TEST-01';
require 'c:/wamp64/www/HIDRA_S.A_de_C.V/api/estado_cuenta.php';
