<?php
/**
 * API - Punto de entrada para peticiones AJAX
 * Un solo archivo para todas las operaciones
 */

require_once __DIR__ . '/../config.php';

header('Content-Type: application/json; charset=utf-8');

$modelo = new Producto();
$api = new Api($modelo, 'productos');
$api->ejecutar();
