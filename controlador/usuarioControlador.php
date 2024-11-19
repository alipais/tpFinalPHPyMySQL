<?php
class UsuarioController {public $conn;
    public $usuariosTable = 'usuarios'; // Nombre de la tabla de usuarios
  
    public function __construct($conn) {
      $this->conn = $conn;
    }
  
    // Crear nuevo usuario
    public function create(string $nombre, string $email, string $clave): bool {
      // Encriptar la contraseña antes de guardarla
      $claveHashed = password_hash($clave, PASSWORD_BCRYPT);
      
      $stmt = $this->conn->prepare("
        INSERT INTO {$this->usuariosTable} (`nombre`, `email`, `clave`)
        VALUES (?, ?, ?)
      ");
  
      $stmt->bind_param('sss', $nombre, $email, $claveHashed);
  
      if ($stmt->execute()) {
        return true;
      } else {
        // Manejar errores
        throw new mysqli_sql_exception($stmt->error);
      }
    }
  
    // Leer usuarios, si se proporciona un ID, leer solo ese usuario
    public function read(int $id = null): mysqli_result {
      $stmt = $this->conn->prepare($id ? 
        "SELECT * FROM {$this->usuariosTable} WHERE id = ?" : 
        "SELECT * FROM {$this->usuariosTable}"
      );
  
      if ($id) {
        $stmt->bind_param('i', $id);
      }
  
      $stmt->execute();
      $result = $stmt->get_result();
  
      return $result;
    }
  
    // Actualizar información de un usuario
    public function update(int $id, string $nombre, string $email, string $clave): bool {
      // Encriptar la nueva contraseña
      $claveHashed = password_hash($clave, PASSWORD_BCRYPT);
  
      $stmt = $this->conn->prepare("
        UPDATE {$this->usuariosTable}
        SET nombre = ?, email = ?, clave = ?
        WHERE id = ?
      ");
  
      $stmt->bind_param('sssi', $nombre, $email, $claveHashed, $id);
  
      if ($stmt->execute()) {
        return true;
      } else {
        // Manejar errores
        throw new mysqli_sql_exception($stmt->error);
      }
    }
// leer usuario por su email
public function readByEmail(string $email): array {
  $stmt = $this->conn->prepare("SELECT * FROM {$this->usuariosTable} WHERE email = ?");
  $stmt->bind_param('s', $email);
  $stmt->execute();
  $result = $stmt->get_result();

  /// Si no se encuentra ningún registro, retornar un arreglo vacío
  if ($result->num_rows === 0) {
    return []; // Retornar arreglo vacío en lugar de null
   }

    return $result->fetch_assoc(); // Retornar los datos del usuario
  }


    // Eliminar un usuario por ID
    public function delete(int $id): bool {
      $query = "DELETE FROM usuarios WHERE id = ?";
      $stmt = $this->conn->prepare($query);
      $stmt->bind_param("i", $id); // Usamos el ID recibido como parámetro
    
      if ($stmt->execute()) {
          return true;
      } else {
          throw new Exception("No se pudo eliminar el usuario.");
      }
  }
}