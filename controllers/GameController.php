<?php
/**
 * Created by PhpStorm.
 * User: ubuntu
 * Date: 06/12/18
 * Time: 20:33
 */
require_once $_SERVER['DOCUMENT_ROOT']."/n2i/Controllers/DBConnect.php";
class GameController {
    public $bdd;
    public $pdo;
    public function __construct(){
        $this->bdd = new DBConnect("bddn2iGame");
        $this->pdo = $this->bdd->getPDO();
    }
    public function getView(){
        $pageContent = file_get_contents("view/game.html");

        $reqNbGame = $this->pdo->prepare("SELECT SUM(nbGame) FROM statistiques");
        $reqNbGame->execute();

        if($reqNbGame->rowCount() <= 0) {
            $nbSum = 0;
        }
        else {
            $data = $reqNbGame->fetchAll();
            $nbSum = $data[0][0];
        }

        $pageContent = str_replace("%nbGame%", $nbSum, $pageContent);
        return $pageContent;
    }

}