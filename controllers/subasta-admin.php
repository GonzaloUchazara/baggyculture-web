<?php

require_once __DIR__ . '/../models/Subasta.php';

class SubastaControlador {
    public function index() {
        $modeloSubasta = new Subasta();
        $subastas = $modeloSubasta->obtenerTodas();

        
        require_once __DIR__ . '/../views/admin/subastas.php';
    }
}