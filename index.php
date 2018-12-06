<?php
/**
 * Created by PhpStorm.
 * User: ubuntu
 * Date: 06/12/18
 * Time: 20:00
 */

require_once "./includes/header.php";
require_once "./includes/navbar.php";
require_once "controllers/all.php";

$controllerTab = array(
    "Game" => new GameController(),
    "About" => new AboutController(),
    "Classement" => new ClassementController()
);

if(isset($_GET["redirect"])){
    //todo redirect
}
if(isset($_GET["page"])){
    $controller = $controllerTab["Game"];
}
else{
    $controller = $controllerTab[$_GET["page"]];
    if($controller == null)
        $controller = $controllerTab["Game"];
}

echo $controller->getView();

//init home controlle

require_once "./includes/footer.php";

?>