# CRUD PHP para XAMPP/WAMP

## Instalación en XAMPP

### 1. Copiar proyecto a htdocs
```
C:\xampp\htdocs\crud_php\
```

### 2. Crear base de datos
- Abrir phpMyAdmin: http://localhost/phpmyadmin
- Importar archivo: `sql/schema.sql`

### 3. Configurar contraseña (si es necesario)
Editar `config.php`:
```php
define('DB_PASS', '');  // Vacío para XAMPP por defecto
```

### 4. Acceder al sistema
```
http://localhost/crud_php/public/
```

## Estructura
```
crud_php/
├── config.php          # Configuración BD
├── core/               # Núcleo del sistema
├── app/models/         # Modelos de BD
├── public/             # Archivos públicos (index.php, api.php)
├── assets/             # CSS y JS
└── sql/                # Scripts SQL
```

## Troubleshooting

### Error de conexión a BD
- Verificar que MariaDB/MySQL esté corriendo en XAMPP
- Verificar credenciales en `config.php`
- Verificar que la BD `crud_productos` exista

### Error 404
- Asegurar que Apache esté corriendo
- Verificar que la carpeta `public` esté en la raíz del proyecto

### Error de permisos
- Verificar permisos de lectura/escritura en la carpeta
