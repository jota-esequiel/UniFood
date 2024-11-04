<?php 

//Wesley Rodrigues
//Eduardo Silva

namespace App\Controller;

use App\Controller\Component\AppComponent;
use App\Controller\Component\DataComponent;
use App\Controller\Component\HoldComponent;
use App\Model\Categorias;

class CategoriasController extends AppComponent {
    private $Categorias;
    public $Data;
    public $Hold;

    public function __construct()
    {
        $this->Categorias = new Categorias();
        $this->Data = new DataComponent();
        $this->Hold = new HoldComponent();
    }

    public function cadastro() {
        $cmp = [
            'CAMPOS' => [
                ['LABEL' => 'Nome Categoria', 'type' => 'text', 'PLACEHOLDER' => 'Nome categoria', 'CMP' => 'nomeCategoria', 'OBG' => 'S', 'CLASS' => 'frm1'],
                ['LABEL' => 'Cadastre-se', 'type' => 'submit', 'NAME' => 'POST']
            ]
        ];

        $filtros = [];

        $this->setFrm($cmp, $filtros);

        if($this->isSubmit('POST')) {
            $formData = $this->getFormData($cmp);

            if(!empty($formData)) {
                $this->Hold->insert('UNIFOOD', 'INSERT', 'categorias', $formData);
            } else {
                throw new \InvalidArgumentException('Não foi possível cadastrar à categoria!');
            }
        }
    }

    public function visualizar() {
        $this->Data->bdConnect();
        $strQuery = $this->Categorias->sqlVisualizar();
        $result = $this->Data->bdQueryFetchAll($strQuery);
    
        $subMenu = [];
    
        $cmps = [
            'TABELA' => [
                ['LABEL' => 'Id Categoria', 'CMP' => 'idcategoria'],
                ['LABEL' => 'Categoria', 'CMP' => 'nomecategoria']
            ]
        ];

        $acoes = [
            ['controller' => 'categorias', 'action' => 'atualizar', 'cmp' => 'idcategoria', 'icon' => 'fa fa-pen'],
            ['controller' => 'categorias', 'action' => 'excluir', 'cmp' => 'idcategoria', 'icon' => 'fa fa-trash']
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
                        return $this->redirect(['controller' => 'Categorias', 'action' => 'atualizar', $valueCod]);
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
                    echo 'Registro excluído com sucesso!';
                } else {
                    echo 'Erro ao tentar excluir! Nenhum registro foi afetado.';
                }
            } catch (\PDOException) {
                echo 'Erro na execução da consulta';
            }
        }
    }

}