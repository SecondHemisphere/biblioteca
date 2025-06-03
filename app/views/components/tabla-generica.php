<?php

/**
 * Componente: tabla-generica.php
 * Descripción: Renderiza una tabla dinámica con acciones para editar y eliminar registros.
 *
 * Parámetros esperados:
 * - $columnas (array): Lista de columnas a mostrar. Cada columna debe tener:
 *     - 'campo' (string): Nombre del atributo del objeto.
 *     - 'titulo' (string): Texto del encabezado de la columna.
 * - $filas (array): Lista de objetos con los datos a mostrar.
 * - $ruta_base (string): Ruta base para las acciones de editar y eliminar.
 */

?>

<?php if (!empty($columnas) && !empty($filas)): ?>
    <!-- Tabla de datos -->
    <table>
        <thead>
            <tr>
                <!-- Encabezados de columnas dinámicos -->
                <?php foreach ($columnas as $col): ?>
                    <th><?= htmlspecialchars($col['titulo']) ?></th>
                <?php endforeach; ?>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <!-- Filas de datos -->
            <?php foreach ($filas as $fila): ?>
                <tr>
                    <?php foreach ($columnas as $col): ?>
                        <td>
                            <?php
                            $key = $col['campo'];
                            $valor = $fila->$key ?? '';

                            // Mostrar estado con etiqueta de color
                            if ($key === 'estado') {
                                echo '<span class="badge ' . ($valor == 1 ? 'badge-success' : 'badge-danger') . '">' .
                                    ($valor == 1 ? 'Activo' : 'Inactivo') . '</span>';
                            } else {
                                echo htmlspecialchars($valor);
                            }
                            ?>
                        </td>
                    <?php endforeach; ?>
                    <!-- Acciones: editar y eliminar -->
                    <td class="acciones-container">
                        <a href="<?= $ruta_base ?>/edit/<?= $fila->id ?>" class="btn-accion btn-editar">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="<?= $ruta_base ?>/delete/<?= $fila->id ?>" method="POST" class="form-eliminar">
                            <button type="button" class="btn-accion btn-eliminar">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <!-- Mensaje si no hay registros -->
    <p>No hay registros disponibles.</p>
<?php endif; ?>