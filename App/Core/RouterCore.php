<?php

namespace App\Core;

class RouterCore
{
    private $Uri;
    private $Method;
    private $getArray = [];

    public function __construct()
    {
        $this->initialize();
        $this->execute();
    }

    private function Initialize()
    {
        $this->Method = $_SERVER['REQUEST_METHOD'];
        $this->Uri = $_SERVER['REQUEST_URI'];

        if (isset($this->Uri)) {
            $uriEx = explode('/', $this->Uri);
            $this->Uri = $this->normalize($uriEx);

            for ($i = 0; $i < UNSET_URI_COUNT; $i++) {
                unset($this->Uri[$i]);
            }
            $this->Uri = implode('/', $this->Normalize($this->Uri));
        }
    }

    public function get($router, $callBack)
    {
        $this->getArray[] = [ 
            'router' => $router,
            'callBack' => $callBack
        ];
}

    private function execute()
    {
        switch ($this->Method) {
            case 'GET':
                $this->executeGet();
                break;

            case 'POST':
                break;
        }
    }

    private function executeGet()
    {
        $uriParts = explode('/', $this->Uri);
        $controllerName = ucfirst($uriParts[0] ?? 'Usuarios') . 'Controller'; 
        $action = $uriParts[1] ?? 'index'; 

        $controllerNamespace = "\\App\\Controller\\{$controllerName}";

        if (class_exists($controllerNamespace) && method_exists($controllerNamespace, $action)) {
            $controllerInstance = new $controllerNamespace();
            call_user_func_array([$controllerInstance, $action], []);
        } else {
            echo "404 - Controller ou método não encontrado.";
        }
    }

    private function Normalize($uriTrat)
    {
        return array_values(array_filter($uriTrat));
    }
}
