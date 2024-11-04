<?php 

/**
 * @author João Vitor Esequiel Vieira
 */

namespace App\Controller\Component;
use App\Controller\Component\DataComponent;
use App\Controller\Component\HoldComponent;

class AppComponent {
    public $Data;
    public $Hold;

    public function __construct() {
        $this->Data = new DataComponent;
        $this->Hold = new HoldComponent();
    }

    /**
     * @param array $cmp Array associativo com os CAMPOS
     * @param array $filtros Array associativo com os filtros a serem aplicados
     * @author João Vitor Esequiel Vieira
     */

     public function setFrm($cmp, $filtros = null) {
        echo '<form method="POST">';
        $strSql = '';
    
        if (isset($cmp['CAMPOS']) && is_array($cmp['CAMPOS'])) {
            foreach ($cmp['CAMPOS'] as $cmpsType) {
                $disabled = '';
                $readonly = '';
                $required = ''; 
        
                $valueClass  = isset($cmpsType['CLASS']) ? $cmpsType['CLASS'] : '';
                $valueLabel  = isset($cmpsType['LABEL']) ? $cmpsType['LABEL'] : '';
                $valueValue  = isset($cmpsType['VALUE']) ? $cmpsType['VALUE'] : '';
                $placeholder = isset($cmpsType['PLACEHOLDER']) ? $cmpsType['PLACEHOLDER'] : '';
                $fieldCmp    = isset($cmpsType['CMP']) ? $cmpsType['CMP'] : '';
        
                if (isset($cmpsType['OBG']) && $cmpsType['OBG'] === 'S') {
                    $required = 'required';
                }
        
                if (!empty($cmpsType['MASK'][0])) {
                    switch ($cmpsType['MASK'][0]) {
                        case 'dateBrazil': 
                            $valueValue = $this->Hold->dateBrazil($valueValue); 
                            break;
                        case 'CEP':
                            $valueValue = $this->Hold->formatarCEP($valueValue);
                            break;
                        case 'CPF':
                            $valueValue = $this->Hold->formatarCPF($valueValue);
                            break;
                        case 'TEL':
                            $valueValue = $this->Hold->formatarTelefone($valueValue);
                            break;
                        default:
                            return 'O parâmetro ' . $cmpsType['MASK'][0] . ' não existe!';
                            break;
                    }
                }
        
                if (!empty($cmpsType['ACAO']) && is_array($cmpsType['ACAO'])) {
                    foreach ($cmpsType['ACAO'] as $acoes) {
                        if ($acoes === 'DESATIVAR') {
                            $disabled = 'disabled';
                        } elseif ($acoes === 'LEITURA') {
                            $readonly = 'readonly';
                        }
                    }
                }
        
                if (isset($cmpsType['type']) && in_array($cmpsType['type'], ['text', 'password', 'email'])) {
                    $valueType = $cmpsType['type'];
                    echo '<label>' . $valueLabel . '</label>';
                    echo '<input type="' . $valueType . '" name="' . $fieldCmp . '" class="' . $valueClass . '" placeholder="' . $placeholder . '" value="' . htmlspecialchars($valueValue, ENT_QUOTES, 'UTF-8') . '" ' . $readonly . ' ' . $disabled . ' ' . $required . ' />';
                    echo '<br>';
                } 
                elseif (isset($cmpsType['type']) && $cmpsType['type'] === 'textarea') {
                    echo '<label for="' . $valueClass . '">' . $valueLabel . '</label>';
                    echo '<textarea name="' . $fieldCmp . '" class="' . $valueClass . '" ' . $readonly . ' ' . $disabled . ' ' . $required . '>' . htmlspecialchars($valueValue, ENT_QUOTES, 'UTF-8') . '</textarea>';
                    echo '<br>';
                } 
                elseif (isset($cmpsType['type']) && $cmpsType['type'] === 'select') {
                    $options = isset($cmpsType['options']) && is_array($cmpsType['options']) ? $cmpsType['options'] : [];
                    echo '<label for="' . $valueClass . '">' . $valueLabel . '</label>';
                    echo '<select name="' . $fieldCmp . '" class="' . $valueClass . '" ' . $readonly . ' ' . $disabled . ' ' . $required . '>';
                    foreach ($options as $optionValue => $optionLabel) {
                        $selected = $optionValue === $valueValue ? 'selected' : '';
                        echo '<option value="' . htmlspecialchars($optionValue, ENT_QUOTES, 'UTF-8') . '" ' . $selected . '>' . htmlspecialchars($optionLabel, ENT_QUOTES, 'UTF-8') . '</option>';
                    }
                    echo '</select>';
                    echo '<br>';
                } 
                elseif (isset($cmpsType['type']) && in_array($cmpsType['type'], ['radio', 'checkbox'])) {
                    $valueType = $cmpsType['type'];
                    $options = isset($cmpsType['options']) && is_array($cmpsType['options']) ? $cmpsType['options'] : [];
                    echo '<fieldset>';
                    echo '<legend>' . $valueLabel . '</legend>';
                    foreach ($options as $optionValue => $optionLabel) {
                        $checked = in_array($optionValue, (array) $valueValue) ? 'checked' : '';
                        echo '<label>';
                        echo '<input type="' . $valueType . '" name="' . $fieldCmp . '[]" value="' . htmlspecialchars($optionValue, ENT_QUOTES, 'UTF-8') . '" ' . $checked . ' ' . $readonly . ' ' . $disabled . ' ' . $required . ' />';
                        echo htmlspecialchars($optionLabel, ENT_QUOTES, 'UTF-8');
                        echo '</label>';
                        echo '<br>';
                    }
                    echo '</fieldset>';
                }
                elseif (isset($cmpsType['type']) && $cmpsType['type'] === 'date') {
                    $valueType = $cmpsType['type'];
                    echo '<label for="' . $valueClass . '">' . $valueLabel . '</label>';
                    echo '<input type="' . $valueType . '" name="' . $fieldCmp . '" class="' . $valueClass . '" value="' . htmlspecialchars($valueValue, ENT_QUOTES, 'UTF-8') . '" ' . $readonly . ' ' . $disabled . ' ' . $required . ' />';
                    echo '<br>';
                } 
                elseif (isset($cmpsType['type']) && $cmpsType['type'] === 'submit') {
                    $valueLabel = isset($cmpsType['LABEL']) ? $cmpsType['LABEL'] : 'Confirmar';
                    $submitName = isset($cmpsType['NAME']) ? $cmpsType['NAME'] : 'POST';
                    $valueClass = isset($cmpsType['CLASS']) ? $cmpsType['CLASS'] : '';
                    echo '<button type="submit" name="' . $submitName . '" class="' . $valueClass . '">' . $valueLabel . '</button>';
                    echo '<br>';
                }
                else {
                    echo 'Nenhum tipo foi passado, por isso o campo não foi renderizado!';
                }
            }
        }
    
        if (!empty($filtros['FILTROS']) && is_array($filtros['FILTROS'])) {
            foreach ($filtros['FILTROS'] as $filtro) {
                $valueLabel = isset($filtro['LABEL']) ? $filtro['LABEL'] : '';
                $cmpsBd = isset($filtro['CMP']) ? $filtro['CMP'] : '';
                $typeQuery = isset($filtro['OPERADOR']) ? $filtro['OPERADOR'] : '';
                $value = isset($filtro['VALOR']) ? $filtro['VALOR'] : '';
    
                if (!empty($typeQuery) && !empty($cmpsBd)) {
                    switch ($typeQuery) {
                        case 'LIKE':
                            $strSql .= " AND $cmpsBd LIKE '%$value%' ";
                            break;
    
                        case 'NOT LIKE':
                            $strSql .= " AND $cmpsBd NOT LIKE '%$value%' ";
                            break;
    
                        case 'BETWEEN':
                            if (is_array($value) && count($value) === 2) {
                                $strSql .= " AND $cmpsBd BETWEEN '{$value[0]}' AND '{$value[1]}' ";
                            }
                            break;
    
                        case 'IN':
                            if(is_array($value) && count($value) >= 1) {
                                $formatValue = implode("','", array_map('addslashes', $value));
                                $strSql .= " AND $cmpsBd IN ('$formatValue') ";
                            }
                            break;
    
                        case '>=':
                            $strSql .= " AND $cmpsBd >= '$value' ";
                            break;
    
                        case '<=': 
                            $strSql .= " AND $cmpsBd <= '$value' ";
                            break;
    
                        case '=':
                            $strSql .= " AND $cmpsBd = '$value' ";
                            break;
    
                        case '!=':
                            $strSql .= " AND $cmpsBd != '$value' ";
                            break;
                    }
                }
            }
        }
        
        echo '</form>';
        
        return $strSql;
    }

