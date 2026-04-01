<?php
session_start();

require_once "config/database.php";

// Autoload controllers
spl_autoload_register(function($class_name) {
    $file = "controllers/" . $class_name . ".php";
    if (file_exists($file)) {
        require_once $file;
    }
});

// Autoload models
spl_autoload_register(function($class_name) {
    $file = "models/" . $class_name . ".php";
    if (file_exists($file)) {
        require_once $file;
    }
});

$db = (new Database())->getConnection();

$controller = $_GET['controller'] ?? 'auth';
$action = $_GET['action'] ?? 'login';

$controllerClass = ucfirst($controller) . 'Controller';

if (class_exists($controllerClass)) {
    $controllerObj = new $controllerClass($db);
    if (method_exists($controllerObj, $action)) {
        $controllerObj->$action();
    } else {
        die("Action not found");
    }
} else {
    die("Controller not found");
}
?>