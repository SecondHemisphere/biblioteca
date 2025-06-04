<?php
// Lógica de paginación
$por_pagina = isset($_GET['por_pagina']) ? (int) $_GET['por_pagina'] : 10;
$total_registros = count($data['authors']);
$pagina_actual = isset($_GET['pagina']) ? max(1, (int)$_GET['pagina']) : 1;
$total_paginas = ceil($total_registros / $por_pagina);

$offset = ($pagina_actual - 1) * $por_pagina;
$registros_paginados = array_slice($data['authors'], $offset, $por_pagina);

$inicio = $offset + 1;
$fin = min($total_registros, $pagina_actual * $por_pagina);

?>

<div class="contenedor-listados">

    <!-- Alerta de éxito o error -->
    <?php
    $mensaje_exito = $data['success_message'] ?? '';
    $mensaje_error = $data['error_message'] ?? '';
    include __DIR__ . '/../components/alerta-flash.php';
    ?>

    <!-- Modal de confirmación para eliminar autor-->
    <?php $mensaje_confirmacion = "¿Estás seguro de que deseas eliminar este autor?"; ?>
    <?php include __DIR__ . '/../components/modal-confirmacion.php'; ?>

    <!-- Título principal -->
    <h1><?= htmlspecialchars($data['title']) ?></h1>
    <hr>

    <!-- Encabezado: control de entradas y botón para nuevo autor -->
    <?php
    $ruta_index = '/authors';
    $ruta_crear = '/authors/create';
    $texto_boton = 'Nuevo Autor';
    $opciones_por_pagina = [5, 10, 25, 50];
    include __DIR__ . '/../components/encabezado-acciones.php';
    ?>

    <!-- Tabla de materias -->
    <div class="contenedor-tabla">
        <?php
        $columnas = [
            ['campo' => 'id', 'titulo' => 'ID'],
            ['campo' => 'nombres', 'titulo' => 'Nombres'],
            ['campo' => 'apellidos', 'titulo' => 'Apellidos'],
            ['campo' => 'fecha_nacimiento', 'titulo' => 'Fecha de Nacimiento'],
            ['campo' => 'fecha_fallecimiento', 'titulo' => 'Fecha de Fallecimiento'],
            ['campo' => 'nacionalidad', 'titulo' => 'Nacionalidad'],
            ['campo' => 'campo_estudio', 'titulo' => 'Campo de Estudio'],
            ['campo' => 'biografia', 'titulo' => 'Biografía'],
            ['campo' => 'imagen', 'titulo' => 'Imagen'],
            ['campo' => 'estado', 'titulo' => 'Estado'],
        ];

        $filas = $registros_paginados;
        $ruta_base = '/authors';

        include __DIR__ . '/../components/tabla-generica.php';
        ?>
    </div>

    <!-- Controles de paginación -->
    <?php include __DIR__ . '/../components/paginacion.php'; ?>
</div>

</body>

</html>