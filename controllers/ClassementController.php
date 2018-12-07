<?php
/**
 * Created by PhpStorm.
 * User: ubuntu
 * Date: 06/12/18
 * Time: 20:33
 */
require_once $_SERVER['DOCUMENT_ROOT']."/n2i/Controllers/DBConnect.php";
class ClassementController {
    public $bdd;
    public $pdo;
    public function __construct(){
        $this->bdd = new DBConnect("bddn2iGame");
        $this->pdo = $this->bdd->getPDO();
    }

    public function getView(){
        $pageClassement = file_get_contents("view/classement.html");

        $reqClassement = $this->pdo->prepare("SELECT * FROM statistiques ORDER BY score DESC, pseudo ASC");
        $reqClassement->execute();

        $i=0;
        $tableContent="";
        while($data = $reqClassement->fetch()){
            $templateRow = file_get_contents("view/row_classement.html");
            $templateRow = str_replace("%pos%",++$i, $templateRow);
            $templateRow = str_replace("%pseudo%",$data["pseudo"], $templateRow);
            $templateRow = str_replace("%score%",$data["score"], $templateRow);
            $templateRow = str_replace("%nbGame%",$data["nbGame"], $templateRow);

            if($i==1)
                $templateRow = str_replace("%class%","gold", $templateRow);
            if($i==2)
                $templateRow = str_replace("%class%","silver", $templateRow);
            if($i==3)
                $templateRow = str_replace("%class%","bronze", $templateRow);
            $tableContent.=$templateRow;
        }

        $pageClassement = str_replace("%tableContent%", $tableContent, $pageClassement);
        return $pageClassement;
    }

}