<?php 

namespace App\Controller;
use App\Controller\Component\AppComponent;
use App\Controller\Component\DataComponent;
use App\Controller\Component\HoldComponent;
use App\Model\Usuarios;

class UsuariosController extends AppComponent {

    private $Usuarios;
    public $Data;
    public $Hold;

    public function __construct()
    {
        $this->Usuarios = new Usuarios();
        $this->Data = new DataComponent();
        $this->Hold = new HoldComponent();
    }

    public function cadastro() {

        $sexo = ['M' => 'Masculino', 'F' => 'Feminino'];

        $cmp = [
            'CAMPOS' => [
                ['LABEL' => 'Nome: ', 'type' => 'text', 'PLACEHOLDER' => 'Digite seu nome...', 'CMP' => 'nomeUser', 'OBG' => 'S'],
                ['LABEL' => 'CPF: ', 'type' => 'text', 'PLACEHOLDER' => '000.000.000-00', 'CMP' => 'cpf', 'OBG' => 'S'],
                ['LABEL' => 'Sexo: ', 'type' => 'select', 'options' => $sexo, 'CMP' => 'sexo', 'OBG' => 'S'],
                ['LABEL' => 'Cadastrar', 'type' => 'submit', 'NAME' => 'POST']    
            ]
        ];

        $filtros = [];

        $this->setFrm($cmp, $filtros);

        if($this->isSubmit('POST')) {
            $formData = $this->getFormData($cmp);

            if(!empty($formData)) {
                $this->Hold->insert('UniFood', 'INSERT', 'usuarios', $formData);
            } else {
                throw new \InvalidArgumentException('Não foi possível se cadastrar!');
            }
        } 
    }

    public function visualizar() {
        $this->Data->bdConnect();
        $strQuery = $this->Usuarios->sqlUsuarios();
        $result = $this->Data->bdQueryFetchAll($strQuery);

        $subMenu = [];

        $cmps = [
            'TABELA' => [
                ['LABEL' => 'Nome', 'CMP' => 'nomeuser'],
                ['LABEL' => 'CPF', 'CMP' => 'cpf', 'MASK' => 'CPF'],
                ['LABEL' => 'Sexo', 'CMP' => 'sexo'],
            ]
        ];

        $this->setTable($cmps, $result, $subMenu);
    }
}