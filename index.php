<?php

// Mendapatkan path dari URL
$request_uri = $_SERVER['REQUEST_URI'];

$base_path = '/rest-api'; // Sesuaikan dengan base path aplikasi Anda

// Menghapus base path dari request URI
$request_uri = str_replace($base_path, '', $request_uri);

// Mendapatkan rute yang diminta
$route = explode('/', $request_uri);

// Memuat controller yang sesuai berdasarkan rute
if ($route[1] == 'convert-time') {
    require_once 'controllers/ConvertTimeController.php';
    $controller = new ConvertTimeController();
} elseif ($route[1] == 'inventories') {
    require_once 'controllers/InventoriesController.php';
    $controller = new InventoriesController();
} elseif ($route[1] == 'migration') {
    require_once 'config/migration.php';
    exit();
} else {
    // Rute tidak valid, atur response kode 404
    http_response_code(404);
    echo json_encode(array("message" => "Not Found", "route" => $route[1]));
    exit();
}

// Menangani request berdasarkan metode HTTP
$method = $_SERVER['REQUEST_METHOD'];
switch ($method) {
    case 'GET':
        $controller->get();
        break;
    case 'POST':
        $controller->post();
        break;
    case 'PUT':
        $id = $route[2];
        $controller->put($id);
        break;
    case 'DELETE':
        $id = $route[2];
        $controller->delete($id);
        break;
    default:
        // Metode HTTP tidak didukung, atur response kode 405
        http_response_code(405);
        echo json_encode(array("message" => "Method Not Allowed",));
        break;
}
