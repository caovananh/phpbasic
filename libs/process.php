<?php
    $controller = isset($_GET['controller'])? $_GET['controller']: 'home';
    $action = isset($_GET['action'])? $_GET['action']:'index';

    $class_name = ucfirst($controller);
    $path = ROOT_PATH.'controllers/'.$controller.'.php';
    $flag =false;
    //check file exist
    if(file_exists($path)){
        require_once(ROOT_PATH.'controllers/'.$controller.'.php');
        if(class_exists($class_name)){
            $obj_controller = new $class_name;
            if(method_exists($obj_controller,$action)){
                $obj_controller->$action();
            }else{ $flag = true ;}
        }else{
            $flag = true ;
        }
    }else{ $flag = true ;}
    
    if($flag){
        require_once(ROOT_PATH.'controllers/error.php');
        $obj_controller = new Errors;
        $obj_controller->process();
    }
    ?>