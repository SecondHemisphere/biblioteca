<?php

/**
 * Componente: tabla-imagenes.php
 * Tabla especializada para mostrar registros con imágenes
 *
 * Parámetros:
 * - $columnas: Array con definición de columnas
 * - $filas: Array de objetos con los datos
 * - $ruta_base: Ruta para acciones
 * - $titulo: Título descriptivo (ej. "Autores")
 */
?>

<?php if (!empty($filas)): ?>
    <table>
        <thead>
            <tr>
                <?php foreach ($columnas as $col): ?>
                    <th class="col-<?= htmlspecialchars($col['campo']) ?>">
                        <?= htmlspecialchars($col['titulo']) ?>
                    </th>
                <?php endforeach; ?>
                <th class="celda-acciones">Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($filas as $fila): ?>
                <tr>
                    <?php foreach ($columnas as $col): ?>
                        <td class="celda-<?= htmlspecialchars($col['campo']) ?>">
                            <?php
                            $valor = $fila->{$col['campo']} ?? '';
                            $tipo = $col['tipo'] ?? 'texto';

                            switch ($tipo) {
                                case 'imagen':
                                    if (!empty($valor)) {
                                        echo '<div class="contenedor-imagen-tabla">
                                                <img src="' . htmlspecialchars($valor) . '"
                                                     alt="' . htmlspecialchars($fila->nombre ?? $fila->nombres ?? '') . '">
                                            </div>';
                                    } else {
                                        echo '<div class="imagen-placeholder">
                                                <svg viewBox="0 0 24 24">
                                                    <path fill="currentColor" d="M8.5,13.5L11,16.5L14.5,12L19,18H5M21,19V5C21,3.89 20.1,3 19,3H5A2,2 0 0,0 3,5V19A2,2 0 0,0 5,21H19A2,2 0 0,0 21,19Z" />
                                                </svg>
                                            </div>';
                                    }
                                    break;

                                case 'estado':
                                    $clase = $valor ? 'activo' : 'inactivo';
                                    $texto = $valor ? 'Activo' : 'Inactivo';
                                    echo '<span class="estado ' . $clase . '">' . $texto . '</span>';
                                    break;

                                case 'fecha':
                                    echo !empty($valor) ? date('d/m/Y', strtotime($valor)) : '-';
                                    break;

                                default:
                                    echo htmlspecialchars($valor);
                            }
                            ?>
                        </td>
                    <?php endforeach; ?>
                    <td class="celda-acciones">
                        <a href="<?= $ruta_base ?>/edit/<?= $fila->id ?>" class="boton-accion editar" title="Editar">
                            <svg viewBox="0 0 24 24">
                                <path fill="currentColor" d="M20.71,7.04C21.1,6.65 21.1,6 20.71,5.63L18.37,3.29C18,2.9 17.35,2.9 16.96,3.29L15.12,5.12L18.87,8.87M3,17.25V21H6.75L17.81,9.93L14.06,6.18L3,17.25Z" />
                            </svg>
                        </a>
                        <form action="<?= $ruta_base ?>/delete/<?= $fila->id ?>" method="POST" class="form-eliminar">
                            <button type="submit" class="boton-accion eliminar" title="Eliminar" onclick="return confirm('¿Confirmas eliminar este registro?')">
                                <svg viewBox="0 0 24 24">
                                    <path fill="currentColor" d="M19,4H15.5L14.5,3H9.5L8.5,4H5V6H19M6,19A2,2 0 0,0 8,21H16A2,2 0 0,0 18,19V7H6V19Z" />
                                </svg>
                            </button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <div class="sin-registros">
        <svg viewBox="0 0 24 24">
            <path fill="currentColor" d="M11,9H13V7H11M12,20C7.59,20 4,16.41 4,12C4,7.59 7.59,4 12,4C16.41,4 20,7.59 20,12C20,16.41 16.41,20 12,20M12,2A10,10 0 0,0 2,12A10,10 0 0,0 12,22A10,10 0 0,0 22,12A10,10 0 0,0 12,2M11,17H13V11H11V17Z" />
        </svg>
        <p>No hay <?= htmlspecialchars($titulo) ?> registrados</p>
    </div>
<?php endif; ?>