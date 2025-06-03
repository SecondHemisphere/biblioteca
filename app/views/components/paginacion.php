<?php
// Este componente reutilizable espera las variables:
// $pagina_actual, $total_paginas, $total_registros, $inicio, $fin, $base_url
?>

<div class="paginacion-container">
    <nav>
        <ul class="paginacion-list">
            <li class="paginacion-item <?= $pagina_actual <= 1 ? 'disabled' : '' ?>">
                <a href="<?= $base_url ?>?pagina=<?= $pagina_actual - 1 ?>" class="paginacion-link">Anterior</a>
            </li>

            <?php for ($i = 1; $i <= $total_paginas; $i++): ?>
                <li class="paginacion-item <?= $i == $pagina_actual ? 'active' : '' ?>">
                    <a href="<?= $base_url ?>?pagina=<?= $i ?>" class="paginacion-link"><?= $i ?></a>
                </li>
            <?php endfor; ?>

            <li class="paginacion-item <?= $pagina_actual >= $total_paginas ? 'disabled' : '' ?>">
                <a href="<?= $base_url ?>?pagina=<?= $pagina_actual + 1 ?>" class="paginacion-link">Siguiente</a>
            </li>
        </ul>
    </nav>

    <div class="paginacion-info">
        Mostrando <span class="highlight"><?= $inicio ?></span> a <span class="highlight"><?= $fin ?></span> de <span class="highlight"><?= $total_registros ?></span> Entradas
    </div>
</div>
