<?php
    namespace Controllers;
    use Lib\Pages;

    class ErrorController {

        private Pages $pages;

        function __construct(){
            $this->pages = new Pages();
        }

        public function error404(): void {
            $this->pages->render('error/error', ['título' => 'Página no encontrada']);
        }
        
    }

?>