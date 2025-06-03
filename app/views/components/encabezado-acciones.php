<?php

/**
 * Componente: encabezado-acciones.php
 * Descripción: Muestra el encabezado de una sección con el total de entradas visibles
 * y un botón para crear un nuevo recurso.
 *
 * Parámetros esperados:
 * - $titulo (string): Texto del encabezado (por ejemplo, "Mostrar")
 * - $cantidad (int): Número de entradas mostradas actualmente
 * - $ruta_crear (string): URL del botón de creación
 * - $texto_boton (string): Texto del botón (opcional, por defecto: "Nuevo")
 * - $opciones_por_pagina (array): Array de números para las opciones de registros por página (ej: [5, 10, 25, 50])
 * - $por_pagina_actual (int): El número de registros por página seleccionado actualmente.
 */

$titulo = $titulo ?? 'Sección';
$cantidad = $cantidad ?? 0;
$ruta_crear = $ruta_crear ?? '#';
$texto_boton = $texto_boton ?? 'Nuevo';
$opciones_por_pagina = $opciones_por_pagina ?? [10, 25, 50, 100];
$por_pagina_actual = $por_pagina_actual ?? 10;

?>

<div class="encabezado">
    <h2>
        <?= htmlspecialchars($titulo) ?> |
        <form method="GET" action="/students" style="display:inline;">
            <select name="por_pagina" onchange="this.form.submit()">
                <?php foreach ($opciones_por_pagina as $opcion): ?>
                    <option value="<?= $opcion ?>" <?= $por_pagina == $opcion ? 'selected' : '' ?>>
                        <?= $opcion ?>
                    </option>
                <?php endforeach; ?>
            </select>
            | Entradas
        </form>
    </h2>
    <div class="nuevo-registro">
        <a type="button" class="btn btn-primary" href="<?= htmlspecialchars($ruta_crear) ?>">
            <i class="fas fa-plus"></i> <?= htmlspecialchars($texto_boton) ?>
        </a>
    </div>
</div>