<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../modelo/Database.php';
include_once '../controlador/usuarioControlador.php'; // Aquí debe ir la ruta correcta de la clase Usuarios

$database = new Database();
$db = $database->getConnection();

$usuarios = new UsuarioController($db);

$data = json_decode(file_get_contents("php://input"));

if(!empty($data->nombre) && !empty($data->email) && !empty($data->clave)){

    // Asignar los valores del JSON a las propiedades de la clase
    $nombre = $data->nombre;
    $email = $data->email;
    $clave = $data->clave;

    // Crear el usuario (encriptando la clave)
    if($usuarios->create($nombre, $email, $clave)){ 
        http_response_code(201); 
        echo json_encode(array("message" => "User was created successfully."));
    } else{ 
        http_response_code(503); 
        echo json_encode(array("message" => "Unable to create user."));
    }
}else{ 
    http_response_code(400); 
    echo json_encode(array("message" => "Unable to create user. Data is incomplete."));
}
?>
