<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reportes - Subastas más Vendidas por Categoría | BaggyCulture</title>
    <link rel="stylesheet" href="../../assets/css/subastas-admin.css">
    <link rel="stylesheet" href="../../assets/css/subastador.css">
    <link rel="stylesheet" href="../../assets/css/grafico_categorias.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="admin-body">

    <?php include __DIR__ . '/../../includes/navbar-admin.php'; ?>

    <main class="admin-container">
        <header class="section-header">
            <div>
                <h1>Dashboard de Categorías</h1>
                <p class="subtitle">Métricas y proporción de ingresos generados en subastas por categoría</p>
            </div>
            <a href="subastador.php" class="btn btn-secondary">ir a Subastas</a>
        </header>


        <div class="dashboard-grid">


        <div class="panel-grafico-contenedor">
            <div class="card-grafico">
                <div class="grafico-header">
                    <h2>Categorías Más Vendidas</h2>
                    <p>Proporción de ingresos generados por subasta</p>
                </div>
                
                <div class="canvas-wrapper">
                    <canvas id="chartCategoriasSubastas"></canvas>
                </div>

                <div id="sinDatosMensaje" class="mensaje-vacio" style="display: none;">
                    No hay subastas con ventas registradas aún.
                </div>
            </div>
        </div>




        <div class="panel-postores-container">
        <div class="card-postores">
            <div class="card-header">
                <h2>Participación de Postores</h2>
                <p>Top usuarios con más ofertas realizadas</p>
            </div>
            
            <div class="canvas-wrapper">
                <canvas id="chartTopPostores"></canvas>
            </div>

            <div id="sinPujasMsg" class="sin-datos" style="display: none;">
                Aún no hay ofertas registradas en el sistema.
            </div>
        </div>
    </div>


    <div class="card-grafico card-full-width">
        <div class="card-header">
            <h2>Evolución y Ritmo de Pujas</h2>
            <p>Progresión del valor de las ofertas a lo largo del tiempo</p>
        </div>
        <div class="canvas-wrapper canvas-linea">
            <canvas id="chartRitmoPujas"></canvas>
        </div>
        <div id="sinRitmoMsg" class="sin-datos" style="display: none;">
            No hay registros suficientes de ofertas para graficar una línea temporal.
        </div>
    </div>
</div>
    
    </main>

    <script src="../../assets/js/graficos-categorias.js"></script>
</body>
</html>