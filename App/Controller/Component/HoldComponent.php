<?php 

/**
 * Funções do sistema
 * @author João Vitor Esequiel Vieira
 */

namespace App\Controller\Component;

class HoldComponent {

    public $Data;

    public function __construct()
    {
        $this->Data = new DataComponent();
    }

    /**  
    * @param mixed $strPdo Nome do Banco de Dados - Se for vazio ou null, então será atribuído 'Papaléguas'
    * @param mixed $operador Tipo esperado 'INSERT'
    * @param mixed $bdTable Tabela na qual quer inserir os dados
    * @param array $cmps São as informações com os devidos campos que quer inserir
    */

    public function insert($strPdo, $operador, $bdTable, $cmps = []) {
        if ($operador != 'INSERT') {
            throw new \InvalidArgumentException("A tentativa não é um INSERT! Verifique o parâmetro!");
        }
    
        $bdObj = $this->Data->bdConnect($strPdo);
    
        $cmpsValue = implode(', ', array_keys($cmps));
        $bindValue = ':' . implode(', :', array_keys($cmps));
    
        $strInsert = "INSERT INTO $bdTable ($cmpsValue) VALUES ($bindValue)";
    
        try {
            $strStmt = $bdObj->prepare($strInsert);
    
            foreach ($cmps as $keys => $cmpsKeys) {
                $strStmt->bindValue(":$keys", $cmpsKeys);
            }
    
            return $strStmt->execute();
    
        } catch (\PDOException) {
            echo "Ocorreu um problema ao fazer a inserção na tabela '$bdTable'!";
            return false;
        }
    }

    public function update($strPdo, $operador, $bdTable, $cmps = [], $conditions = []) {
        if ($operador != 'UPDATE') {
            throw new \InvalidArgumentException("A tentativa não é um UPDATE! Verifique o parâmetro!");
        }
    
        $bdObj = $this->Data->bdConnect($strPdo);
    
        $setParts = [];
        foreach (array_keys($cmps) as $key) {
            $setParts[] = "$key = :$key";
        }
        $setClause = implode(', ', $setParts);
    
        $whereParts = [];
        foreach (array_keys($conditions) as $key) {
            $whereParts[] = "$key = :$key";
        }
        $whereClause = implode(' AND ', $whereParts);
    
        $strUpdate = "UPDATE $bdTable SET $setClause WHERE $whereClause";
    
        try {
            $strStmt = $bdObj->prepare($strUpdate);
    
            foreach ($cmps as $key => $value) {
                $strStmt->bindValue(":$key", $value);
            }
    
            foreach ($conditions as $key => $value) {
                $strStmt->bindValue(":$key", $value);
            }
    
            return $strStmt->execute();
    
        } catch (\PDOException) {
            echo "Ocorreu um problema ao fazer a atualização na tabela '$bdTable'!";
            return false;
        }
    }
    
    
    
    
    public function dateBrazil($date) {
        if (strpos($date, '-') !== false) {
            $dateArray = explode('-', $date);
            if (count($dateArray) === 3) {
                return $dateArray[2] . '/' . $dateArray[1] . '/' . $dateArray[0];
            }
        }
        return $date;
    }

    
    public function convertData($date) {
        if (strlen($date) != 8) {
            return "Formato de data inválido";
        }

        $ano = substr($date, 0, 4);
        $mes = substr($date, 4, 2);
        $dia = substr($date, 6, 2);

        return "$dia/$mes/$ano";
    }

    public function formatarCPF($cpf) {
        $cpf = preg_replace('/[^0-9]/', '', $cpf);
        return substr($cpf, 0, 3) . '.' . substr($cpf, 3, 3) . '.' . substr($cpf, 6, 3) . '-' . substr($cpf, 9, 3);
    }

    public function formatarTelefone($fone) {
        $fone = preg_replace('/[^0-9]/', '', $fone);
        return '(' . substr($fone, 0, 2) . ')' . substr($fone, 2, 5) . '-' . substr($fone, 7);
    }

    public function formatarCEP($cep) {
        $cep = preg_replace('/[^0-9]/', '', $cep);
        if(strlen($cep) !== 8) {
            throw new \InvalidArgumentException('O CEP está com com uma quantidade de caracteres insuficiente!');
        }
        return substr($cep, 0, 5) . '-' . substr($cep, 5);
    }

    public function getSaudacao() {
        date_default_timezone_set('America/Sao_Paulo');

        $date = (int)date('H');

        if($date > 0 && $date <= 12) {
            return "Bom dia!";
        } elseif ($date >= 12 && $date < 18) {
            return "Boa tarde!";
        } else {
            return "Boa noite!";
        } 
    }

    public function displayHelp($message, $iconType, $fontLink = null) {
        $defaultFontLink = '<link href="../fontawesome/css/all.css" rel="stylesheet">';

        if($fontLink === 'link') {
            $fontLink = $defaultFontLink;
        } elseif ($fontLink !== null && strpos($fontLink, 'href') === false) {
            $fontLink = '<link href="' . $fontLink . '" rel="stylesheet">';
        } else {
            $fontLink = $defaultFontLink;
        }

        $icons = [
            'alerta'       => 'fa-triangle-exclamation',
            'exclamacao'   => 'fa-exclamation',
            'interrogacao' => 'fa-question'
        ];
    
        $iconClass = isset($icons[$iconType]) ? $icons[$iconType] : $icons['interrogacao'];
    
        echo $fontLink . "\n";
        
        echo '<i class="fa-solid ' . $iconClass . '"></i> ';
        echo '<span>' . $message . '</span>';
    }


    public function getLink($keyLinks) {
        $links = array(

            //Links HTML 
            'link'   => '<link href="../fontawesome/css/all.css" rel="stylesheet">',
            'css'    => '<link href="../templates/CSS/form.css>',
    
            //Ícones FontAwesome
            'email'  => '<i class="fa-solid fa-envelope"></i>',
            'user'   => '<i class="fa-solid fa-user"></i>',
            'logout' => '<i class="fa-solid fa-right-from-bracket"></i>',
            'edit'   => '<i class="fa-solid fa-pen-to-square"></i>',
            'save'   => '<i class="fa-solid fa-floppy-disk"></i>',
            'excel'  => '<i class="fa-sharp fa-solid fa-file-excel"></i>',
            'pdf'    => '<i class="fa-solid fa-file-pdf"></i>',
            'box'    => '<i class="fa-solid fa-box-open"></i>',
        );
    
        if (array_key_exists($keyLinks, $links)) {
            return $links[$keyLinks];
        } else {
            return null;
        }
    }

    /**
     * @param array $redirect Array que contém o link para onde quer redirecionar o usuário
     * @author João Vitor Esequiel Vieira
     */

    public function redirect($redirect = []){
        if(!empty($redirect['url']) && filter_var($redirect['url'], FILTER_VALIDATE_URL)) {
            header('Location: ' . $redirect['url']);
            exit();
        } else {
            throw new \InvalidArgumentException('Verifique o se ' . $redirect['url'] . ' está correto!');
        }
    }
}
?>