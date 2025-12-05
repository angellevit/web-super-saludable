<?php
require 'conexion.php';

if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_rol'] !== 'admin') {
    header("Location: index.php");
    exit;
}

// Obtener usuarios y su dieta
$sql = "SELECT u.id as usuario_id, u.nombre, u.email, d.id as dieta_id 
        FROM usuarios u
        LEFT JOIN dietas d ON d.usuario_id = u.id
        ORDER BY u.nombre";
$usuarios = $conexion->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel admin - Nutrición</title>
    <style>
        body { background:#fff8e1; font-family:Arial,sans-serif; }
        .contenedor { max-width:900px; margin:30px auto; background:#ffffff; padding:20px; border-radius:10px; box-shadow:0 0 10px rgba(0,0,0,.1);}
        h1 { color:#f9a825; }
        table { width:100%; border-collapse:collapse; margin-top:15px;}
        th, td { border:1px solid #ddd; padding:8px; text-align:left;}
        th { background:#fff9c4;}
        a.btn { padding:6px 10px; border-radius:4px; text-decoration:none; }
        .editar { background:#43a047; color:#fff; }
        .sin-dieta { color:#e53935; }
        .topbar { display:flex; justify-content:space-between; align-items:center; }
    </style>
</head>
<body>
<div class="contenedor">
    <div class="topbar">
        <h1>Panel de administración 🥕</h1>
        <a href="logout.php">Cerrar sesión</a>
    </div>
    <p>Revisa las dietas de los usuarios y sugiere opciones más saludables.</p>

    <table>
        <tr>
            <th>Nombre</th>
            <th>Correo</th>
            <th>Dieta</th>
        </tr>
        <?php while ($u = $usuarios->fetch_assoc()): ?>
            <tr>
                <td><?php echo htmlspecialchars($u['nombre']); ?></td>
                <td><?php echo htmlspecialchars($u['email']); ?></td>
                <td>
                    <?php if ($u['dieta_id']): ?>
                        <a class="btn editar" href="editar_dieta.php?dieta_id=<?php echo $u['dieta_id']; ?>">
                            Ver / Editar dieta
                        </a>
                    <?php else: ?>
                        <span class="sin-dieta">Sin dieta registrada</span>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
</div>
</body>
</html>
