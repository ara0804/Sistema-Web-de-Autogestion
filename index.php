<?php
// Incluir la conexión a la base de datos
include 'conexion.php';

// Función que empieza con un VERBO, como exige el profesor
function obtenerProductosActivos($conexionBd) {
    $consultaSql = "SELECT id, nombre, descripcion, precio FROM productos WHERE estaDisponible = TRUE";
    return $conexionBd->query($consultaSql);
}

$listaProductos = obtenerProductosActivos($conexionBd);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <!-- Etiqueta vital para el AC3 (Responsividad en móviles) -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menú Digital - Restaurante WEB</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; max-width: 800px; margin: auto; }
        .producto-card { border: 1px solid #ccc; padding: 15px; margin-bottom: 15px; border-radius: 8px; }
        .precio { color: green; font-weight: bold; }
    </style>
</head>
<body>
    <h1>Menú Digital</h1>
    <p>Selecciona tus productos (Mesa pendiente)</p>

    <?php
    if ($listaProductos->num_rows > 0) {
        while($productoActual = $listaProductos->fetch_assoc()) {
            echo "<div class='producto-card'>";
            echo "<h3>" . htmlspecialchars($productoActual["nombre"]) . "</h3>";
            echo "<p>" . htmlspecialchars($productoActual["descripcion"]) . "</p>";
            // Formateando con la unidad de moneda clara
            echo "<p class='precio'>$" . number_format($productoActual["precio"], 2) . " COP</p>";
            echo "<button>Agregar</button>";
            echo "</div>";
        }
    } else {
        echo "<p>No hay productos disponibles en este momento.</p>";
    }
    $conexionBd->close();
    ?>
</body>
</html>