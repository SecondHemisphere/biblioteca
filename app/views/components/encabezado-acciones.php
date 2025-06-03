<?php

/**
 * Componente: encabezado-acciones.php
 * Descripción: Muestra el encabezado de una sección con el total de entradas visibles
 *              y un botón para crear un nuevo recurso.
 *
 * Parámetros esperados:
 * - $titulo (string): Texto del encabezado (por ejemplo, "Mostrar")
 * - $cantidad (int): Número de entradas mostradas actualmente
 * - $ruta_crear (string): URL del botón de creación
 * - $texto_boton (string): Texto del botón (opcional, por defecto: "Nuevo")
 */

$titulo = $titulo ?? 'Sección';
$cantidad = $cantidad ?? 0;
$ruta_crear = $ruta_crear ?? '#';
$texto_boton = $texto_boton ?? 'Nuevo';

?>

<div class="encabezado">
    <h2><?= htmlspecialchars($titulo) ?> | <?= $cantidad ?> | Entradas</h2>
    <div class="nuevo-estudiante">
        <a type="button" class="btn btn-primary" href="<?= htmlspecialchars($ruta_crear) ?>">
            <i class="fas fa-plus"></i> <?= htmlspecialchars($texto_boton) ?>
        </a>
    </div>
</div>