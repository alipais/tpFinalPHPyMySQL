<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once '../modelo/Database.php';
include_once '../controlador/usuarioControlador.php'; // Ajusta el nombre del archivo si es necesario

$database = new Database();
$db = $database->getConnection();

$usuarios = new UsuarioController($db);

// Obtener el ID si se pasa como parámetro
$id = (isset($_GET['id']) && $_GET['id']) ? $_GET['id'] : null;

// Obtener los datos de los usuarios (todos o uno específico)
$result = $usuarios->read($id);

if ($result->num_rows > 0) {
    $userRecords = array();
    $userRecords["usuarios"] = array();

    while ($user = $result->fetch_assoc()) {
        $userDetails = array(
            "id" => $user['id'],
            "nombre" => $user['nombre'],
            "email" => $user['email']
            // Excluimos la clave por seguridad
        );
        array_push($userRecords["usuarios"], $userDetails);
    }

    http_response_code(200);
    echo json_encode($userRecords);
} else {
    http_response_code(404);
    echo json_encode(array("message" => "No se encontraron usuarios."));
}

?>
