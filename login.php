<?php
session_start(); // Inicia la sesión

include_once 'modelo/Database.php';
include_once 'controlador/usuarioControlador.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obtener los datos del formulario
    $email = $_POST['email'];
    $clave = $_POST['clave'];

    // Conectar a la base de datos
    $database = new Database();
    $conn = $database->getConnection();

    // Crear instancia del controlador de usuarios
    $usuario = new UsuarioController($conn);

    // Verificar si el usuario existe y la contraseña es correcta
    $user = $usuario->readByEmail($email);

    // Comprobar si la consulta devuelve un resultado
    if ($user) {
        // Verificar la contraseña
        if (password_verify($clave, $user['clave'])) {
            // Iniciar sesión
            $_SESSION['usuario_id'] = $user['id'];
            $_SESSION['nombre'] = $user['nombre'];

            // Redirigir a la página de bienvenida
            header("Location: bienvenida.php");
            exit();
        } else {
            $error = "Credenciales incorrectas.";
        }
    } else {
        $error = "Usuario no encontrado.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio de Sesión</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="text-center">Iniciar Sesión</h3>
                    </div>
                    <div class="card-body">

                    <form action="login.php" method="POST">
                      <label for="email">Correo Electrónico:</label>
                      <input type="email" id="email" name="email" required>
                      
                      <label for="clave">Contraseña:</label>
                      <input type="password" id="clave" name="clave" required>
                      
                      <button type="submit">Iniciar Sesión</button>
                    </form>
                    <?php if (isset($error)) { echo "<p>$error</p>"; } ?>
                        <div class="mt-3 text-center">
                            <p>¿No tienes cuenta? <a href="registro.php">Regístrate aquí</a></p>
                        </div>
                        <!-- Enlace para volver al índice -->
                        <div class="mt-3 text-center">
                            <p><a href="index.php">Volver al ínicio</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
   
      
</body>
</html>