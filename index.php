<?php
/**
 * Created by PhpStorm.
 * User: ubuntu
 * Date: 06/12/18
 * Time: 20:00
 */

if(isset($_GET["page"]) && ($_GET['page'] == "dataCenter"))
    require_once "./includes/header2.php";
else
    require_once "./includes/header.php";

require_once "./includes/navbar.php";
require_once "controllers/all.php";

$controllerTab = array(
    "Game" => new GameController(),
    "About" => new AboutController(),
    "Classement" => new ClassementController(),
    "Storage" => new StorageController(),
    "StorageEditor" => new StorageEditorController(),
    "dataCenter" => new DataCenterController()
);

if(!isset($_GET["page"])){
    $controller = $controllerTab["Game"];
}
else{
    if(isset($_GET['idItem'])) {
        $controller = $controllerTab["StorageEditor"];
        $controller->setIdItem($_GET['idItem']);
    }
    else
        $controller = $controllerTab[$_GET["page"]];
    if($controller == null)
        $controller = $controllerTab["Game"];
}

echo $controller->getView();

//init home controlle

if(isset($_GET["page"]) && ($_GET['page'] == "dataCenter"))
    require_once "./includes/footer2.php";
else
    require_once "./includes/footer.php";

?>