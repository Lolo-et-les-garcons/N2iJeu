<?php
/**
 * Created by PhpStorm.
 * User: ubuntu
 * Date: 06/12/18
 * Time: 20:33
 */

class AboutController{
    public function __construct(){
    }

    public function getView(){
        $page = file_get_contents("view/about.html");
        return $page;
    }

}