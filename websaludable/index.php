<?php
require 'conexion.php';

$mensaje = "";



if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    $stmt = $conexion->prepare("SELECT id, nombre, password, rol FROM usuarios WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows === 1) {
        $usuario = $resultado->fetch_assoc();

        // si aún no usas password_hash(), puedes comparar directo:
        // if ($password === $usuario['password']) { ... }

        if (password_verify($password, $usuario['password'])) {
            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['usuario_nombre'] = $usuario['nombre'];
            $_SESSION['usuario_rol'] = $usuario['rol'];

            if ($usuario['rol'] === 'admin') {
                header("Location: admin.php");
            } else {
                header("Location: usuario.php");
            }
            exit;
        } else {
            $mensaje = "Datos incorrectos";
        }
    } else {
        $mensaje = "Datos incorrectos";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login - Nutrición Saludable</title>
    <style>
        body {
            background: #f5fff5;
            font-family: Arial, sans-serif;
        }
        .contenedor {
            max-width: 400px;
            margin: 80px auto;
            padding: 20px;
            background: #ffffff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,.1);
            text-align: center;
        }
        h1 {
            color: #2e7d32;
        }
        input {
            width: 100%;
            padding: 10px;
            margin: 8px 0;
            border-radius: 6px;
            border: 1px solid #ccc;
        }
        button {
            background: #43a047;
            color: #fff;
            border: none;
            padding: 10px 15px;
            border-radius: 6px;
            cursor: pointer;
        }
        button:hover {
            background: #2e7d32;
        }
        .mensaje {
            color: red;
            margin-top: 10px;
        }
    </style>
</head>
<body>
<div class="contenedor">
    <h1>Nutrición Saludable</h1>
    <p>Inicia sesión para continuar</p>

    <?php if ($mensaje): ?>
        <div class="mensaje"><?php echo $mensaje; ?></div>
    <?php endif; ?>

    <form method="post">
        <input type="email" name="email" placeholder="Correo" required>
        <input type="password" name="password" placeholder="Contraseña" required>
        <button type="submit">Entrar</button>
    </form>
    <a href="registro.php">Crear una cuenta nueva</a>

</div>
</body>
</html>
