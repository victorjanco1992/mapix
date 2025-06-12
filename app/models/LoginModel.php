<?php

class LoginModel extends Model
{
    // Método para obtener registro con id
    public function validar_()
    {
        $correo = $_POST['email'] ?? '';
        $clave_ingresada = $_POST['password'] ?? '';

        // Obtener usuario por correo
        $sql = "SELECT * FROM users WHERE email_user = :email";
        $params = [':email' => $correo];

        $usuario = $this->getOne($sql, $params);

        // Verificar que exista y que la contraseña coincida
        if ($usuario && password_verify($clave_ingresada, $usuario->password_user)) {

            // Iniciar la sesión si no está iniciada
            if (session_status() == PHP_SESSION_NONE) {
                session_start();
            }

            // Guardar usuario en sesión
            $_SESSION['usuario_cliente'] = $usuario;

            return [
                'status' => 'success',
                'message' => 'Inicio de sesión exitoso.',
                'data' => $usuario
            ];
        } else {
            return [
                'status' => 'error',
                'message' => 'Usuario o contraseña incorrectos.'
            ];
        }
    }


    public function registrar_()
    {
        $nombre = $_POST['name'] ?? '';
        $correo = $_POST['email'] ?? '';
        $clave_plana = $_POST['password'] ?? '';

        // Buscar si ya existe un usuario con ese email
        $sql = "SELECT * FROM users WHERE email_user = :email";
        $params = [':email' => $correo];
        $usuario = $this->getOne($sql, $params);

        if (!$usuario) {
            // Hashear la clave
            $clave_hash = password_hash($clave_plana, PASSWORD_DEFAULT);

            // Insertar nuevo usuario
            $sql2 = "INSERT INTO users (name_users, email_user, password_user) 
                 VALUES (:nombre, :email, :clave)";
            $params2 = [
                ':nombre' => $nombre,
                ':email'  => $correo,
                ':clave'  => $clave_hash
            ];
            $this->query($sql2, $params2);

            // Volver a obtener los datos del usuario recién registrado
            $usuario2 = $this->getOne($sql, [':email' => $correo]);

            // Iniciar la sesión
            if (session_status() == PHP_SESSION_NONE) {
                session_start();
            }
            
            $_SESSION['usuario_cliente'] = $usuario2;

            return [
                'status' => 'success',
                'message' => 'Registro de sesión exitoso.',
                'data' => $usuario2
            ];
        } else {
            return [
                'status' => 'error',
                'message' => 'Correo electrónico existente'
            ];
        }
    }
}
