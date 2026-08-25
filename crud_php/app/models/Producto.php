<?php
/**
 * Modelo Producto - Ejemplo de uso del sistema
 * Solo definir tabla, campos y reglas
 */

class Producto extends Modelo
{
    protected string $tabla = 'productos';
    protected array $fillable = ['nombre', 'descripcion', 'precio', 'stock', 'categoria', 'estado'];
    protected array $rules = [
        'nombre'    => ['required' => true, 'min' => 3],
        'precio'    => ['required' => true, 'numeric' => true],
        'stock'     => ['required' => true, 'numeric' => true],
        'categoria' => ['required' => true],
    ];

    /**
     * Estadísticas personalizadas
     */
    public function estadisticas(): array
    {
        return [
            'total' => $this->contar(),
            'activos' => $this->contar("estado = 'activo'"),
            'inactivos' => $this->contar("estado = 'inactivo'"),
            'valor_inventario' => $this->db->val("SELECT SUM(precio * stock) FROM {$this->tabla}") ?? 0,
        ];
    }

    /**
     * Buscar productos (búsqueda personalizada)
     */
    public function buscarProductos(string $termino): array
    {
        return $this->buscarTexto($termino, ['nombre', 'descripcion', 'categoria']);
    }
}
