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

<div class="contenedor-tabla-imagenes">
    <?php if (!empty($filas)): ?>
        <table class="tabla-estilizada">
            <thead>
                <tr>
                    <?php foreach ($columnas as $col): ?>
                        <th class="col-<?= htmlspecialchars($col['campo']) ?>">
                            <?= htmlspecialchars($col['titulo']) ?>
                        </th>
                    <?php endforeach; ?>
                    <th class="col-acciones">Acciones</th>
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
                                                <img src="'.htmlspecialchars($valor).'" 
                                                     alt="'.htmlspecialchars($fila->nombre ?? $fila->nombres ?? '').'">
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
                                        echo '<span class="estado '.$clase.'">'.$texto.'</span>';
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
</div>
<style>
/* Contenedor principal */
.contenedor-tabla-imagenes {
    width: 100%;
    overflow-x: auto;
    margin: 20px 0;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    border-radius: 6px;
    background: white;
}

/* Estilos de tabla */
.tabla-estilizada {
    width: 100%;
    border-collapse: collapse;
    font-family: Arial, sans-serif;
}

.tabla-estilizada th {
    background-color: #f5f7fa;
    color: #333;
    font-weight: 600;
    text-align: left;
    padding: 12px 15px;
    border-bottom: 2px solid #e1e5eb;
    text-transform: uppercase;
    font-size: 0.85rem;
    letter-spacing: 0.5px;
}

.tabla-estilizada td {
    padding: 12px 15px;
    border-bottom: 1px solid #e1e5eb;
    vertical-align: middle;
}

.tabla-estilizada tr:last-child td {
    border-bottom: none;
}

.tabla-estilizada tr:hover {
    background-color: #f8f9fa;
}

/* Celdas de imagen */
.contenedor-imagen-tabla {
    width: 60px;
    height: 60px;
    border-radius: 4px;
    overflow: hidden;
}

.contenedor-imagen-tabla img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.contenedor-imagen-tabla:hover img {
    transform: scale(1.05);
}

.imagen-placeholder {
    width: 60px;
    height: 60px;
    background-color: #f1f3f5;
    border-radius: 4px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #adb5bd;
}

.imagen-placeholder svg {
    width: 24px;
    height: 24px;
}

/* Estados */
.estado {
    display: inline-block;
    padding: 4px 10px;
    border-radius: 12px;
    font-size: 0.75rem;
    font-weight: 500;
}

.estado.activo {
    background-color: #e6f7ee;
    color: #0a7e4a;
}

.estado.inactivo {
    background-color: #fde8e8;
    color: #c81e1e;
}

/* Botones de acción */
.celda-acciones {
    white-space: nowrap;
}

.boton-accion {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 32px;
    height: 32px;
    border-radius: 50%;
    border: none;
    background: transparent;
    cursor: pointer;
    margin: 0 2px;
    padding: 0;
    transition: all 0.2s ease;
}

.boton-accion svg {
    width: 18px;
    height: 18px;
}

.boton-accion.editar {
    color: #2c7be5;
    border: 1px solid #2c7be5;
}

.boton-accion.editar:hover {
    background: #2c7be5;
    color: white;
}

.boton-accion.eliminar {
    color: #e63757;
    border: 1px solid #e63757;
}

.boton-accion.eliminar:hover {
    background: #e63757;
    color: white;
}

/* Mensaje sin registros */
.sin-registros {
    padding: 40px 20px;
    text-align: center;
    color: #6c757d;
}

.sin-registros svg {
    width: 48px;
    height: 48px;
    margin-bottom: 15px;
    color: #adb5bd;
}

.sin-registros p {
    margin: 0;
    font-size: 1.1rem;
}

/* Responsive */
@media (max-width: 768px) {
    .tabla-estilizada {
        display: block;
    }
    
    .tabla-estilizada thead {
        display: none;
    }
    
    .tabla-estilizada tbody, 
    .tabla-estilizada tr, 
    .tabla-estilizada td {
        display: block;
        width: 100%;
    }
    
    .tabla-estilizada tr {
        margin-bottom: 15px;
        border: 1px solid #e1e5eb;
        border-radius: 6px;
    }
    
    .tabla-estilizada td {
        padding: 10px 15px;
        border-bottom: 1px solid #e1e5eb;
    }
    
    .tabla-estilizada td:last-child {
        border-bottom: none;
    }
    
    .celda-acciones {
        display: flex;
        justify-content: center;
        padding: 15px !important;
    }
    
    /* Estilo para mostrar el nombre del campo en móviles */
    .tabla-estilizada td::before {
        content: attr(data-label);
        font-weight: bold;
        display: inline-block;
        width: 120px;
        color: #495057;
    }
    
    /* Necesitarías añadir data-label a cada td en el PHP */
}
</style>