<?php

namespace Controllers;
use Lib\Pages;
use Models\Ponente;

class DashboardController{
    
    public function peticiones() {
        $pages = new Pages();
        $pages->render('dashboard/peticionesApi');
    }
}