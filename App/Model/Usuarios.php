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
        $strQuery = " SELECT
                        idUsuario,
                         nomeuser,
                         cpf,
                         CASE sexo 
                                WHEN 'M' THEN 'Masculino'
                                WHEN 'F' THEN 'Feminino'
                        END sexo
                        FROM usuarios ";
        return $strQuery;
    }
}
