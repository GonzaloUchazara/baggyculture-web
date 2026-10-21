# BaggyCulture - Sistema de Gestión Web para Tienda de Ropa Urbana

## Descripción del Proyecto

BaggyCulture es una plataforma web integral diseñada para digitalizar y optimizar la gestión comercial de la tienda de ropa urbana BaggyCulture (La Paz, Bolivia).

El sistema centraliza las operaciones de venta, control de inventarios en tiempo real, gestión de cotizaciones personalizadas, módulos de subastas de prendas exclusivas, probador virtual mediante Inteligencia Artificial y seguimiento de envíos en tiempo real.

---

## Características Principales

- Autenticación y Control de Accesos: Gestión de usuarios con roles diferenciados (Cliente, Asesor de Ventas, Administrador) y credenciales encriptadas.
- Catálogo Digital Interactivo: Muestra de productos con filtros dinámicos por categoría, precios en Bolivianos (Bs.) y control automático de stock.
- Carrito y Procesamiento de Pedidos: Selección de prendas, cálculo automático de subtotales, totales y descuentos por volumen.
- Módulo de Cotizaciones y Asesores: Formulario de solicitudes con asignación automática a asesores e integración directa con WhatsApp API.
- Gestión de Envíos y Tracking: Asignación de guías de despacho, control de estados (Pendiente, Confirmado, Enviado, Entregado) y seguimiento en tiempo real.
- Módulo de Subastas en Vivo: Sistema de pujas para colecciones limitadas con temporizadores en tiempo real.
- Probador Virtual con IA (Virtual Try-On): Visualización interactiva de prendas sobre fotografías de los usuarios mediante procesamiento de imágenes.
- Panel Administrativo & Dashboard: Estadísticas clave, reportes periódicos de ventas, clientes e inventario.

---

## Tecnologías Utilizadas

- Backend: PHP 8.x
- Base de Datos: MySQL / MariaDB (vía XAMPP)
- Frontend: HTML5, CSS3, JavaScript (Fetch API / AJAX)
- Servidor Local: Apache / XAMPP

---

## Plan de Desarrollo y Sprints (SCRUM)

El proyecto se desarrolla bajo la metodología ágil SCRUM, dividiéndose en 8 Sprints (~112 días laborables / 16 semanas):

| Sprint | Módulo / Enfoque | Duración Estimada |
| :---: | :--- | :---: |
| Sprint 1 | Autenticación, Usuarios y Seguridad | 18 días |
| Sprint 2 | Catálogo Digital y Filtros en Tiempo Real | 15 días |
| Sprint 3 | Carrito de Compras y Pasarela de Pagos | 14 días |
| Sprint 4 | Módulo de Envíos, Tracking y Logística Extendido | 16 días |
| Sprint 5 | Cotizaciones y Atención por Asesores | 11 días |
| Sprint 6 | Subastas y Pujas en Vivo | 13 días |
| Sprint 7 | Probador Virtual de Ropa con IA (Virtual Try-On) | 14 días |
| Sprint 8 | Dashboard Admin, Reportes y Despliegue en Producción | 11 días |

---

## Estructura del Repositorio

```text
baggyculture/
├── assets/            # Imágenes, estilos CSS y scripts JS
├── conexion.php       # Archivo de conexión a la Base de Datos MySQL
├── index.php          # Página principal de la tienda
├── baggyinicio.php    # Dashboard / Inicio del sistema
├── hombres.php        # Catálogo de prendas para varones
├── asesores.php       # Panel y gestión de asesores de ventas
├── subastas.php       # Módulo interactivo de subastas en vivo
└── README.md          # Documentación del repositorio