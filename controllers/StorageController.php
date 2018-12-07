<?php
/**
 * Created by PhpStorm.
 * User: ubuntu
 * Date: 06/12/18
 * Time: 20:33
 */
require_once $_SERVER['DOCUMENT_ROOT']."/n2i/Controllers/DBConnect.php";
class StorageController {
    public $bdd;
    public $pdo;
    public function __construct(){
        $this->bdd = new DBConnect("bddn2iGame");
        $this->pdo = $this->bdd->getPDO();
    }
    public function getView(){
        $pageContent = file_get_contents("view/storage.html");

        $reqNbGame = $this->pdo->prepare("SELECT * FROM storage");
        $reqNbGame->execute();

        if($reqNbGame->rowCount() <= 0) {
            $pageContent = str_replace("%tableContent%", "", $pageContent);
            return $pageContent;
        }

        $tableContent = "";
        while($data = $reqNbGame->fetch()){
            $templateRow = file_get_contents("view/row_storage.html");
            $templateRow = str_replace("%id%", $data['id'], $templateRow);
            $templateRow = str_replace("%name%", $data['name'], $templateRow);
            $templateRow = str_replace("%type%", $data['typeRef'], $templateRow);
            $templateRow = str_replace("%quantity%", $data['quantity'], $templateRow);
            $tableContent.=$templateRow;
        }

        $pageContent = str_replace("%tableContent%", $tableContent, $pageContent);
        return $pageContent;}
}