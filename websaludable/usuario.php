<?php
require 'conexion.php';

if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_rol'] !== 'usuario') {
    header("Location: index.php");
    exit;
}

$usuario_id = $_SESSION['usuario_id'];
$mensaje = "";

// Cargar dieta existente
$stmt = $conexion->prepare("SELECT * FROM dietas WHERE usuario_id = ? LIMIT 1");
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$dieta = $stmt->get_result()->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $lunes     = $_POST['lunes'] ?? '';
    $martes    = $_POST['martes'] ?? '';
    $miercoles = $_POST['miercoles'] ?? '';
    $jueves    = $_POST['jueves'] ?? '';
    $viernes   = $_POST['viernes'] ?? '';
    $sabado    = $_POST['sabado'] ?? '';
    $domingo   = $_POST['domingo'] ?? '';

    if ($dieta) {
        // UPDATE
        $stmt = $conexion->prepare(
            "UPDATE dietas SET lunes=?, martes=?, miercoles=?, jueves=?, viernes=?, sabado=?, domingo=? 
             WHERE id=?"
        );
        $stmt->bind_param("sssssssi", $lunes, $martes, $miercoles, $jueves, $viernes, $sabado, $domingo, $dieta['id']);
        $stmt->execute();
        $mensaje = "Dieta actualizada correctamente 💚";
    } else {
        // INSERT
        $stmt = $conexion->prepare(
            "INSERT INTO dietas (usuario_id, lunes, martes, miercoles, jueves, viernes, sabado, domingo) 
             VALUES (?,?,?,?,?,?,?,?)"
        );
        $stmt->bind_param("isssssss", $usuario_id, $lunes, $martes, $miercoles, $jueves, $viernes, $sabado, $domingo);
        $stmt->execute();
        $mensaje = "Dieta guardada correctamente 💚";
    }

    // recargar dieta
    $stmt = $conexion->prepare("SELECT * FROM dietas WHERE usuario_id = ? LIMIT 1");
    $stmt->bind_param("i", $usuario_id);
    $stmt->execute();
    $dieta = $stmt->get_result()->fetch_assoc();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mi dieta semanal</title>
    <style>
        body { background:#f5fff5; font-family:Arial,sans-serif; }
        .contenedor { max-width:800px; margin:30px auto; background:#fff; padding:20px; border-radius:10px; box-shadow:0 0 10px rgba(0,0,0,.1); }
        h1 { color:#2e7d32; }
        textarea { width:100%; min-height:60px; margin:6px 0; border-radius:6px; border:1px solid #ccc; padding:8px; }
        button { background:#43a047; color:#fff; border:none; padding:10px 20px; border-radius:6px; cursor:pointer; margin-top:10px; }
        button:hover { background:#2e7d32; }
        .mensaje { color:#2e7d32; margin-bottom:10px; }
        .comentarios { background:#e8f5e9; padding:10px; border-radius:6px; margin-top:15px; }
        .topbar { display:flex; justify-content:space-between; align-items:center; }
        a { color:#e53935; text-decoration:none; }
    </style>
</head>
<body>
<div class="contenedor">
    <div class="topbar">
        <h1>Hola, <?php echo htmlspecialchars($_SESSION['usuario_nombre']); ?> 🥦</h1>
        <a href="logout.php">Cerrar sesión</a>
    </div>
    <p>Escribe lo que planeas comer cada día. El administrador podrá sugerirte cambios más saludables.</p>

    <?php if ($mensaje): ?>
        <div class="mensaje"><?php echo $mensaje; ?></div>
    <?php endif; ?>

    <form method="post">
        <label>Lunes</label>
        <textarea name="lunes"><?php echo $dieta['lunes'] ?? ''; ?></textarea>

        <label>Martes</label>
        <textarea name="martes"><?php echo $dieta['martes'] ?? ''; ?></textarea>

        <label>Miércoles</label>
        <textarea name="miercoles"><?php echo $dieta['miercoles'] ?? ''; ?></textarea>

        <label>Jueves</label>
        <textarea name="jueves"><?php echo $dieta['jueves'] ?? ''; ?></textarea>

        <label>Viernes</label>
        <textarea name="viernes"><?php echo $dieta['viernes'] ?? ''; ?></textarea>

        <label>Sábado</label>
        <textarea name="sabado"><?php echo $dieta['sabado'] ?? ''; ?></textarea>

        <label>Domingo</label>
        <textarea name="domingo"><?php echo $dieta['domingo'] ?? ''; ?></textarea>

        <button type="submit">Guardar dieta</button>
    </form>

    <?php if (!empty($dieta['comentarios_admin'])): ?>
        <div class="comentarios">
            <strong>Comentarios del nutricionista (admin):</strong>
            <p><?php echo nl2br(htmlspecialchars($dieta['comentarios_admin'])); ?></p>
        </div>
    <?php endif; ?>
</div>
</body>
</html>
