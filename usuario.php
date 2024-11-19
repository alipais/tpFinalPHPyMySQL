<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once 'modelo/Database.php'; // conexión a la DB
include_once 'controlador/usuarioControlador.php';


$database = new Database(); // Obtener la conexión
$conn = $database->getConnection();
// Asegurar que la conexión se ha establecido correctamente
if ($conn) {
    echo "Conexión exitosa a la base de datos.<br>";
} else {
    echo "Error de conexión.<br>";
}

$usuario = new UsuarioController($conn);  // Aquí debe pasar la conexión a la base de datos


$method = $_SERVER['REQUEST_METHOD']; // Método HTTP (GET, POST, PUT, DELETE)

// Verificar si el parámetro 'id' está presente en la URL
if (isset($_GET['id'])) {
    $id = $_GET['id']; // Obtener el ID del usuario
}
// Rutas de la API
switch ($method) {
    case 'GET':
        if (isset($_GET['id'])) {
            // Leer un usuario por ID
            $id = $_GET['id'];
            $result = $usuario->read($id);
            $usuarios = $result->fetch_all(MYSQLI_ASSOC);
            echo json_encode($usuarios);
        } else {
            // Leer todos los usuarios
            $result = $usuario->read();
            $usuarios = $result->fetch_all(MYSQLI_ASSOC);
            echo json_encode($usuarios);
        }
        break;

    case 'POST':
        // Crear un nuevo usuario
        $data = json_decode(file_get_contents("php://input"));
        if (!empty($data->nombre) && !empty($data->email) && !empty($data->clave)) {
            try {
                $usuario->create($data->nombre, $data->email, $data->clave);
                echo json_encode(array("mensaje" => "Usuario creado correctamente."));
            } catch (Exception $e) {
                echo json_encode(array("mensaje" => "Error al crear usuario: " . $e->getMessage()));
            }
        } else {
            echo json_encode(array("mensaje" => "Datos faltantes o inválidos."));
        }
        break;

        case 'PUT':
            $data = json_decode(file_get_contents("php://input"));
            if (!empty($data->id) && !empty($data->nombre) && !empty($data->email) && !empty($data->clave)) {
                try {
                    $usuario->update($data->id, $data->nombre, $data->email, $data->clave);
                    echo json_encode(array("mensaje" => "Usuario actualizado correctamente."));
                } catch (Exception $e) {
                    echo json_encode(array("mensaje" => "Error al actualizar usuario: " . $e->getMessage()));
                }
            } else {
                echo json_encode(array("mensaje" => "Datos faltantes o inválidos."));
            }
            break;
        

            case 'DELETE':
                // Leer el cuerpo de la solicitud
                $data = json_decode(file_get_contents("php://input"));
            
                if (!empty($data->id)) {
                    $id = $data->id;
                    try {
                        $usuario->delete($id);
                        echo json_encode(array("mensaje" => "Usuario eliminado correctamente."));
                    } catch (Exception $e) {
                        echo json_encode(array("mensaje" => "Error al eliminar usuario: " . $e->getMessage()));
                    }
                } else {
                    echo json_encode(array("mensaje" => "ID de usuario no especificado."));
                }
                break;
            
}
?>
