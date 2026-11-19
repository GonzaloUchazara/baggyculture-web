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

    public function toggleEstado() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_subasta = filter_input(INPUT_POST, 'id_subasta', FILTER_VALIDATE_INT);
            $nuevo_estado = $_POST['nuevo_estado'] ?? 'activa';

            if ($id_subasta && in_array($nuevo_estado, ['activa', 'finalizada', 'cancelada'])) {
                $this->modelo->cambiarEstado($id_subasta, $nuevo_estado);
            }
        }
        header('Location: ../views/admin/subastador.php');
        exit;
    }

    public function extenderTiempo() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_subasta = filter_input(INPUT_POST, 'id_subasta', FILTER_VALIDATE_INT);
            $minutos_extra = filter_input(INPUT_POST, 'minutos_extra', FILTER_VALIDATE_INT);

            if ($id_subasta && $minutos_extra > 0) {
                $this->modelo->extenderTiempoMinutos($id_subasta, $minutos_extra);
            }
        }
        header('Location: ../views/admin/subastador.php');
        exit;
    }

    public function editarSubasta() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_subasta = filter_input(INPUT_POST, 'id_subasta', FILTER_VALIDATE_INT);
            $precio_base = filter_input(INPUT_POST, 'precio_base', FILTER_VALIDATE_FLOAT);
            $fecha_inicio_raw = $_POST['fecha_inicio'] ?? '';
            $fecha_fin_raw = $_POST['fecha_fin'] ?? '';
            $estado = $_POST['estado'] ?? 'activa';
            $producto_nombre = trim($_POST['producto_nombre'] ?? '');

            if ($id_subasta && $precio_base !== false && !empty($fecha_inicio_raw) && !empty($fecha_fin_raw)) {
                $fecha_inicio = date('Y-m-d H:i:s', strtotime($fecha_inicio_raw));
                $fecha_fin = date('Y-m-d H:i:s', strtotime($fecha_fin_raw));

                $this->modelo->actualizarSubastaAdmin($id_subasta, $precio_base, $fecha_inicio, $fecha_fin, $estado, $producto_nombre);
            }
        }
        header('Location: ../views/admin/subastador.php');
        exit;
    }

    public function obtenerDatosCategoriasApi() {
        header('Content-Type: application/json');
        $datos = $this->modelo->obtenerVentasPorCategoria();

        $categorias = array_column($datos, 'categoria');
        $totales = array_map('floatval', array_column($datos, 'total_recaudado'));
        $cantidades = array_map('intval', array_column($datos, 'total_vendidas'));

        echo json_encode([
            'categorias' => $categorias,
            'totales'    => $totales,
            'cantidades' => $cantidades
        ]);
        exit;
    }


    public function obtenerTopPostoresApi() {
        header('Content-Type: application/json');
        $datos = $this->modelo->obtenerTopPostores(5);

        $nombres = array_column($datos, 'nombre');
        $ofertas = array_map('intval', array_column($datos, 'total_ofertas'));
        $volumen = array_map('floatval', array_column($datos, 'volumen_total'));

        echo json_encode([
            'usuarios' => $nombres,
            'ofertas'  => $ofertas,
            'volumen'  => $volumen
        ]);
        exit;
    }

    public function obtenerRitmoPujasApi() {
        header('Content-Type: application/json');
        $id_subasta = filter_input(INPUT_GET, 'id_subasta', FILTER_VALIDATE_INT) ?: null;
        $datos = $this->modelo->obtenerRitmoPujasTiempo($id_subasta);

        $tiempos = array_column($datos, 'punto_tiempo');
        $montos = array_map('floatval', array_column($datos, 'monto'));

        echo json_encode([
            'tiempos' => $tiempos,
            'montos'  => $montos
        ]);
        exit;
    }
}


$accion = $_POST['accion'] ?? $_GET['accion'] ?? null;

if ($accion) {
    $controlador = new subastacontroler();

    if ($accion === 'pujar') {
        $controlador->pujar();
    } elseif ($accion === 'estado') {
        $controlador->obtenerEstadoVivo();
    } elseif ($accion === 'toggle_estado' || $accion === 'toggle-estado') {
        $controlador->toggleEstado();
    } elseif ($accion === 'extender') {
        $controlador->extenderTiempo();
    } elseif ($accion === 'editar' || $accion === 'actualizar') {
        $controlador->editarSubasta();
    } elseif ($accion === 'datos_categorias') {
        $controlador->obtenerDatosCategoriasApi();
    } elseif ($accion === 'top_postores') {
        $controlador->obtenerTopPostoresApi();
    } elseif ($accion === 'ritmo_pujas') {
        $controlador->obtenerRitmoPujasApi();
    }
}


