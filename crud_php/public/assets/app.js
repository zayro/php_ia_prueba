/**
 * app.js - JavaScript mínimo y reutilizable
 */

const API = 'api.php';
let tabla, modal;

$(function () {
    modal = new bootstrap.Modal('#modal');
    initTabla();
    initForm();
});

/**
 * Inicializar DataTable
 */
function initTabla() {
    tabla = $('#tabla').DataTable({
        ajax: {
            url: `${API}?action=list`,
            dataSrc: 'data'
        },
        columns: [
            { data: 'id' },
            { data: 'nombre' },
            { data: 'descripcion', render: v => v || '<em class="text-muted">-</em>' },
            { data: 'precio', render: v => '$' + Number(v).toLocaleString() },
            { data: 'stock', render: v => `<span class="badge bg-${v > 0 ? 'success' : 'danger'}">${v}</span>`, className: 'text-center' },
            { data: 'categoria' },
            { data: 'estado', render: v => `<span class="badge bg-${v === 'activo' ? 'success' : 'secondary'}">${v}</span>` },
            {
                data: null, orderable: false, className: 'text-center',
                render: d => `
                    <button class="btn btn-sm btn-info" onclick="ver(${d.id})"><i class="fas fa-eye"></i></button>
                    <button class="btn btn-sm btn-warning" onclick="editar(${d.id})"><i class="fas fa-edit"></i></button>
                    <button class="btn btn-sm btn-danger" onclick="eliminar(${d.id})"><i class="fas fa-trash"></i></button>
                `
            }
        ],
        language: { url: 'https://cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json' },
        order: [[0, 'desc']]
    });
}

/**
 * Ver detalle
 */
function ver(id) {
    $.get(`${API}?action=get&id=${id}`, r => {
        if (r.success) {
            const d = r.data;
            
            // Llenar datos y deshabilitar controles para vista de solo lectura
            $('#id').val(d.id);
            $('#nombre').val(d.nombre).prop('disabled', true);
            $('#descripcion').val(d.descripcion).prop('disabled', true);
            $('#precio').val(d.precio).prop('disabled', true);
            $('#stock').val(d.stock).prop('disabled', true);
            $('#categoria').val(d.categoria).prop('disabled', true);
            $('#estado').val(d.estado).prop('disabled', true);

            // Limpiar clases de validación y mensajes de error
            $('.form-control, .form-select').removeClass('is-invalid');
            $('.invalid-feedback').text('');

            // Ajustar modal
            $('#tituloModal').text('Detalle del Producto');
            $('#formulario button[type="submit"]').hide();
            modal.show();
        }
    });
}

/**
 * Editar
 */
function editar(id) {
    $.get(`${API}?action=get&id=${id}`, r => {
        if (r.success) {
            const d = r.data;

            // Habilitar controles y limpiar clases de validación
            $('#formulario .form-control, #formulario .form-select')
                .prop('disabled', false)
                .removeClass('is-invalid');
            $('.invalid-feedback').text('');
            $('#formulario button[type="submit"]').show();

            $('#id').val(d.id);
            $('#nombre').val(d.nombre);
            $('#descripcion').val(d.descripcion);
            $('#precio').val(d.precio);
            $('#stock').val(d.stock);
            $('#categoria').val(d.categoria);
            $('#estado').val(d.estado);

            $('#tituloModal').text('Editar Producto');
            modal.show();
        }
    });
}

/**
 * Eliminar
 */
function eliminar(id) {
    if (!confirm('¿Eliminar este producto?')) return;
    $.post(`${API}?action=delete`, { id }, r => {
        if (r.success) {
            tabla.ajax.reload();
            toast(r.message, 'success');
        }
    });
}

/**
 * Abrir modal para nuevo
 */
function abrirModal() {
    $('#formulario')[0].reset();
    $('#id').val('');

    // Habilitar controles, mostrar botón de guardar y limpiar clases de validación
    $('#formulario .form-control, #formulario .form-select')
        .prop('disabled', false)
        .removeClass('is-invalid');
    $('.invalid-feedback').text('');
    $('#formulario button[type="submit"]').show();

    $('#tituloModal').text('Nuevo Producto');
    modal.show();
}

/**
 * Guardar (crear/editar)
 */
function initForm() {
    $('#formulario').on('submit', function (e) {
        e.preventDefault();
        const id = $('#id').val();
        const accion = id ? `update&id=${id}` : 'create';

        $.post(`${API}?action=${accion}`, $(this).serialize(), r => {
            if (r.success) {
                modal.hide();
                tabla.ajax.reload();
                toast(r.message, 'success');
            } else if (r.errors) {
                $.each(r.errors, (k, v) => {
                    $(`#${k}`).addClass('is-invalid');
                    $(`#err_${k}`).text(v);
                });
            }
        }).fail(xhr => {
            const r = xhr.responseJSON;
            if (r?.message) toast(r.message, 'danger');
        });
    });
}

/**
 * Notificación toast
 */
function toast(msg, type = 'success') {
    const html = `
        <div class="toast align-items-center text-bg-${type} border-0 show" role="alert">
            <div class="d-flex">
                <div class="toast-body">${msg}</div>
                <button class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        </div>`;
    const el = $(html).appendTo('#toasts');
    setTimeout(() => el.fadeOut(300, () => el.remove()), 3000);
}
