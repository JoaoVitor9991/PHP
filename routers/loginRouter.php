<?php
    require_once __DIR__ . '/../controller/LoginController.php';

    $loginController = new LoginController();

    if ($_SERVER ['REQUEST_METHOD'] === 'POST'){
        switch ($_GET['acao']){
            case 'validarlogin';
            $output = $loginController->
        }
    }


?>