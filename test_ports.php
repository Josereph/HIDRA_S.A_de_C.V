<?php
foreach([3306,3307,3308] as $port) {
    try {
        $pdo = new PDO("mysql:host=127.0.0.1;port=$port", "root", "");
        $dbs = $pdo->query("SHOW DATABASES")->fetchAll(PDO::FETCH_COLUMN);
        echo "Port $port: " . implode(', ', $dbs) . "\n";
    } catch(Exception $e) {
        echo "Port $port: Failed\n";
    }
}
