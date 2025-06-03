<?php
// Configuración de la paginación
$total_registros = count($data['students']);
$por_pagina = 10;
$pagina_actual = isset($_GET['pagina']) ? max(1, (int)$_GET['pagina']) : 1;
$total_paginas = ceil($total_registros / $por_pagina);

$offset = ($pagina_actual - 1) * $por_pagina;
$inicio = $offset + 1;
$fin = min($total_registros, $pagina_actual * $por_pagina);

// Subconjunto de estudiantes para mostrar en la página actual
$estudiantes_paginados = array_slice($data['students'], $offset, $por_pagina);

// Ruta base para los enlaces de paginación
$base_url = '/students';
?>

<div class="contenedor-listados">

    <!-- Alerta de éxito o error -->
    <?php
    $mensaje_exito = $data['success_message'] ?? '';
    $mensaje_error = $data['error_message'] ?? '';
    include __DIR__ . '/../components/alerta-flash.php';
    ?>

    <!-- Modal de confirmación para eliminar estudiante -->
    <?php $mensaje_confirmacion = "¿Estás seguro de que deseas eliminar este estudiante?"; ?>
    <?php include __DIR__ . '/../components/modal-confirmacion.php'; ?>

    <!-- Título principal -->
    <h1><?= htmlspecialchars($data['title']) ?></h1>
    <hr>

    <!-- Encabezado: control de entradas y botón para nuevo estudiante -->
    <?php
    $titulo = 'Mostrar';
    $cantidad = $por_pagina;
    $ruta_crear = '/students/create';
    $texto_boton = 'Nuevo Estudiante';
    include __DIR__ . '/../components/encabezado-acciones.php';
    ?>

    <!-- Tabla de estudiantes -->
    <div class="contenedor-tabla">
        <?php
        $columnas = [
            ['campo' => 'id', 'titulo' => 'ID'],
            ['campo' => 'codigo', 'titulo' => 'Código'],
            ['campo' => 'dni', 'titulo' => 'DNI'],
            ['campo' => 'nombre', 'titulo' => 'Nombre'],
            ['campo' => 'carrera', 'titulo' => 'Carrera'],
            ['campo' => 'direccion', 'titulo' => 'Dirección'],
            ['campo' => 'telefono', 'titulo' => 'Teléfono'],
            ['campo' => 'estado', 'titulo' => 'Estado'],
        ];

        $filas = $estudiantes_paginados;
        $ruta_base = '/students';

        include __DIR__ . '/../components/tabla-generica.php';
        ?>
    </div>

    <!-- Controles de paginación -->
    <?php include __DIR__ . '/../components/paginacion.php'; ?>
</div>

</body>

</html>