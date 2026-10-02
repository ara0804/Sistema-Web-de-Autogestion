<?php
include 'conexion.php';

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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menú Digital - Restaurante WEB</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; max-width: 800px; margin: auto; }
        .producto-card { border: 1px solid #ccc; padding: 15px; margin-bottom: 15px; border-radius: 8px; }
        .precio { color: green; font-weight: bold; }
        .controles-cantidad { display: flex; align-items: center; gap: 10px; margin-top: 10px; }
        .btn-cantidad { padding: 5px 15px; font-size: 16px; cursor: pointer; }
        .cantidad-texto { font-weight: bold; font-size: 18px; min-width: 20px; text-align: center; }
        .btn-confirmar { background-color: #4CAF50; color: white; padding: 15px 20px; border: none; border-radius: 5px; width: 100%; font-size: 18px; cursor: pointer; margin-top: 20px; }
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
            echo "<p class='precio'>$" . number_format($productoActual["precio"], 2) . " COP</p>";
            
            // Controles de cantidad para el RF2
            echo "<div class='controles-cantidad'>";
            echo "<button class='btn-cantidad' onclick='cambiarCantidad(" . $productoActual["id"] . ", -1)'>-</button>";
            echo "<span class='cantidad-texto' id='cantidad-" . $productoActual["id"] . "'>0</span>";
            echo "<button class='btn-cantidad' onclick='cambiarCantidad(" . $productoActual["id"] . ", 1)'>+</button>";
            echo "</div>";
            
            echo "</div>";
        }
    } else {
        echo "<p>No hay productos disponibles en este momento.</p>";
    }
    $conexionBd->close();
    ?>

    <button class="btn-confirmar" onclick="revisarPedido()">Revisar Pedido</button>

    <script>
        // Objeto para guardar lo que el cliente selecciona
        const pedidoActual = {};

        // Función que empieza con verbo (Regla de oro)
        function cambiarCantidad(idProducto, cambio) {
            // Inicializar en 0 si es la primera vez que se hace clic en este producto
            if (!pedidoActual[idProducto]) {
                pedidoActual[idProducto] = 0;
            }

            // Calcular nueva cantidad
            let nuevaCantidad = pedidoActual[idProducto] + cambio;

            // Evitar cantidades negativas
            if (nuevaCantidad < 0) {
                nuevaCantidad = 0;
            }

            // Guardar en el objeto y actualizar la interfaz (AC1: sin recargar la página)
            pedidoActual[idProducto] = nuevaCantidad;
            document.getElementById('cantidad-' + idProducto).innerText = nuevaCantidad;
        }

        function revisarPedido() {
            console.log("Pedido actual:", pedidoActual);
            alert("Abre la consola del navegador (F12) para ver el objeto del pedido guardado.");
        }
    </script>
</body>
</html>