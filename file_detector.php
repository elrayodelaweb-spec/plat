<?php
// Desactiva el límite de tiempo de ejecución para proyectos grandes.
set_time_limit(0);

// Define el directorio raíz para escanear (el directorio actual donde se coloca este archivo).
$rootPath = __DIR__;

echo "<h1>🔍 Analizador de Archivos del Proyecto</h1>";
echo "<h2>Ruta Raíz: $rootPath</h2>";
echo "<hr>";
echo "<pre>";

try {
    // 1. Iterador recursivo para recorrer directorios y archivos.
    $iterator = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($rootPath, RecursiveDirectoryIterator::SKIP_DOTS),
        RecursiveIteratorIterator::SELF_FIRST
    );

    // 2. Itera sobre todos los elementos.
    $count = 0;
    foreach ($iterator as $path => $fileinfo) {
        $name = $fileinfo->getFilename();
        
        // Excluir el archivo de la herramienta para evitar listar su propio contenido.
        if ($name === 'file_detector.php') {
            continue;
        }

        // Determina si es un directorio o un archivo.
        if ($fileinfo->isDir()) {
            echo "📂 **Directorio:** " . $path . "\n";
        } else {
            // Muestra el archivo y su tamaño.
            $size = number_format($fileinfo->getSize() / 1024, 2) . ' KB';
            echo "📄 Archivo: " . $path . " (Tamaño: {$size})\n";
            $count++;
        }
    }

    echo "\n\n";
    echo "========================================================\n";
    echo "✅ Escaneo completado. Se encontraron {$count} archivos.\n";
    echo "========================================================\n";

} catch (Exception $e) {
    echo "❌ ERROR al escanear: " . $e->getMessage();
}

echo "</pre>";
?>