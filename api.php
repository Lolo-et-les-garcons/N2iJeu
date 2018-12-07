<?php
/**
 * Created by PhpStorm.
 * User: ubuntu
 * Date: 06/12/18
 * Time: 23:52
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once "./controllers/ApiController.php";

if(!isset($_GET['action'])){
    echo "error_no_action";
    return;
}

$apiController = new ApiController();
$action = $_GET['action'];
switch ($action){
    case "saveScore" :
        $apiController->saveScore();
    break;
    default :
        echo "error_action";
        return;
    break;
}

