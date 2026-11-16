<?php
require_once __DIR__ . '/../config/database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    json_response(['ok' => false, 'mensaje' => 'Método no permitido'], 405);
}

$data = json_decode(file_get_contents('php://input'), true) ?? [];
$cliente = trim($data['cliente'] ?? '');
$email = trim($data['email'] ?? '');
$items = $data['items'] ?? [];

if ($cliente === '' || !filter_var($email, FILTER_VALIDATE_EMAIL) || !is_array($items) || count($items) === 0) {
    json_response(['ok' => false, 'mensaje' => 'Completá nombre, email y agregá al menos un producto.'], 400);
}

$pdo = db();
try {
    $pdo->beginTransaction();

    $stmtCliente = $pdo->prepare("INSERT INTO clientes (nombre, email) VALUES (?, ?)
        ON DUPLICATE KEY UPDATE nombre=VALUES(nombre)");
    $stmtCliente->execute([$cliente, $email]);
    $stmtFind = $pdo->prepare("SELECT id_cliente FROM clientes WHERE email=?");
    $stmtFind->execute([$email]);
    $idCliente = (int) $stmtFind->fetchColumn();

    $total = 0;
    $lineas = [];
    $stmtProducto = $pdo->prepare("SELECT id_producto, nombre, precio, stock FROM productos WHERE id_producto=? AND activo=1 FOR UPDATE");
    foreach ($items as $item) {
        $id = (int) ($item['id'] ?? 0);
        $cantidad = max(1, (int) ($item['cantidad'] ?? 1));
        $stmtProducto->execute([$id]);
        $producto = $stmtProducto->fetch();
        if (!$producto || (int)$producto['stock'] < $cantidad) {
            throw new RuntimeException('Uno de los productos no tiene stock suficiente.');
        }
        $subtotal = (float)$producto['precio'] * $cantidad;
        $total += $subtotal;
        $lineas[] = [$id, $cantidad, (float)$producto['precio']];
    }

    $stmtPedido = $pdo->prepare("INSERT INTO pedidos (id_cliente, total, estado) VALUES (?, ?, 'Pendiente')");
    $stmtPedido->execute([$idCliente, $total]);
    $idPedido = (int) $pdo->lastInsertId();

    $stmtDetalle = $pdo->prepare("INSERT INTO detalle_pedido (id_pedido, id_producto, cantidad, precio_unitario) VALUES (?, ?, ?, ?)");
    $stmtStock = $pdo->prepare("UPDATE productos SET stock = stock - ? WHERE id_producto = ?");
    foreach ($lineas as [$id, $cantidad, $precio]) {
        $stmtDetalle->execute([$idPedido, $id, $cantidad, $precio]);
        $stmtStock->execute([$cantidad, $id]);
    }

    $pdo->commit();
    json_response(['ok' => true, 'pedido' => $idPedido, 'total' => $total]);
} catch (Throwable $e) {
    if ($pdo->inTransaction()) $pdo->rollBack();
    $message = $e instanceof RuntimeException ? $e->getMessage() : 'No se pudo registrar la compra.';
    json_response(['ok' => false, 'mensaje' => $message], 400);
}
