<?php
/**
 * Created by PhpStorm.
 * User: ubuntu
 * Date: 06/12/18
 * Time: 20:33
 */
require_once $_SERVER['DOCUMENT_ROOT']."/n2i/Controllers/DBConnect.php";
class StorageEditorController
{
    public $bdd;
    public $pdo;
    protected $idItem;

    public function __construct()
    {
        $this->bdd = new DBConnect("bddn2iGame");
        $this->pdo = $this->bdd->getPDO();
    }

    public function setIdItem($id) {
        $this->idItem = $id;
    }

    public function getView()
    {
        $pageContent = file_get_contents("view/editor.html");
        $reqOption = $this->pdo->prepare("SELECT * FROM TypeItem");
        $reqOption->execute();
        $contentOptionList = "";
        while($data = $reqOption->fetch()){
            $templateOption = file_get_contents("view/row_option.html");
            $templateOption = str_replace("%value%", $data['name'], $templateOption);
            $templateOption = str_replace("%id%", $data['id'], $templateOption);
            $contentOptionList.=$templateOption;
        }
        $pageContent = str_replace("%optionList%", $contentOptionList, $pageContent);

        if($this->idItem != null){
            $reqElem = $this->pdo->prepare("SELECT id, name, quantity, typeRef FROM storage WHERE storage.id=:id");
            $reqElem->execute(array(
            "id" => $this->idItem
            ));

            if ($reqElem->rowCount() <= 0) {
            echo "no data";
            return;
            }

            $elem = $reqElem->fetch();
            $pageContent = str_replace("%id%", $elem['id'], $pageContent);
            $pageContent = str_replace("%name%", $elem['name'], $pageContent);
            $pageContent = str_replace("%quantity%", $elem['quantity'], $pageContent);
            $pageContent = str_replace("%NomBtn%", "Modifier", $pageContent);
            $script =
                "<script>
                    document.addEventListener('DOMContentLoaded', function() {
                       document.getElementById('type').value = ".$elem['typeRef'].";
                    });</script>";

            $pageContent.=$script;
        }
        else{
            $pageContent = str_replace("%id%", "", $pageContent);
            $pageContent = str_replace("%name%", "", $pageContent);
            $pageContent = str_replace("%type%", "", $pageContent);
            $pageContent = str_replace("%quantity%", "", $pageContent);
            $pageContent = str_replace("%NomBtn%", "Créer", $pageContent);
        }


        return $pageContent;
    }
}