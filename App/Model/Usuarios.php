<?php 

namespace App\Model;
use App\Controller\Component\DataComponent;

class Usuarios {
    public $Data;

    public function __construct()
    {
        $this->Data = new DataComponent();
        $this->Data->bdConnect('UNIFOOD');
    }

    public function sqlUsuarios() {
        $strQuery = " SELECT * FROM usuarios ";
        return $strQuery;
    }
}
