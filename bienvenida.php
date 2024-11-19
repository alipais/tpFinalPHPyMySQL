<?php
session_start();
include_once 'modelo/database.php';
include_once 'controlador/usuarioControlador.php';

// Verificar si el usuario está logueado (session)
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

$usuario_id = $_SESSION['usuario_id']; // ID del usuario que inició sesión

// Conexión a la base de datos
$database = new Database();
$conn = $database->getConnection();

// Crear una instancia del controlador de usuario
$usuario = new UsuarioController($conn);

// Obtener los datos del usuario de la base de datos
$result = $usuario->read($usuario_id);
$usuario_data = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenido</title>
    <style>
        table {
            width: 50%;
            margin: auto;
            border-collapse: collapse;
        }
        th, td {
            padding: 10px;
            text-align: left;
            border: 1px solid #ddd;
        }
        th {
            background-color: #f4f4f4;
        }
        button {
            padding: 5px 10px;
            margin-top: 10px;
        }
        .center {
            text-align: center;
        }
    </style>
</head>
<body>
    <h1 style="text-align: center;">Bienvenido, <?php echo $usuario_data['nombre']; ?>!</h1>

    <form id="updateForm" method="POST" action="vistas/update.php">
    <table>
        <tr>
            <th>Campo</th>
            <th>Datos</th>
        </tr>
        <tr>
            <td>ID</td>
            <td>
                <input type="hidden" name="id" value="<?php echo $usuario_data['id']; ?>">
                <?php echo $usuario_data['id']; ?>
            </td>
        </tr>
        <tr>
            <td>Nombre</td>
            <td>
                <input type="text" name="nombre" value="<?php echo $usuario_data['nombre']; ?>" required>
            </td>
        </tr>
        <tr>
            <td>Correo electrónico</td>
            <td>
                <input type="email" name="email" value="<?php echo $usuario_data['email']; ?>" required>
            </td>
        </tr>
        <tr>
            <td>Contraseña</td>
            <td>
                <input type="password" name="clave" placeholder="Nueva contraseña (opcional)">
            </td>
        </tr>
    </table>
    <div class="center">
        <button type="submit">Actualizar datos</button>
    </div>
    </form>

    <div class="center" style="margin-top: 20px;">
        <form id="deleteForm" style="display: inline;">
            <input type="hidden" name="id" value="<?php echo $usuario_data['id']; ?>">
            <button type="submit" style="background-color: red; color: white;">Eliminar cuenta</button>
        </form>

        <form action="logout.php" method="GET" style="display: inline;">
            <button type="submit">Cerrar sesión</button>
        </form>
    </div>

    <script>
        // Lógica para actualizar datos de usuario con redirección a bienvenida.php
        document.getElementById('updateForm').addEventListener('submit', function (e) {
            e.preventDefault();

            const formData = new FormData(this);

            fetch('vistas/update.php', {
                method: 'POST',
                body: formData,
            })
            .then(response => response.json())
            .then(data => {
                alert(data.mensaje); // Mostrar mensaje al usuario

                if (data.exito) {
                    // Redirigir a bienvenida.php si la actualización fue exitosa
                    window.location.href = 'logout.php'; // Redirigir a bienvenida.php
                }
            })
            .catch(error => console.error('Error:', error));
        });

        // Lógica para eliminar la cuenta del usuario con confirmación
        document.getElementById('deleteForm').addEventListener('submit', function (e) {
            e.preventDefault();
            const id = document.querySelector('input[name="id"]').value;

            if (confirm('¿Estás seguro de que deseas eliminar tu cuenta? Esta acción no se puede deshacer.')) {
                fetch('vistas/delete.php', {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ id: id }),
                })
                .then((response) => response.json())
                .then((data) => {
                    alert(data.mensaje); // Mostrar mensaje al usuario
                    if (data.exito) {
                        // Redirigir a login.php si la cuenta fue eliminada
                        window.location.href = 'login.php';
                    }
                })
                .catch((error) => {
                    console.error('Error:', error);
                    alert('Ocurrió un error al intentar eliminar tu cuenta.');
                });
            }
        });
    </script>
</body>
</html>
