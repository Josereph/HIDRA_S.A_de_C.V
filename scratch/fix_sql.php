<?php
$file = 'c:/wamp64/www/HIDRA_S.A_de_C.V/dbref/hidra_bd (1).sql';
$content = file_get_contents($file);
$hash = password_hash('admin123', PASSWORD_DEFAULT);
$content = str_replace("('Administrador HIDRA', 'admin', 'admin@hidra.local', '\$2y\$10\$CAMBIAR_ESTE_HASH_EN_PRODUCCION', 'administrador', NULL);", "('Administrador HIDRA', 'admin', 'admin', '$hash', 'administrador', NULL);", $content);
file_put_contents($file, $content);
echo "SQL Updated\n";
