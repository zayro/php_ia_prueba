<?php
/**
 * Database - Conexión PDO Singleton
 * Máxima simplicidad: una clase que lo hace todo
 */

class Database
{
    private static ?self $instancia = null;
    private PDO $db;

    private function __construct()
    {
        $dsn = "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES " . DB_CHARSET,
        ];

        $this->db = new PDO($dsn, DB_USER, DB_PASS, $options);
    }

    public static function conexion(): self
    {
        if (!self::$instancia) {
            self::$instancia = new self();
        }
        return self::$instancia;
    }

    public function sql(): PDO
    {
        return $this->db;
    }

    /**
     * Ejecutar query con análisis automático
     */
    public function query(string $sql, array $params = []): PDOStatement
    {
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    /**
     * Obtener una fila
     */
    public function row(string $sql, array $params = []): ?array
    {
        $result = $this->query($sql, $params)->fetch();
        return $result ?: null;
    }

    /**
     * Obtener todas las filas
     */
    public function rows(string $sql, array $params = []): array
    {
        return $this->query($sql, $params)->fetchAll();
    }

    /**
     * Obtener un valor (count, sum, etc)
     */
    public function val(string $sql, array $params = []): mixed
    {
        return $this->query($sql, $params)->fetchColumn();
    }

    /**
     * Insertar y retornar ID
     */
    public function insert(string $table, array $data): int
    {
        $cols = implode(', ', array_keys($data));
        $vals = ':' . implode(', :', array_keys($data));
        $this->query("INSERT INTO {$table} ({$cols}) VALUES ({$vals})", $data);
        return (int) $this->db->lastInsertId();
    }

    /**
     * Actualizar registros
     */
    public function update(string $table, array $data, string $where, array $whereParams = []): bool
    {
        $set = implode(', ', array_map(fn($c) => "{$c} = ?", array_keys($data)));
        $params = array_merge(array_values($data), $whereParams);
        return $this->query("UPDATE {$table} SET {$set} WHERE {$where}", $params)->rowCount() > 0;
    }

    /**
     * Eliminar registros
     */
    public function delete(string $table, string $where, array $params = []): bool
    {
        return $this->query("DELETE FROM {$table} WHERE {$where}", $params)->rowCount() > 0;
    }

    /**
     * Contar registros
     */
    public function count(string $table, string $where = '1', array $params = []): int
    {
        return (int) $this->val("SELECT COUNT(*) FROM {$table} WHERE {$where}", $params);
    }
}
