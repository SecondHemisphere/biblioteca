<?php
// Paginación
$total_registros = count($data['students']);
$por_pagina = 10;
$pagina_actual = isset($_GET['pagina']) ? max(1, (int)$_GET['pagina']) : 1;
$total_paginas = ceil($total_registros / $por_pagina);

$offset = ($pagina_actual - 1) * $por_pagina;
$inicio = $offset + 1;
$fin = min($total_registros, $pagina_actual * $por_pagina);

// Datos paginados
$estudiantes_paginados = array_slice($data['students'], $offset, $por_pagina);

// Ruta base para los enlaces de paginación
$base_url = '/students';
?>

<div class="contenedor-estudiantes">
    <?php
    $mensaje_exito = $data['success_message'] ?? '';
    $mensaje_error = $data['error_message'] ?? '';
    include __DIR__ . '/../components/alerta-flash.php';
    ?>

    <?php $mensaje_confirmacion = "¿Estás seguro de que deseas eliminar este estudiante?"; ?>
    <?php include __DIR__ . '/../components/modal-confirmacion.php'; ?>

    <h1><?= htmlspecialchars($data['title']) ?></h1>

    <hr>

    <div class="encabezado">
        <h2>Mostrar | <?= $por_pagina ?> | Entradas</h2>
        <div class="nuevo-estudiante">
            <a type="button" class="btn btn-primary" href="/students/create">
                <i class="fas fa-plus"></i> Nuevo Estudiante
            </a>
        </div>
    </div>

    <div class="contenedor-tabla-estudiantes">
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

    <?php include __DIR__ . '/../components/paginacion.php'; ?>
</div>

</body>

</html>