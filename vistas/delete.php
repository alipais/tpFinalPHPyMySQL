<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../modelo/database.php';  
include_once '../controlador/usuarioControlador.php';

$database = new Database();
$db = $database->getConnection();

$usuarios = new UsuarioController($db);

// Obtener los datos de la solicitud
$data = json_decode(file_get_contents("php://input"));


if (!empty($data->id)) {
    try {
        // Llamar al método delete() con el ID recibido
        if ($usuarios->delete($data->id)) {
            http_response_code(200);
            echo json_encode(array("mensaje" => "Usuario eliminado exitosamente."));
        } else {
            http_response_code(503);
            echo json_encode(array("mensaje" => "No se pudo eliminar el usuario."));
        }
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(array("mensaje" => "Error al intentar eliminar: " . $e->getMessage()));
    }
} else {
    http_response_code(400);
    echo json_encode(array("mensaje" => "No se puede eliminar el usuario. Falta el ID."));
}
