<?php
/**
 * Created by PhpStorm.
 * User: ubuntu
 * Date: 06/12/18
 * Time: 20:33
 */
require_once $_SERVER['DOCUMENT_ROOT']."/n2i/Controllers/DBConnect.php";
class ApiController {
    public $bdd;
    public $pdo;
    public function __construct(){
        $this->bdd = new DBConnect("bddn2iGame");
        $this->pdo = $this->bdd->getPDO();
    }

    public function saveScore(){
        if(!isset($_GET['pseudo'])){
            echo "need_pseudo";
            return;
        }

        if(!isset($_GET['score'])){
            echo "need_score";
            return;
        }

        $pseudo = $_GET['pseudo'];
        $score = $_GET['score'];

        $reqScore = $this->pdo->prepare("SELECT score, nbGame FROM statistiques WHERE pseudo=:pseudo");
        $reqScore->execute(array(
            "pseudo" => $pseudo
        ));

        if($reqScore->rowCount() <= 0){
            $reqScoreCreate = $this->pdo->prepare("INSERT INTO statistiques(pseudo, score, nbGame) VALUES (:pseudo, :score, :nbGame)");
            $reqScoreCreate->execute(array(
                "pseudo" => $pseudo,
                "score" => $score,
                "nbGame" => 1
            ));
            echo "success";
            return;
        }

        $data = $reqScore->fetchAll();

        $newScore = $data[0]['score'] + $score;
        var_dump($newScore);
        var_dump($pseudo);
        $reqScoreUpdate = $this->pdo->prepare("UPDATE statistiques SET score=:score, nbGame=:nbGame WHERE pseudo=:pseudo");
        $reqScoreUpdate->execute(array(
            "pseudo" => $pseudo,
            "score" => $newScore,
            "nbGame" => ++$data[0]['nbGame']
        ));
        echo "success";
        return;
    }

}