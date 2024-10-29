<?php

namespace App\Controller\Component;

class DataComponent {

    public function bdConnect($bd = null) {
        $host = 'localhost';
        $user = 'postgres'; 
        $pass = '12345';    
        $port = '5432';    
    
        if (is_null($bd)) {
            $bd = 'UniFood'; 
        }
    
        try {
            $strPdo = new \PDO("pgsql:host=$host;port=$port;dbname=$bd", $user, $pass);
            $strPdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    
            $result = $strPdo->query("SELECT current_database()")->fetchColumn();
            if (!$result) {
                throw new \InvalidArgumentException("O banco de dados '$bd' não existe.");
            }
    
            return $strPdo;
    
        } catch (\Exception $PDO) {
            if ($PDO->getCode() === '3D000') { 
                die("O banco de dados '$bd' não existe.");
            } else {
                die("Aconteceu algum problema ao se conectar com o banco de dados '$bd'!");
            }
        }
    }
    
    public function bdQueryFetchAll($strQuery) {
        try {
            $strPdo = $this->bdConnect();
            $strStmt = $strPdo->prepare($strQuery);
            
            if (empty($strStmt)) {
                die ("Ocorreu algum problema ao preparar a consulta ao banco de dados!");
            }
    
            $strStmt->execute();
            $strResult = $strStmt->fetchAll(\PDO::FETCH_ASSOC);
            
            return $strResult;
            
        } catch (\PDOException) {
            die("Erro ao executar a consulta ao banco de dados!");
        }
    }


    public function bdExecBind($strPdo, $strQuery, $strBind = [], $fetchAssoc = true) {
        try {
            $strPdo = $this->bdConnect($strPdo);
    
            if ($strPdo === null) {
                die("Erro ao conectar ao banco de dados!");
            }
    
            $strStmt = $strPdo->prepare($strQuery);
    
            if ($strStmt === false) {
                die("Ocorreu algum problema ao preparar a consulta ao banco de dados!");
            }
    
            foreach ($strBind as $key => $value) {
                $strStmt->bindValue($key, $value);
            }
    
            $strStmt->execute();
    
            if ($fetchAssoc) {
                return $strStmt->fetch(\PDO::FETCH_ASSOC);
            }
    
            return $strStmt;
    
        } catch (\PDOException) {
            die("Erro ao executar a consulta ao banco de dados!");
        }
    }
    
}