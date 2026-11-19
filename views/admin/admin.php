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
    <title>Panel Administrador Principal | BaggyCulture</title>
    <link rel="stylesheet" href="../../assets/css/navbar-admin-principal.css">
    <link rel="stylesheet" href="../../assets/css/subastador.css">
</head>
<body class="admin-body">

    <?php include __DIR__ . '/../../includes/navbar-admin-principal.php'; ?>

    <main class="admin-container">
        <header class="section-header">
            <div>
                <h1>Principal Dashboard</h1>
                <p class="subtitle">Panel de control y administración general de BaggyCulture</p>
            </div>
        </header>
    </main>

</body>
</html>
