<?php
include_once 'modelo/Database.php';
include_once 'controlador/usuarioControlador.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Recoger los datos del formulario
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $clave = $_POST['clave'];

    // Conectar a la base de datos
    $database = new Database();
    $conn = $database->getConnection();

    // Crear instancia del controlador
    $usuario = new UsuarioController($conn);

    // Intentar crear el usuario
    try {
        $usuario->create($nombre, $email, $clave);
        echo "Usuario registrado con éxito.";
          // Redirigir al login después de un registro exitoso
          header("Location: login.php");
          exit(); // Asegura que no se ejecute el código siguiente
    } catch (Exception $e) {
        echo "Error al registrar el usuario: " . $e->getMessage();
    }
} 
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Usuarios</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="text-center">Registro</h3>
                    </div>
                    <div class="card-body">
                        <div id="alertMessage" class="alert d-none"></div>
                        <form action="registro.php" method="POST">
                            <label for="nombre">Nombre:</label>
                            <input type="text" id="nombre" name="nombre" required>
                            
                            <label for="email">Correo Electrónico:</label>
                            <input type="email" id="email" name="email" required>
                            
                            <label for="clave">Contraseña:</label>
                            <input type="password" id="clave" name="clave" required>
                            
                            <button type="submit">Registrar</button>
                          </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
   
</body>
</html>