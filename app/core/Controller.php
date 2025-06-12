<?php

class Controller
{
    protected function view($view, $data = [])
    {
        $headerPath = __DIR__ . '/../views/templates/admin/header.php';
        $viewPath = __DIR__ . '/../views/' . $view . '.php';
        $footerPath = __DIR__ . '/../views/templates/admin/footer.php';

        $headerHomePath = __DIR__ . '/../views/templates/header.php';
        $footerHomePath = __DIR__ . '/../views/templates/footer.php';


        if (file_exists($viewPath)) {
            extract($data);

            if (str_contains($view, "admin")) {
                if ($view != "admin/login/index") {
                    require_once $headerPath;
                }
            } else {
                if ($view != "login/index") {
                    require_once $headerHomePath;
                }
            }

            require_once $viewPath;

            if (str_contains($view, "admin")) {
                if ($view != "admin/login/index") {
                    require_once $footerPath;
                }
            } else {
                if ($view != "login/index") {
                    require_once $footerHomePath;
                }
            }
        } else {
            die("La vista '$view' no existe.");
        }
    }

    protected function model($model)
    {
        $modelPath = __DIR__ . '/../models/' . $model . '.php';

        if (file_exists($modelPath)) {
            require_once $modelPath;
            return new $model();
        } else {
            die("El modelo '$model' no existe.");
        }
    }
    
    protected function print($view, $data = [])
    {
        $viewPath = __DIR__ . '/../views/' . $view . '.php';

        if (file_exists($viewPath)) {
            extract($data);
            require_once $viewPath;
        } else {
            die("La vista '$view' no existe.");
        }
    }
}
