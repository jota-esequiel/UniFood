<?php 

namespace App\Core;

class RouterCore 
{
    private $Uri;
    private $method;

    public function __construct()
    {
        
        $this->Initialize();
    }

    private function Initialize() {
        $this->method = $_SERVER['REQUEST_METHOD'];
        $Uri = $_SERVER['REQUEST_URI'];

        echo "<pre>"; print_r($_SERVER['REQUEST_URI']); die();
        
    }
}