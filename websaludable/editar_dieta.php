<?php
require 'conexion.php';

if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_rol'] !== 'admin') {
    header("Location: index.php");
    exit;
}

if (!isset($_GET['dieta_id'])) {
    header("Location: admin.php");
    exit;
}

$dieta_id = intval($_GET['dieta_id']);
$mensaje = "";

// Cargar dieta
$stmt = $conexion->prepare(
    "SELECT d.*, u.nombre 
     FROM dietas d 
     JOIN usuarios u ON u.id = d.usuario_id 
     WHERE d.id = ?"
);
$stmt->bind_param("i", $dieta_id);
$stmt->execute();
$dieta = $stmt->get_result()->fetch_assoc();

if (!$dieta) {
    header("Location: admin.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $lunes     = $_POST['lunes'] ?? '';
    $martes    = $_POST['martes'] ?? '';
    $miercoles = $_POST['miercoles'] ?? '';
    $jueves    = $_POST['jueves'] ?? '';
    $viernes   = $_POST['viernes'] ?? '';
    $sabado    = $_POST['sabado'] ?? '';
    $domingo   = $_POST['domingo'] ?? '';
    $comentarios_admin = $_POST['comentarios_admin'] ?? '';

    $stmt = $conexion->prepare(
        "UPDATE dietas SET lunes=?, martes=?, miercoles=?, jueves=?, viernes=?, sabado=?, domingo=?, comentarios_admin=? 
         WHERE id=?"
    );
    $stmt->bind_param("ssssssssi", $lunes, $martes, $miercoles, $jueves, $viernes, $sabado, $domingo, $comentarios_admin, $dieta_id);
    $stmt->execute();
    $mensaje = "Dieta modificada y comentarios guardados 💛";

    // recargar datos
    $stmt = $conexion->prepare(
        "SELECT d.*, u.nombre 
         FROM dietas d 
         JOIN usuarios u ON u.id = d.usuario_id 
         WHERE d.id = ?"
    );
    $stmt->bind_param("i", $dieta_id);
    $stmt->execute();
    $dieta = $stmt->get_result()->fetch_assoc();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar dieta</title>
    <style>
        body { background:#fff8e1; font-family:Arial,sans-serif; }
        .contenedor { max-width:800px; margin:30px auto; background:#fff; padding:20px; border-radius:10px; box-shadow:0 0 10px rgba(0,0,0,.1); }
        textarea { width:100%; min-height:60px; margin:6px 0; border-radius:6px; border:1px solid #ccc; padding:8px; }
        button { background:#f9a825; color:#fff; border:none; padding:10px 20px; border-radius:6px; cursor:pointer; margin-top:10px; }
        button:hover { background:#f57f17; }
        .mensaje { color:#f57f17; margin-bottom:10px; }
        a { text-decoration:none; color:#43a047; }
    </style>
</head>
<body>
<div class="contenedor">
    <a href="admin.php">&larr; Volver al panel</a>
    <h1>Editar dieta de <?php echo htmlspecialchars($dieta['nombre']); ?></h1>

    <?php if ($mensaje): ?>
        <div class="mensaje"><?php echo $mensaje; ?></div>
    <?php endif; ?>

    <form method="post">
        <label>Lunes</label>
        <textarea name="lunes"><?php echo $dieta['lunes']; ?></textarea>

        <label>Martes</label>
        <textarea name="martes"><?php echo $dieta['martes']; ?></textarea>

        <label>Miércoles</label>
        <textarea name="miercoles"><?php echo $dieta['miercoles']; ?></textarea>

        <label>Jueves</label>
        <textarea name="jueves"><?php echo $dieta['jueves']; ?></textarea>

        <label>Viernes</label>
        <textarea name="viernes"><?php echo $dieta['viernes']; ?></textarea>

        <label>Sábado</label>
        <textarea name="sabado"><?php echo $dieta['sabado']; ?></textarea>

        <label>Domingo</label>
        <textarea name="domingo"><?php echo $dieta['domingo']; ?></textarea>

        <label>Comentarios del admin (sugerencias saludables)</label>
        <textarea name="comentarios_admin"><?php echo $dieta['comentarios_admin']; ?></textarea>

        <button type="submit">Guardar cambios</button>
    </form>
</div>
</body>
</html>
