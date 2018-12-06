<?php
/**
 * Created by PhpStorm.
 * User: ubuntu
 * Date: 06/12/18
 * Time: 20:33
 */

class ClassementController {
    public function __construct(){
    }

    public function getView(){
        $page = $templateProjEtude = file_get_contents("view/game.html");
        return $page;
    }

}