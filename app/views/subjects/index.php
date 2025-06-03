<?php
// Configuración de la paginación
$total_registros = count($data['subjects']);
$por_pagina = 10;
$pagina_actual = isset($_GET['pagina']) ? max(1, (int)$_GET['pagina']) : 1;
$total_paginas = ceil($total_registros / $por_pagina);

$offset = ($pagina_actual - 1) * $por_pagina;
$inicio = $offset + 1;
$fin = min($total_registros, $pagina_actual * $por_pagina);

// Subconjunto de materias para mostrar en la página actual
$materias_paginadas = array_slice($data['subjects'], $offset, $por_pagina);

// Ruta base para los enlaces de paginación
$base_url = '/subjects';
?>

<div class="contenedor-estudiantes">

    <!-- Alerta de éxito o error -->
    <?php
    $mensaje_exito = $data['success_message'] ?? '';
    $mensaje_error = $data['error_message'] ?? '';
    include __DIR__ . '/../components/alerta-flash.php';
    ?>

    <!-- Modal de confirmación para eliminar materia -->
    <?php $mensaje_confirmacion = "¿Estás seguro de que deseas eliminar esta materia?"; ?>
    <?php include __DIR__ . '/../components/modal-confirmacion.php'; ?>

    <!-- Título principal -->
    <h1><?= htmlspecialchars($data['title']) ?></h1>
    <hr>

    <!-- Encabezado: control de entradas y botón para nueva materia -->
    <?php
    $titulo = 'Mostrar';
    $cantidad = $por_pagina;
    $ruta_crear = '/subjects/create';
    $texto_boton = 'Nueva Materia';
    include __DIR__ . '/../components/encabezado-acciones.php';
    ?>

    <!-- Tabla de materias -->
    <div class="contenedor-tabla-estudiantes">
        <?php
        $columnas = [
            ['campo' => 'id', 'titulo' => 'ID'],
            ['campo' => 'materia', 'titulo' => 'Nombre'],
        ];

        $filas = $materias_paginadas;
        $ruta_base = '/subjects';

        include __DIR__ . '/../components/tabla-generica.php';
        ?>
    </div>

    <!-- Controles de paginación -->
    <?php include __DIR__ . '/../components/paginacion.php'; ?>
</div>

</body>

</html>