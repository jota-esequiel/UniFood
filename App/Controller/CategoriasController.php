<?php 

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
                ['LABEL' => 'Nome Categoria', 'type' => 'text', 'PLACEHOLDER' => 'Nome categoria', 'CMP' => 'nomeCategoria', 'OBG' => 'S'],
                ['LABEL' => 'Cadastre-se', 'type' => 'submit', 'NAME' => 'POST']
            ]
        ];

        $filtros = [];

        $this->setFrm($cmp, $filtros);

        if($this->isSubmit('POST')) {
            $formData = $this->getFormData($cmp);

            if(!empty($formData)) {
                $this->Hold->insert('UniFood', 'INSERT', 'categoria', $formData);
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
        $this->setTable($cmps, $result, $subMenu);
    }


    public function atualizar($idCategoria) {
        $valueCod = $idCategoria;
    
        if ($valueCod > 0) {
            $this->Data->bdConnect('UniFood');
            
            $strQuery = $this->Categorias->sqlVisualizar(); 
            $strQuery .= " WHERE idCategoria = :idCategoria";
            $strBind = [':idCategoria' => $valueCod];
            $strResult = $this->Data->bdExecBind('UniFood', $strQuery, $strBind);
    
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
                        $this->Hold->update('UniFood', 'UPDATE', 'categoria', $formData, ['idCategoria' => $valueCod]);
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
            $this->Data->bdConnect('UniFood');
    
            $strQuery = "DELETE FROM categoria WHERE idCategoria = :idCategoria";
            $strBind = [':idCategoria' => $codCategoria];
    
            try {
                $strResult = $this->Data->bdExecBind('UniFood', $strQuery, $strBind);

                if ($strResult > 0) {
                    echo 'Registro excluído com sucesso!';
                } else {
                    echo 'Erro ao tentar excluir! Nenhum registro foi afetado.';
                }
            } catch (Exception) {
                echo 'Erro na execução da consulta';
            }
        }
    }
}