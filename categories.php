<?php
require_once __DIR__ . '/../config/database.php';
try {
    $stmt = db()->query("SELECT id_categoria, nombre FROM categorias ORDER BY nombre");
    json_response(['ok' => true, 'categorias' => $stmt->fetchAll()]);
} catch (Throwable $e) {
    json_response(['ok' => false, 'mensaje' => 'No se pudieron cargar las categorías.'], 500);
}
