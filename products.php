<?php
require_once __DIR__ . '/../config/database.php';

try {
    $pdo = db();
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        $sql = "SELECT p.id_producto, p.nombre, p.descripcion, p.precio, p.stock,
                       p.imagen, c.nombre AS categoria, p.id_categoria
                FROM productos p
                INNER JOIN categorias c ON c.id_categoria = p.id_categoria
                WHERE p.activo = 1";
        $params = [];
        if (!empty($_GET['q'])) {
            $sql .= " AND (p.nombre LIKE :q OR p.descripcion LIKE :q)";
            $params['q'] = '%' . trim($_GET['q']) . '%';
        }
        if (!empty($_GET['categoria'])) {
            $sql .= " AND p.id_categoria = :categoria";
            $params['categoria'] = (int) $_GET['categoria'];
        }
        if (isset($_GET['max']) && $_GET['max'] !== '') {
            $sql .= " AND p.precio <= :max";
            $params['max'] = (float) $_GET['max'];
        }
        $sql .= " ORDER BY p.id_producto DESC";
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        json_response(['ok' => true, 'productos' => $stmt->fetchAll()]);
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $data = json_decode(file_get_contents('php://input'), true) ?? [];
        $stmt = $pdo->prepare("INSERT INTO productos
            (nombre, descripcion, precio, stock, imagen, id_categoria, activo)
            VALUES (:nombre, :descripcion, :precio, :stock, :imagen, :categoria, 1)");
        $stmt->execute([
            'nombre' => trim($data['nombre'] ?? ''),
            'descripcion' => trim($data['descripcion'] ?? ''),
            'precio' => (float) ($data['precio'] ?? 0),
            'stock' => (int) ($data['stock'] ?? 0),
            'imagen' => trim($data['imagen'] ?? ''),
            'categoria' => (int) ($data['id_categoria'] ?? 0),
        ]);
        json_response(['ok' => true, 'id' => (int) $pdo->lastInsertId()], 201);
    }

    if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
        $data = json_decode(file_get_contents('php://input'), true) ?? [];
        $stmt = $pdo->prepare("UPDATE productos SET nombre=:nombre, descripcion=:descripcion,
            precio=:precio, stock=:stock, imagen=:imagen, id_categoria=:categoria
            WHERE id_producto=:id");
        $stmt->execute([
            'nombre' => trim($data['nombre'] ?? ''),
            'descripcion' => trim($data['descripcion'] ?? ''),
            'precio' => (float) ($data['precio'] ?? 0),
            'stock' => (int) ($data['stock'] ?? 0),
            'imagen' => trim($data['imagen'] ?? ''),
            'categoria' => (int) ($data['id_categoria'] ?? 0),
            'id' => (int) ($data['id_producto'] ?? 0),
        ]);
        json_response(['ok' => true]);
    }

    if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
        $id = (int) ($_GET['id'] ?? 0);
        $stmt = $pdo->prepare("UPDATE productos SET activo=0 WHERE id_producto=?");
        $stmt->execute([$id]);
        json_response(['ok' => true]);
    }

    json_response(['ok' => false, 'mensaje' => 'Método no permitido'], 405);
} catch (Throwable $e) {
    json_response(['ok' => false, 'mensaje' => 'Error de base de datos. Revisá la configuración y los datos.'], 500);
}
