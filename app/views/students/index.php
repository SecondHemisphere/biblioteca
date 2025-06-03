<?php
// Configuración de la paginación

// Opciones de registros por página para el selector del usuario.
$opciones_por_pagina = [5, 10, 25, 50, 100];

// Obtiene 'por_pagina' de la URL; si no es válido, usa 10 por defecto.
$por_pagina = isset($_GET['por_pagina']) && in_array((int)$_GET['por_pagina'], $opciones_por_pagina) ? (int)$_GET['por_pagina'] : 10;

$total_registros = count($data['students']); // Total de registros.
$pagina_actual = isset($_GET['pagina']) ? max(1, (int)$_GET['pagina']) : 1; // Página actual.
$total_paginas = ceil($total_registros / $por_pagina); // Total de páginas.

$offset = ($pagina_actual - 1) * $por_pagina; // Desplazamiento para la consulta.
$inicio = $offset + 1; // Primer registro visible.
$fin = min($total_registros, $pagina_actual * $por_pagina); // Último registro visible.

// Subconjunto de estudiantes para la página actual.
$estudiantes_paginados = array_slice($data['students'], $offset, $por_pagina);

// Ruta base para los enlaces de paginación.
// Asegúrate de que los enlaces de paginación conserven 'por_pagina'.
$base_url = '/students';
// Si el componente de paginación maneja parámetros, no modifiques $base_url aquí.

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