<?php
$dir = __DIR__ . '/view/admin/';
$files = scandir($dir);

foreach ($files as $file) {
    if (pathinfo($file, PATHINFO_EXTENSION) === 'php' && $file !== 'index.php') {
        $path = $dir . $file;
        $content = file_get_contents($path);
        
        // Handle logout first
        $content = str_replace('index.php?page=logout', 'index.php?module=auth&action=logout', $content);
        
        // Handle the rest
        $content = preg_replace('/index\.php\?page=([a-zA-Z0-9_]+)/', 'index.php?module=admin&action=$1', $content);
        
        file_put_contents($path, $content);
        echo "Updated $file\n";
    }
}
echo "Done.\n";