    /**
     * @author João Vitor Esequiel Vieira
     */
    public function getFormData($cmp) {
        $dataForm = [];
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            foreach ($cmp['CAMPOS'] as $cmpsFil) {
                if (isset($cmpsFil['CMP']) && isset($_POST[$cmpsFil['CMP']])) {
                    $dataForm[$cmpsFil['CMP']] = $_POST[$cmpsFil['CMP']];
                }
            }
        }
    
        return $dataForm;
    }
    

    public function isSubmit($submitName) {
        return isset($_POST[$submitName]);
    }

    /**
     * @author João Vitor Esequiel Vieira
     * @param array $cmps Array com as informações que serão renderizadas na View
     * @param array $strQuery Resultado da consulta que será utilizado ao renderizar a View
     * EM DESENVOLVIMENTO
     */
    function setTable($cmps, $queryResult, $subMenu = null, $acoes = null) {
        if (is_array($subMenu)) {
            echo '<ul class="navbar">';
            foreach ($subMenu as $key => $valueSubMenu) {
                if (is_array($valueSubMenu)) {
                    echo '<li class="menu-item">' . $key . '<ul class="submenu">';
                    foreach ($valueSubMenu as $subKey => $subValue) {
                        echo '<li><a href="' . $subValue . '">' . $subKey . '</a></li>';
                    }
                    echo '</ul></li>';
                }
            }
            echo '</ul>';
        }
    
        $border = ' border="1"';
    
        if (isset($cmps['TABELA']) && is_array($cmps['TABELA'])) {
            echo '<table' . $border . '>';
    
            $labels = [];
            $bdCmps = [];
            $tableMask = [];
            foreach ($cmps['TABELA'] as $item) {
                if (isset($item['LABEL']) && isset($item['CMP'])) {
                    $labels[] = $item['LABEL'];
                    $bdCmps[] = $item['CMP'];
                    $tableMask[] = $item['MASK'] ?? null;
                }
            }
    
            if (!empty($labels)) {
                echo '<tr>';
                foreach ($labels as $label) {
                    echo '<td>' . $label . '</td>';
                }
                if ($acoes) {
                    echo '<td>Ações</td>';
                }
                echo '</tr>';
            }
    
            if (!empty($bdCmps)) {
                foreach ($queryResult as $rowResult) {
                    echo '<tr>';
                    foreach ($bdCmps as $keyMask => $cmpQuery) {
                        $value = isset($rowResult[$cmpQuery]) ? $rowResult[$cmpQuery] : '/A';
    
                        if (!empty($tableMask[$keyMask])) {
                            switch ($tableMask[$keyMask]) {
                                case 'dateBrazil':
                                    $value = $this->Hold->dateBrazil($value);
                                    break;
    
                                case 'CEP':
                                    $value = $this->Hold->formatarCEP($value);
                                    break;
    
                                case 'CPF':
                                    $value = $this->Hold->formatarCPF($value);
                                    break;
    
                                case 'TEL':
                                    $value = $this->Hold->formatarTelefone($value);
                                    break;
    
                                case 'DATA':
                                    $value = $this->Hold->convertData($value);
                                    break;
    
                                default:
                                    break;
                            }
                        }
                        echo '<td>' . $value . '</td>';
                    }
    
                    if ($acoes) {
                        echo '<td>';
                        foreach ($acoes as $acao) {
                            $url = "{$acao['controller']}/{$acao['action']}/{$rowResult[$acao['cmp']]}";
                            echo "<a href='$url'><i class='{$acao['icon']}'></i></a> ";
                        }
                        echo '</td>';
                    }
                    echo '</tr>';
                }
            } else {
                echo 'Configuração inválida.';
            }
            echo '</table>';
        }
    }
    

    public function redirect($params)
    {
        $controller = $params['controller'] ?? '';
        $action = $params['action'] ?? '';

        unset($params['controller'], $params['action']);
        
        $url = "/UniFood/{$controller}/{$action}";

        if (!empty($params)) {
            $url .= '/' . implode('/', $params);
        }

        header("Location: $url");
        exit;
    }
    
} 

?>