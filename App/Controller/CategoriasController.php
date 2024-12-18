<?php 

namespace App\Controller;

use App\Controller\Component\AppComponent;
use App\Controller\Component\DataComponent;
use App\Controller\Component\HoldComponent;
use App\Controller\Component\FlashComponent;
use App\Model\Categorias;

class CategoriasController extends AppComponent {
    private $Categorias;
    public $Data;
    public $Hold;
    public $Flash;

    public function __construct()
    {
        $this->Categorias = new Categorias();
        $this->Data = new DataComponent();
        $this->Hold = new HoldComponent();
        $this->Flash = new FlashComponent();
    }

    public function cadastro() {
        $cmp = [
            'CAMPOS' => [
                ['LABEL' => 'Nome Categoria', 'type' => 'text', 'PLACEHOLDER' => 'Nome categoria', 'CMP' => 'nomeCategoria', 'OBG' => 'S', 'CLASS' => 'frm1'],
                ['LABEL' => 'Cadastre-se', 'type' => 'submit', 'NAME' => 'POST', 'CLASS' => 'frm1']
            ]
        ];

        $filtros = [];

        $this->setFrm($cmp, $filtros);

        if($this->isSubmit('POST')) {
            $formData = $this->getFormData($cmp);

            if(!empty($formData)) {
                $this->Hold->insert('UNIFOOD', 'INSERT', 'categorias', $formData);
                $this->redirect(['controller' => 'categorias', 'action' => 'visualizar']);
            } else {
                $this->redirect(['controller' => 'categorias', 'action' => 'visualizar']);
            }
        }
    }

    public function visualizar() {
        $this->Data->bdConnect();
        $strQuery = $this->Categorias->sqlVisualizar();
        $result = $this->Data->bdQueryFetchAll($strQuery);
    
        $cmps = [
            'TABELA' => [
                ['LABEL' => 'Id Categoria', 'CMP' => 'idcategoria'],
                ['LABEL' => 'Categoria', 'CMP' => 'nomecategoria'],
            ]
        ];
    
        $acoes = [
            ['controller' => 'categorias', 'action' => 'atualizar', 'cmp' => 'idcategoria', 'icon' => 'fa fa-pen'],
            ['controller' => 'categorias', 'action' => 'deletar', 'cmp' => 'idcategoria', 'icon' => 'fa fa-trash']
        ];
    
        $subMenu = [
            ['desc' => 'Cadastrar Categoria', 'controller' => 'categorias', 'action' => 'cadastro'],
            ['desc' => 'Cadastrar Usuários', 'controller' => 'usuarios', 'action' => 'cadastro']
        ];
    
        $this->setTable($cmps, $result, $subMenu, $acoes);
    }
    
    
    public function atualizar($idCategoria) {
        $valueCod = $idCategoria;
    
        if ($valueCod > 0) {
            $this->Data->bdConnect('UNIFOOD');
            
            $strQuery = $this->Categorias->sqlVisualizar(); 
            $strQuery .= " WHERE idCategoria = :idCategoria";
            $strBind = [':idCategoria' => $valueCod];
            $strResult = $this->Data->bdExecBind('UNIFOOD', $strQuery, $strBind);
    
            if ($strResult) {
                $codCategoria = $strResult;
    
                $cmps = [
                    'CAMPOS' => [
                        ['LABEL' => 'Nome: ', 'type' => 'text', 'PLACEHOLDER' => 'Digite o nome da categoria...', 'CMP' => 'nomecategoria', 'OBG' => 'S', 'VALUE' => trim($codCategoria['nomecategoria'])],
                        ['LABEL' => 'Atualizar', 'type' => 'submit', 'NAME' => 'POST']
                    ]
                ];
    
                $filtros = [];
    
                $this->setFrm($cmps, $filtros);
    
                if ($this->isSubmit('POST')) {
                    $formData = $this->getFormData($cmps);
    
                    if (!empty($formData)) {
                        $this->Hold->update('UNIFOOD', 'UPDATE', 'categorias', $formData, ['idCategoria' => $valueCod]);
                        return $this->redirect(['controller' => 'categorias', 'action' => 'visualizar']);
                    }
                }
            } else {
                echo "Categoria não encontrada!";
            }
        }
    }
    
    public function deletar($idCategoria) {
        $codCategoria = $idCategoria;
    
        if (!is_null($codCategoria) && $codCategoria > 0) {
            $this->Data->bdConnect('UNIFOOD');
    
            $strQuery = "DELETE FROM categorias WHERE idCategoria = :idCategoria";
            $strBind = [':idCategoria' => $codCategoria];
    
            try {
                $strResult = $this->Data->bdExecBind('UNIFOOD', $strQuery, $strBind);

                if ($strResult > 0) {
                    $this->redirect(['controller' => 'categorias', 'action' => 'visualizar']);
                } else {
                    $this->redirect(['controller' => 'categorias', 'action' => 'visualizar']);
                }
            } catch (\PDOException) {
                echo 'Erro na execução da consulta';
            }
        }
    }
    

}