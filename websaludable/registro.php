<?php
require 'conexion.php';

$mensaje = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre   = trim($_POST['nombre']);
    $email    = trim($_POST['email']);
    $password = trim($_POST['password']);
    $password2 = trim($_POST['password2']);

    if ($password !== $password2) {
        $mensaje = "Las contraseñas no coinciden";
    } else {
        // ¿ya existe ese correo?
        $stmt = $conexion->prepare("SELECT id FROM usuarios WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $mensaje = "Ese correo ya está registrado";
        } else {
            // Hashear contraseña
            $password_hash = password_hash($password, PASSWORD_DEFAULT);

            // Rol por defecto: usuario
            $rol = "usuario";

            $stmt = $conexion->prepare(
                "INSERT INTO usuarios (nombre, email, password, rol) VALUES (?,?,?,?)"
            );
            $stmt->bind_param("ssss", $nombre, $email, $password_hash, $rol);

            if ($stmt->execute()) {
                // opcional: llevar al login
                header("Location: index.php");
                exit;
            } else {
                $mensaje = "Error al registrar usuario";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro - Nutrición Saludable</title>
    <style>
        body {
            background:#f5fff5;
            font-family:Arial,sans-serif;
        }
        .contenedor {
            max-width:400px;
            margin:60px auto;
            padding:20px;
            background:#fff;
            border-radius:10px;
            box-shadow:0 0 10px rgba(0,0,0,.1);
            text-align:center;
        }
        h1 {
            color:#2e7d32;
        }
        input {
            width:100%;
            padding:10px;
            margin:8px 0;
            border-radius:6px;
            border:1px solid #ccc;
        }
        button {
            background:#43a047;
            color:#fff;
            border:none;
            padding:10px 15px;
            border-radius:6px;
            cursor:pointer;
        }
        button:hover {
            background:#2e7d32;
        }
        .mensaje {
            color:red;
            margin-top:10px;
        }
        a {
            display:block;
            margin-top:10px;
            text-decoration:none;
            color:#2e7d32;
        }
    </style>
</head>
<body>
<div class="contenedor">
    <h1>Crear cuenta</h1>
    <p>Regístrate para guardar tu dieta semanal.</p>

    <?php if ($mensaje): ?>
        <div class="mensaje"><?php echo $mensaje; ?></div>
    <?php endif; ?>

    <form method="post">
        <input type="text" name="nombre" placeholder="Nombre completo" required>
        <input type="email" name="email" placeholder="Correo" required>
        <input type="password" name="password" placeholder="Contraseña" required>
        <input type="password" name="password2" placeholder="Repite la contraseña" required>
        <button type="submit">Registrarme</button>
    </form>

    <a href="index.php">¿Ya tienes cuenta? Inicia sesión</a>
</div>
</body>
</html>
