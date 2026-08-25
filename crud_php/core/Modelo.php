<?php
/**
 * Modelo - Clase base genérica
 * Un solo modelo puede manejar CUALQUIER tabla
 */

class Modelo
{
    protected Database $db;
    protected string $tabla;
    protected string $pk = 'id';
    protected array $fillable = [];
    protected array $rules = [];

    public function __construct()
    {
        $this->db = Database::conexion();
    }

    /**
     * Buscar todos los registros
     */
    public function todos(string $orden = 'id DESC'): array
    {
        return $this->db->rows("SELECT * FROM {$this->tabla} ORDER BY {$orden}");
    }

    /**
     * Buscar por ID
     */
    public function buscar(int $id): ?array
    {
        return $this->db->row("SELECT * FROM {$this->tabla} WHERE {$this->pk} = ?", [$id]);
    }

    /**
     * Buscar por campo
     */
    public function donde(string $campo, mixed $valor): array
    {
        return $this->db->rows("SELECT * FROM {$this->tabla} WHERE {$campo} = ?", [$valor]);
    }

    /**
     * Buscar por término (búsqueda general)
     */
    public function buscarTexto(string $termino, array $campos): array
    {
        $conditions = array_map(fn($c) => "{$c} LIKE ?", $campos);
        $params = array_fill(0, count($campos), "%{$termino}%");
        return $this->db->rows(
            "SELECT * FROM {$this->tabla} WHERE " . implode(' OR ', $conditions),
            $params
        );
    }

    /**
     * Crear registro
     */
    public function crear(array $datos): int
    {
        $datos = $this->filtrar($datos);
        return $this->db->insert($this->tabla, $datos);
    }

    /**
     * Actualizar registro
     */
    public function actualizar(int $id, array $datos): bool
    {
        $datos = $this->filtrar($datos);
        return $this->db->update($this->tabla, $datos, "{$this->pk} = ?", [$id]);
    }

    /**
     * Eliminar registro
     */
    public function eliminar(int $id): bool
    {
        return $this->db->delete($this->tabla, "{$this->pk} = ?", [$id]);
    }

    /**
     * Contar registros
     */
    public function contar(string $where = '1', array $params = []): int
    {
        return $this->db->count($this->tabla, $where, $params);
    }

    /**
     * Verificar si existe
     */
    public function existe(int $id): bool
    {
        return $this->contar("{$this->pk} = ?", [$id]) > 0;
    }

    /**
     * Paginación para DataTables
     */
    public function paginar(array $request): array
    {
        $start = (int) ($request['start'] ?? 0);
        
        // Si no viene de DataTables (no tiene 'draw') ni tiene un límite específico, mostramos todo (-1)
        $limit = isset($request['length']) ? (int) $request['length'] : (isset($request['draw']) ? 10 : -1);
        
        $search = $request['search']['value'] ?? '';
        $orderCol = $request['order'][0]['column'] ?? 0;
        $orderDir = $request['order'][0]['dir'] ?? 'asc';
        $columnas = $request['columns'] ?? [];

        // Buscar
        if ($search && !empty($columnas)) {
            $conditions = array_map(fn($c) => "{$c['data']} LIKE ?", $columnas);
            $params = array_fill(0, count($conditions), "%{$search}%");
            $where = implode(' OR ', $conditions);
            $total = $this->contar($where, $params);
            $sql = "SELECT * FROM {$this->tabla} WHERE {$where}";
        } else {
            $total = $this->contar();
            $sql = "SELECT * FROM {$this->tabla}";
            $params = [];
        }

        // Ordenar
        if (!empty($columnas[$orderCol]['data'])) {
            $col = $columnas[$orderCol]['data'];
            // Validar columna contra whitelist para prevenir inyección SQL
            if (in_array($col, $this->fillable) || $col === $this->pk) {
                $sql .= " ORDER BY {$col} " . (strtolower($orderDir) === 'desc' ? 'DESC' : 'ASC');
            }
        }

        // Paginar (si el límite es diferente de -1)
        if ($limit !== -1) {
            $sql .= " LIMIT {$start}, {$limit}";
        }
        $data = $this->db->rows($sql, $params);

        return [
            'draw' => (int) ($request['draw'] ?? 1),
            'recordsTotal' => $this->contar(),
            'recordsFiltered' => $total,
            'data' => $data,
        ];
    }

    /**
     * Filtrar solo campos permitidos
     */
    protected function filtrar(array $datos): array
    {
        if (empty($this->fillable)) return $datos;
        return array_filter($datos, fn($k) => in_array($k, $this->fillable), ARRAY_FILTER_USE_KEY);
    }

    /**
     * Validar datos
     */
    public function validar(array $datos): array
    {
        $errores = [];
        foreach ($this->rules as $campo => $reglas) {
            $valor = $datos[$campo] ?? '';
            $label = ucfirst($campo);

            if (!empty($reglas['required']) && empty(trim((string) $valor))) {
                $errores[$campo] = "{$label} es requerido";
                continue;
            }
            if (!empty($reglas['min']) && strlen(trim((string) $valor)) < $reglas['min']) {
                $errores[$campo] = "{$label} mínimo {$reglas['min']} caracteres";
                continue;
            }
            if (!empty($reglas['numeric']) && !is_numeric($valor)) {
                $errores[$campo] = "{$label} debe ser numérico";
                continue;
            }
            if (!empty($reglas['email']) && !filter_var($valor, FILTER_VALIDATE_EMAIL)) {
                $errores[$campo] = "{$label} debe ser un email válido";
            }
        }
        return $errores;
    }
}
