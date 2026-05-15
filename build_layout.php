<?php
$content = file_get_contents('c:/wamp64/www/HIDRA_S.A_de_C.V/views/layouts/pagina_principal.php');
$lines = explode("\n", $content);

$header = implode("\n", array_slice($lines, 0, 183));
$footer = implode("\n", array_slice($lines, 679));

// Replace data-view="..." with href="..."
$header = preg_replace('/class="nav-item([^"]*)"\s+data-view="([^"]+)"\s+data-tooltip="([^"]+)"/', 'class="nav-item$1" href="<?= BASE_PATH ?>/$2" data-tooltip="$3"', $header);

// Remove the inline script that calls showView
$footer = preg_replace('/if\s*\(typeof\s*showView\s*===\s*\'function\'\)\s*showView\(last\);/', '', $footer);

$main_php = $header . "\n" . '<?php if (isset($content) && file_exists($content)) { include $content; } else { echo "<div style=\"padding:40px; text-align:center;\">Vista en construcción o no encontrada.</div>"; } ?>' . "\n" . $footer;

file_put_contents('c:/wamp64/www/HIDRA_S.A_de_C.V/views/layouts/main.php', $main_php);
