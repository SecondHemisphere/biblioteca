<?php if (!empty($columnas) && !empty($filas)): ?>
<table>
    <thead>
        <tr>
            <?php foreach ($columnas as $col): ?>
                <th><?= htmlspecialchars($col['titulo']) ?></th>
            <?php endforeach; ?>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($filas as $fila): ?>
            <tr>
                <?php foreach ($columnas as $col): ?>
                    <td>
                        <?php
                            $key = $col['campo'];
                            $valor = $fila->$key ?? '';

                            if ($key === 'estado') {
                                echo '<span class="badge ' . ($valor == 1 ? 'badge-success' : 'badge-danger') . '">' .
                                     ($valor == 1 ? 'Activo' : 'Inactivo') . '</span>';
                            } else {
                                echo htmlspecialchars($valor);
                            }
                        ?>
                    </td>
                <?php endforeach; ?>
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
    <p>No hay registros disponibles.</p>
<?php endif; ?>
