<?php
function fixPermissions($path) {
    if (is_dir($path)) {
        chmod($path, 0755); // složky
        $items = scandir($path);
        foreach ($items as $item) {
            if ($item === '.' || $item === '..') continue;
            fixPermissions($path . DIRECTORY_SEPARATOR . $item);
        }
    } elseif (is_file($path)) {
        chmod($path, 0644); // soubory
    }
}

$root = __DIR__; // aktuální složka, tj. www/
fixPermissions($root);

echo "✅ Všechna práva byla úspěšně nastavena: složky 755, soubory 644.";
?>