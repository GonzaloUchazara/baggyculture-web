<?php
require_once __DIR__ . '/../config/conexion.php';
require_once __DIR__ . '/../models/subasta.php';

class subastacontroler{
    private $modelo;
    public function __construct() {
        global $pdo;
        $this->modelo = new subasta($pdo);
    }

    public function index(){
        $subastas = $this->modelo->obtenersubastasactivas();
        require_once __DIR__ . '/../views/subastas.php';
    }

    public function detalle($id){
        $id = filter_var($id, FILTER_VALIDATE_INT);
        if (!$id){

            header('Location: index.php?c=subasta&a=index');
            exit;
        }


        $subasta = $this->modelo->obtenerPorId($id);
        if (!$subasta) {
            echo "Subasta no encontrada.";
            return;
        }

        $historial = $this->modelo->obtenerHistorialPujas($id);
        require_once __DIR__ . '/../views/detalles.php';
    }

    public function pujar() {
        header('Content-Type: application/json');

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Validar que el usuario haya iniciado sesión
        if (!isset($_SESSION['id_usuario'])) {
            echo json_encode([
                'success' => false,
                'mensaje' => 'Debes iniciar sesión para poder realizar una oferta.'
            ]);
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'mensaje' => 'Método no permitido.']);
            exit;
        }

        $id_subasta = filter_input(INPUT_POST, 'id_subasta', FILTER_VALIDATE_INT);
        $monto = filter_input(INPUT_POST, 'monto', FILTER_VALIDATE_FLOAT);
        $id_usuario = $_SESSION['id_usuario'];

        if (!$id_subasta || !$monto) {
            echo json_encode([
                'success' => false,
                'mensaje' => 'Datos de oferta inválidos.'
            ]);
            exit;
        }

        $resultado = $this->modelo->registrarPuja($id_subasta, $id_usuario, $monto);
        echo json_encode($resultado);
        exit;
    }




    // 4. Endpoint AJAX para consultar precio actual y tiempo en vivo
    public function obtenerEstadoVivo() {
        header('Content-Type: application/json');
        $id_subasta = filter_input(INPUT_GET, 'id_subasta', FILTER_VALIDATE_INT);

        if (!$id_subasta) {
            echo json_encode(['success' => false]);
            exit;
        }

        $subasta = $this->modelo->obtenerPorId($id_subasta);
        if (!$subasta) {
            echo json_encode(['success' => false]);
            exit;
        }

        $minimo = $subasta['precio_actual'] + $subasta['incremento_minimo'];

        echo json_encode([
            'success' => true,
            'precio_actual' => number_format($subasta['precio_actual'], 2, '.', ''),
            'minimo_siguiente' => number_format($minimo, 2, '.', ''),
            'estado' => $subasta['estado'],
            'fecha_fin' => $subasta['fecha_fin'],
            'nombre_ganador' => $subasta['nombre_ganador'] ?? 'Aún sin ofertas'
        ]);
        exit;
    }
}

// Enrutador directo si se llama directamente al archivo o por query params
if (isset($_GET['accion'])) {
    $controlador = new subastacontroler();
    $accion = $_GET['accion'];

    if ($accion === 'pujar') {
        $controlador->pujar();
    } elseif ($accion === 'estado') {
        $controlador->obtenerEstadoVivo();
    }
}