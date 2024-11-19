<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once '../modelo/database.php';
include_once '../controlador/usuarioControlador.php';

$database = new Database();
$db = $database->getConnection();

$usuario = new UsuarioController($db);

// Verifica si es una solicitud POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Verificar que los datos necesarios estén presentes
    if (isset($_POST['id'], $_POST['nombre'], $_POST['email'], $_POST['clave']) &&
        !empty($_POST['id']) && 
        !empty($_POST['nombre']) && 
        !empty($_POST['email']) && 
        !empty($_POST['clave'])) {

        // Asegurarse de que la ID sea un número entero
        $id = (int) $_POST['id'];
        $nombre = $_POST['nombre'];
        $email = $_POST['email'];
        $clave = $_POST['clave'];

        try {
            $usuario->update($id, $nombre, $email, $clave);
            http_response_code(200);
            echo json_encode(array("mensaje" => "Usuario actualizado correctamente.", "exito" => true));
        } catch (Exception $e) {
            http_response_code(503);
            echo json_encode(array("mensaje" => "No se pudo actualizar el usuario: " . $e->getMessage(), "exito" => false));
        }
    } else {
        http_response_code(400);
        echo json_encode(array("mensaje" => "Datos no válidos o faltantes."));
    }
}
?>
