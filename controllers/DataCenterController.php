<?php
/**
 * Created by PhpStorm.
 * User: ubuntu
 * Date: 06/12/18
 * Time: 20:33
 */
class DataCenterController {
    public $bdd;
    public $pdo;
    public function __construct(){

    }
    public function getView(){
        $pageContent = file_get_contents("view/dataCenter.html");
        return $pageContent;
    }
}