---
name: php-mariadb-bootstrap-datatables-expert
version: 1.0
description: "Instrucciones de experto para agentes sobre PHP 8.0+, MariaDB 10.4 o superior, Bootstrap, jQuery y DataTables."
applyTo:
  - "**/*.php"
  - "**/*.js"
  - "**/*.sql"
  - "**/*.html"
  - "README.md"
---

# Guía experta de PHP 8.0+, MariaDB 10.4+, Bootstrap, jQuery y DataTables

Este conjunto de reglas establece buenas prácticas y convenciones para crear, refactorizar o mantener endpoints, páginas o esquemas de base de datos en este proyecto.

---

## 1. Requisitos de la pila principal
- **PHP**: versión 8.0 o superior.
- **MariaDB**: versión 10.4 o superior.
- **Estilos**: Bootstrap 5 (o la versión configurada en public/index.php).
- **Librerías**: jQuery 3.7+ y DataTables 1.13+.

---

## 2. Directrices para PHP 8.0+
- **Tipado estricto**: usa `declare(strict_types=1);` en los archivos PHP nuevos, salvo que exista una incompatibilidad específica.
- **Sintaxis moderna**: utiliza promoción de propiedades del constructor, expresiones `match`, tipos unión, el operador nullsafe y argumentos con nombre cuando corresponda.
- **Seguridad de PDO y de la base de datos**:
  - Ejecuta siempre las consultas mediante el singleton `Database` (`Database::conexion()`).
  - Usa sentencias preparadas y proporciona los parámetros en las llamadas a las consultas (por ejemplo, `$db->query($sql, $params)` o `$db->row(...)`). **Nunca** concatentes variables en cadenas SQL.
- **Seguridad**:
  - Evita XSS escapando el contenido dinámico con `htmlspecialchars($value, ENT_QUOTES, 'UTF-8')`.
  - Asegúrate de que las respuestas de la API que devuelven errores o datos estén correctamente sanitizadas y formateadas como JSON (por ejemplo, mediante `echo json_encode(...)`).

---

## 3. Directrices para MariaDB 10.4+
- **Diseño del esquema**:
  - Usa `snake_case` en minúsculas para tablas y columnas (por ejemplo, `product_id`, `created_at`).
  - Configura InnoDB como motor de la tabla: `ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci`.
  - Usa tipos apropiados: `VARCHAR` para cadenas, `TEXT` para contenidos extensos, `DECIMAL` para precios y valores financieros, `TINYINT(1)` o `BOOLEAN` para indicadores o estados, y `TIMESTAMP`/`DATETIME` para fechas.
  - Establece claves primarias claras (`AUTO_INCREMENT`) y claves foráneas con eliminación o actualización en cascada cuando sea necesario.
- **Consultas SQL**:
  - Escribe las palabras clave SQL en mayúsculas (por ejemplo, `SELECT`, `INSERT INTO`, `WHERE`, `JOIN`, `ON`).

---

## 4. Directrices de UI: Bootstrap, jQuery y DataTables
- **Diseño adaptable**:
  - Estructura la interfaz con el sistema de contenedores, filas y columnas de Bootstrap 5 (`.container-fluid`, `.row`, `.col-md-*`).
  - Usa clases de utilidad para los estilos (`d-flex`, `justify-content-between`, `align-items-center`, `mb-3`, `p-3`).
- **Tablas dinámicas con DataTables**:
  - Inicializa DataTables mediante `$('#tabla').DataTable({...})` con los estilos de integración del contenedor de Bootstrap 5.
  - Define acciones personalizadas para las columnas (por ejemplo, botones Editar y Eliminar) mediante las funciones `columnDefs` y `render`.
  - Mantén los estilos coherentes con el encabezado de tema oscuro (`thead.table-dark`) y alineaciones limpias.
- **Interacciones dinámicas con jQuery y AJAX**:
  - Usa `$(function() { ... })` (document ready) para enlazar eventos.
  - Para elementos dinámicos o cargados dinámicamente, enlaza los controladores mediante la sintaxis de eventos delegados: `$(document).on('click', '.btn-action', function() { ... })`.
  - Usa `$.ajax` o `$.get`/`$.post` para comunicarte con los controladores o las API del backend, gestionando correctamente `success` (o `.done()`) y `error` (o `.fail()`).
  - Estandariza la visualización de errores en los formularios mediante la realimentación de validación de CSS (clases `.invalid-feedback` y `.is-invalid`).
