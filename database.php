<?php
declare(strict_types=1);

/*
 * Configuración de conexión para XAMPP / MariaDB.
 * Cambiá estos valores si tu instalación usa otros datos.
 */
const DB_HOST = 'localhost';
const DB_NAME = 'techstore';
const DB_USER = 'root';
const DB_PASS = ''; // XAMPP normalmente no trae contraseña para root.

function db(): PDO {
    static $pdo = null;
    if ($pdo === null) {
        $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4';
        $pdo = new PDO($dsn, DB_USER, DB_PASS, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]);
    }
    return $pdo;
}

function json_response(array $data, int $status = 200): never {
    http_response_code($status);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit;
}
