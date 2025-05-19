<?php
    // Configuración de paginación
    $total_registros= count($data['students']);
    $por_pagina = 10;
    $pagina_actual = isset($_GET['pagina']) ? max(1, (int)$_GET['pagina']) : 1;
    $total_paginas = ceil($total_registros / $por_pagina);

    // Calcular valores para mostrar
    $inicio = max(1, ($pagina_actual - 1) * $por_pagina + 1);
    $fin = min($total_registros, $pagina_actual * $por_pagina);
    
    $offset = ($pagina_actual - 1) * $por_pagina;
    $estudiantes_paginados = array_slice($data['students'], $offset, $por_pagina);
?>

<div class="contenedor-estudiantes">
    <h1><?= htmlspecialchars($data['title']) ?></h1>
    
    <!-- Mensajes flash -->
    <?php if ($data['success_message']): ?>
        <div class="alert alert-success">
            <?= htmlspecialchars($data['success_message']) ?>
        </div>
    <?php endif; ?>
    
    <?php if ($data['error_message']): ?>
        <div class="alert alert-danger">
            <?= htmlspecialchars($data['error_message']) ?>
        </div>
    <?php endif; ?>
    
    <hr>
    
    <div class="encabezado">
        <h2>Mostrar | <?= $por_pagina ?> | Entradas</h2>

        <div class="nuevo-estudiante">
            <a href="/students/create" class="btn btn-primary">
                <i class="fas fa-plus"></i> Nuevo Estudiante
            </a>
        </div>
    </div>
    
    <div class="contenedor-tabla-estudiantes">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Código</th>
                    <th>DNI</th>
                    <th>Nombre</th>
                    <th>Carrera</th>
                    <th>Dirección</th>
                    <th>Teléfono</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($estudiantes_paginados as $estudiante): ?>
                <tr>
                    <td><?= htmlspecialchars($estudiante->id) ?></td>
                    <td><?= htmlspecialchars($estudiante->codigo) ?></td>
                    <td><?= htmlspecialchars($estudiante->dni) ?></td>
                    <td><?= htmlspecialchars($estudiante->nombre) ?></td>
                    <td><?= htmlspecialchars($estudiante->carrera) ?></td>
                    <td><?= htmlspecialchars($estudiante->direccion) ?></td>
                    <td><?= htmlspecialchars($estudiante->telefono) ?></td>
                    <td>
                        <span class="badge <?= $estudiante->estado == 1 ? 'badge-success' : 'badge-danger' ?>">
                            <?= $estudiante->estado == 1 ? 'Activo' : 'Inactivo' ?>
                        </span>
                    </td>
                    <td class="acciones-container">
                        <a href="/students/edit/<?= $estudiante->id ?>" class="btn btn-sm btn-warning accion-icon">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="/students/delete/<?= $estudiante->id ?>" method="POST" class="d-inline">
                            <button type="submit" class="btn btn-sm btn-danger accion-icon" onclick="return confirm('¿Estás seguro?')">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    
    <div class="paginacion-container">
                <nav>
                    <ul class="paginacion-list">
                        <li class="paginacion-item <?= $pagina_actual <= 1 ? 'disabled' : '' ?>">
                            <a href="?pagina=<?= $pagina_actual - 1 ?>" class="paginacion-link">Anterior</a>
                        </li>
                        
                        <?php for ($i = 1; $i <= $total_paginas; $i++): ?>
                            <li class="paginacion-item <?= $i == $pagina_actual ? 'active' : '' ?>">
                                <a href="?pagina=<?= $i ?>" class="paginacion-link"><?= $i ?></a>
                            </li>
                        <?php endfor; ?>
                        
                        <li class="paginacion-item <?= $pagina_actual >= $total_paginas ? 'disabled' : '' ?>">
                            <a href="?pagina=<?= $pagina_actual + 1 ?>" class="paginacion-link">Siguiente</a>
                        </li>
                    </ul>
                </nav>

            <div class="paginacion-info">
                Mostrando <span class="highlight"><?= $inicio ?></span> a <span class="highlight"><?= $fin ?></span> de <span class="highlight"><?= $total_registros ?></span> Entradas
            </div>

    </div>
</div>

<!-- Scripts -->
<script src="/assets/js/scripts.js"></script>
</body>
</html>