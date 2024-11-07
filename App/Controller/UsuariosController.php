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
                $this->Hold->insert('UNIFOOD', 'INSERT', 'usuarios', $formData);
                return $this->redirect(['controller' => 'usuarios', 'action' => 'visualizar']);
            } else {
                throw new \InvalidArgumentException('Não foi possível se cadastrar!');
            }
        } 
    }

    public function visualizar() {
        $this->Data->bdConnect();
        $strQuery = $this->Usuarios->sqlUsuarios();
        $result = $this->Data->bdQueryFetchAll($strQuery);

        $subMenu = [
            ['desc' => 'Cadastrar Usuário', 'controller' => 'usuarios', 'action' => 'cadastro'],
            ['desc' => 'Cadastrar Categoria', 'controller' => 'categorias', 'action' => 'cadastro'],
            ['desc' => 'Listar Categorias', 'controller' => 'categorias', 'action' => 'visualizar']
        ];

        $cmps = [
            'TABELA' => [
                ['LABEL' => 'Nome', 'CMP' => 'nomeuser'],
                ['LABEL' => 'CPF', 'CMP' => 'cpf', 'MASK' => 'CPF'],
                ['LABEL' => 'Sexo', 'CMP' => 'sexo'],
            ]
        ];

        $acoes = [
            ['controller' => 'usuarios', 'action' => 'atualizar', 'cmp' => 'idusuario', 'icon' => 'fa fa-pen'],
            ['controller' => 'usuarios', 'action' => 'deletar', 'cmp' => 'idusuario', 'icon' => 'fa fa-trash']
        ];

        $this->setTable($cmps, $result, $subMenu, $acoes);
    }

    public function atualizar($idUsuario) {
        $codUser = $idUsuario;
        $this->Data->bdConnect();

        $strQuery = $this->Usuarios->sqlUsuarios();
        $strQuery .= " WHERE idUsuario = :idUsuario ";
        $strBind = [':idUsuario' => $codUser];
        $item = $this->Data->bdExecBind('UNIFOOD', $strQuery, $strBind);

        $sexo = ['M' => 'Masculino', 'F' => 'Feminino'];

        if(isset($item)) {
            $itens = $item;

            $filtros = [];

            $cmps = [
                'CAMPOS' => [
                    ['LABEL' => 'Nome ', 'type' => 'text', 'PLACEHOLDER' => 'Atualizar nome...', 'CMP' => 'nomeuser', 'VALUE' => trim($itens['nomeuser'])],
                    ['LABEL' => 'CPF ', 'type' => 'text', 'CMP' => 'cpf', 'VALUE' => trim($itens['cpf'])],
                    ['LABEL' => 'Sexo ', 'type' => 'select', 'options' => $sexo, 'CMP' => 'sexo', 'VALUE' => trim($itens['sexo'])],
                    ['LABEL' => 'Atualizar', 'type' => 'submit', 'NAME' => 'POST']
                ]
            ];

            $this->setFrm($cmps, $filtros);

            if($this->isSubmit('POST')) {
                $formData = $this->getFormData($cmps);

                if(!empty($formData)) {
                    $this->Hold->update('UNIFOOD', 'UPDATE', 'usuarios', $formData, ['idUsuario' => $codUser]);
                    return $this->redirect(['controller' => 'usuarios', 'action' => 'visualizar']);
                }
            }
        }
    }

    public function deletar($idUsuario) {
        $codUser = $idUsuario;

        if(!is_null($codUser) || !empty($codUser)) {
            $this->Data->bdConnect();

            $strQuery = " DELETE FROM usuarios WHERE idUsuario = :idUsuario ";
            $strBind = [':idUsuario' => $codUser];

            try {
                $item = $this->Data->bdExecBind('UNIFOOD', $strQuery, $strBind);

                if(isset($item)) {
                    $this->redirect(['controller' => 'usuarios', 'action' => 'visualizar']);
                } else {
                    $this->redirect(['controller' => 'usuarios', 'action' => 'visualizar']);
                }
            } catch (\PDOException) {
                echo 'Erro na exclusão!';
            }
        }
    }

}