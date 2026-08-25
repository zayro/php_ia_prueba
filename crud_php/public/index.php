<?php
/**
 * Router Principal - Un solo archivo
 */

require_once __DIR__ . '/../config.php';

$modelo = new Producto();
$stats = $modelo->estadisticas();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= APP_NAME ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <link href="assets/style.css" rel="stylesheet">
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="#"><i class="fas fa-cube me-2"></i><?= APP_NAME ?></a>
        <span class="navbar-text text-light"><small>PHP + MariaDB + PDO</small></span>
    </div>
</nav>

<main class="container-fluid py-4">

    <!-- Estadísticas -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white text-center p-3">
                <h2><?= $stats['total'] ?></h2>
                <small>Total Productos</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white text-center p-3">
                <h2><?= $stats['activos'] ?></h2>
                <small>Activos</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-danger text-white text-center p-3">
                <h2><?= $stats['inactivos'] ?></h2>
                <small>Inactivos</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white text-center p-3">
                <h2>$<?= number_format($stats['valor_inventario']) ?></h2>
                <small>Inventario</small>
            </div>
        </div>
    </div>

    <!-- Tabla -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="fas fa-boxes-stacked me-2"></i>Productos</h5>
            <button class="btn btn-primary btn-sm" onclick="abrirModal()">
                <i class="fas fa-plus me-1"></i>Nuevo
            </button>
        </div>
        <div class="card-body">
            <table id="tabla" class="table table-hover" style="width:100%">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Descripción</th>
                        <th>Precio</th>
                        <th>Stock</th>
                        <th>Categoría</th>
                        <th>Estado</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

</main>

<!-- Modal -->
<div class="modal fade" id="modal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="formulario">
                <input type="hidden" id="id" name="id" value="">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="tituloModal">Nuevo Producto</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nombre *</label>
                        <input type="text" class="form-control" id="nombre" name="nombre" required>
                        <div class="invalid-feedback" id="err_nombre"></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Descripción</label>
                        <textarea class="form-control" id="descripcion" name="descripcion" rows="2"></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Precio *</label>
                            <input type="number" class="form-control" id="precio" name="precio" step="0.01" min="0" required>
                            <div class="invalid-feedback" id="err_precio"></div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Stock *</label>
                            <input type="number" class="form-control" id="stock" name="stock" min="0" required>
                            <div class="invalid-feedback" id="err_stock"></div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Estado</label>
                            <select class="form-select" id="estado" name="estado">
                                <option value="activo">Activo</option>
                                <option value="inactivo">Inactivo</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Categoría *</label>
                        <select class="form-select" id="categoria" name="categoria" required>
                            <option value="">Seleccionar...</option>
                            <option>Electrónica</option>
                            <option>Accesorios</option>
                            <option>Almacenamiento</option>
                            <option>Muebles</option>
                        </select>
                        <div class="invalid-feedback" id="err_categoria"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Toast -->
<div class="toast-container position-fixed bottom-0 end-0 p-3" id="toasts"></div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
<script src="assets/app.js"></script>

</body>
</html>
