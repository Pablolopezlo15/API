<?php

namespace Controllers;
use Lib\Pages;
use Models\Ponente;

class DashboardController{
    
    public function index() {
        $pages = new Pages();
        $pages->render('usuario/login');
    }

    public function peticiones() {
        $pages = new Pages();
        $pages->render('dashboard/peticionesApi');
    }
}