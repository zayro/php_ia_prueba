<?php
/**
 * API - Maneja peticiones REST automáticamente
 * Una sola clase para todas las operaciones CRUD
 */

class Api
{
    private Modelo $modelo;
    private string $nombre;

    public function __construct(Modelo $modelo, string $nombre)
    {
        $this->modelo = $modelo;
        $this->nombre = $nombre;

        // Soporte para peticiones JSON (Postman, curl, etc.)
        $contentType = $_SERVER['CONTENT_TYPE'] ?? $_SERVER['HTTP_CONTENT_TYPE'] ?? '';
        if (str_contains(strtolower($contentType), 'application/json')) {
            $input = file_get_contents('php://input');
            $data = json_decode($input, true);
            if (is_array($data)) {
                $_POST = array_merge($_POST, $data);
            }
        }
    }

    /**
     * Ejecutar acción basada en request
     */
    public function ejecutar(): void
    {
        $accion = $_GET['action'] ?? $_POST['action'] ?? 'list';

        $acciones = [
            'list'   => fn() => $this->listar(),
            'get'    => fn() => $this->obtener(),
            'create' => fn() => $this->crear(),
            'update' => fn() => $this->actualizar(),
            'delete' => fn() => $this->eliminar(),
        ];

        if (isset($acciones[$accion])) {
            $acciones[$accion]();
        } else {
            $this->json(['success' => false, 'message' => 'Acción inválida'], 400);
        }
    }

    /**
     * Listar con DataTables
     */
    private function listar(): void
    {
        $result = $this->modelo->paginar($_GET);
        $this->json($result);
    }

    /**
     * Obtener uno
     */
    private function obtener(): void
    {
        $id = (int) ($_GET['id'] ?? 0);
        $item = $this->modelo->buscar($id);

        if (!$item) {
            $this->json(['success' => false, 'message' => "{$this->nombre} no encontrado"], 404);
            return;
        }

        $this->json(['success' => true, 'data' => $item]);
    }

    /**
     * Crear
     */
    private function crear(): void
    {
        $data = $_POST;
        $errores = $this->modelo->validar($data);

        if (!empty($errores)) {
            $this->json(['success' => false, 'errors' => $errores], 422);
            return;
        }

        try {
            $id = $this->modelo->crear($data);
            $this->json([
                'success' => true,
                'message' => "{$this->nombre} creado",
                'id' => $id
            ], 201);
        } catch (\Exception $e) {
            $this->json(['success' => false, 'message' => 'Error al crear'], 500);
        }
    }

    /**
     * Actualizar
     */
    private function actualizar(): void
    {
        $id = (int) ($_GET['id'] ?? 0);

        if (!$this->modelo->existe($id)) {
            $this->json(['success' => false, 'message' => "{$this->nombre} no encontrado"], 404);
            return;
        }

        $data = $_POST;
        $errores = $this->modelo->validar($data);

        if (!empty($errores)) {
            $this->json(['success' => false, 'errors' => $errores], 422);
            return;
        }

        try {
            $this->modelo->actualizar($id, $data);
            $this->json(['success' => true, 'message' => "{$this->nombre} actualizado"]);
        } catch (\Exception $e) {
            $this->json(['success' => false, 'message' => 'Error al actualizar'], 500);
        }
    }

    /**
     * Eliminar
     */
    private function eliminar(): void
    {
        $id = (int) ($_GET['id'] ?? $_POST['id'] ?? 0);

        if (!$this->modelo->existe($id)) {
            $this->json(['success' => false, 'message' => "{$this->nombre} no encontrado"], 404);
            return;
        }

        try {
            $this->modelo->eliminar($id);
            $this->json(['success' => true, 'message' => "{$this->nombre} eliminado"]);
        } catch (\Exception $e) {
            $this->json(['success' => false, 'message' => 'Error al eliminar'], 500);
        }
    }

    /**
     * Respuesta JSON
     */
    private function json(array $data, int $code = 200): void
    {
        http_response_code($code);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit;
    }
}
