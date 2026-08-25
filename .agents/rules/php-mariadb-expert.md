---
name: php-mariadb-bootstrap-datatables-expert
version: 1.0
description: "Expert agent instructions for PHP 8.0+, MariaDB 10.4 or higher, Bootstrap, jQuery, and DataTables."
applyTo:
  - "**/*.php"
  - "**/*.js"
  - "**/*.sql"
  - "**/*.html"
  - "README.md"
---

# PHP 8.0+, MariaDB 10.4+, Bootstrap, jQuery & DataTables Expert Guide

This ruleset enforces best practices and conventions when creating, refactoring, or maintaining endpoints, pages, or database schemas in this project.

---

## 1. Core Stack Requirements
- **PHP**: Version 8.0 or higher.
- **MariaDB**: Version 10.4 or higher.
- **Styling**: Bootstrap 5 (or version configured in public/index.php).
- **Libraries**: jQuery 3.7+ and DataTables 1.13+.

---

## 2. PHP 8.0+ Guidelines
- **Strict Typing**: Use `declare(strict_types=1);` in new PHP files unless specific compatibility forbids it.
- **Modern Syntax**: Leverage constructor property promotion, match expressions, union types, nullsafe operator, and named arguments where appropriate.
- **PDO & Database Safety**:
  - Always run database queries using the `Database` singleton (`Database::conexion()`).
  - Use prepared statements and place parameters in query calls (e.g., `$db->query($sql, $params)` or `$db->row(...)`). **Never** concatenate variables into SQL strings.
- **Security**:
  - Prevent XSS by escaping dynamic content with `htmlspecialchars($value, ENT_QUOTES, 'UTF-8')`.
  - Ensure API responses returning errors or data are properly sanitized and formatted as JSON (e.g., using `echo json_encode(...)`).

---

## 3. MariaDB 10.4+ Guidelines
- **Schema Design**:
  - Use lowercase snake_case for tables and columns (e.g. `product_id`, `created_at`).
  - Set InnoDB as the table engine: `ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci`.
  - Use appropriate types: `VARCHAR` for strings, `TEXT` for longer contents, `DECIMAL` for prices/financial values, `TINYINT(1)` or `BOOLEAN` for flags/status, and `TIMESTAMP`/`DATETIME` for dates.
  - Establish clear primary keys (`AUTO_INCREMENT`) and foreign keys with cascading where necessary.
- **SQL Queries**:
  - Write SQL keywords in uppercase (e.g. `SELECT`, `INSERT INTO`, `WHERE`, `JOIN`, `ON`).

---

## 4. UI: Bootstrap, jQuery & DataTables Guidelines
- **Responsive Layout**:
  - Structure using Bootstrap 5 container-row-column system (`.container-fluid`, `.row`, `.col-md-*`).
  - Use utility classes for styling (`d-flex`, `justify-content-between`, `align-items-center`, `mb-3`, `p-3`).
- **Dynamic Tables with DataTables**:
  - Initialize DataTables using `$('#tabla').DataTable({...})` with Bootstrap 5 integration wrapper styling.
  - Define custom column actions (e.g. Edit and Delete buttons) using the `columnDefs` and `render` functions.
  - Keep styling consistent with the dark theme header (`thead.table-dark`) and clean alignments.
- **Dynamic Interactions with jQuery & AJAX**:
  - Use `$(function() { ... })` (document ready) to bind events.
  - For dynamic or dynamically-loaded elements, bind handlers using delegated event syntax: `$(document).on('click', '.btn-action', function() { ... })`.
  - Use `$.ajax` or `$.get`/`$.post` for communication with backend controllers/APIs, ensuring proper handles for `success` (or `.done()`) and `error` (or `.fail()`).
  - Standardize error display to forms using CSS invalid feedback (`.invalid-feedback` and `.is-invalid` classes).
