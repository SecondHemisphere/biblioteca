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
    <!-- Modal personalizado para mensajes flash -->
    <?php if ($data['success_message'] || $data['error_message']): ?>
        <div id="customAlert" class="custom-alert" style="display: flex;">
            <div class="alert-content <?= $data['error_message'] ? 'error' : 'success' ?>">
                <div class="alert-icon">
                    <?= $data['error_message'] ? '✕' : '✓' ?>
                </div>
                <h3><?= $data['error_message'] ? 'Error' : '¡Correcto!' ?></h3>
                <p><?= htmlspecialchars($data['error_message'] ?: $data['success_message']) ?></p>
                <button id="alertConfirmBtn" class="alert-button">
                    Aceptar
                </button>
            </div>
        </div>
    <?php endif; ?>

    <div id="confirmModal" class="custom-confirm">
        <div class="confirm-content">
            <div class="confirm-icon">!</div>
            <h3>Confirmar acción</h3>
            <p>¿Estás seguro de que deseas eliminar este registro?</p>
            <div class="confirm-actions">
                <button id="confirmCancel" class="btn-cancel">Cancelar</button>
                <button id="confirmDelete" class="btn-confirm">Eliminar</button>
            </div>
        </div>
    </div>

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