<?php

namespace App\Core;

class RouterCore
{
    private $Uri;
    private $Method;
    private $getArray = [];
    private $postArray = [];

    public function __construct()
    {
        $this->initialize();
        $this->execute();
    }

    private function initialize()
    {
        $this->Method = $_SERVER['REQUEST_METHOD'];
        $this->Uri = $_SERVER['REQUEST_URI'];

        if (preg_match('/\.(?:css|js|png|jpg|jpeg|gif|woff|woff2|ttf|svg)$/', $this->Uri)) {
            return false;
        }

        if (isset($this->Uri)) {
            $uriEx = explode('/', $this->Uri);
            $this->Uri = $this->normalize($uriEx);

            for ($i = 0; $i < UNSET_URI_COUNT; $i++) {
                unset($this->Uri[$i]);
            }
            $this->Uri = implode('/', $this->normalize($this->Uri));
        }
    }

    public function get($router, $callBack)
    {
        $this->getArray[] = [
            'router' => $router,
            'callBack' => $callBack
        ];
    }

    public function post($router, $callBack)
    {
        $this->postArray[] = [
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
                $this->executePost();
                break;

            default:
                echo "Método não suportado.";
                break;
        }
    }

    private function executeGet()
    {
        $uriParts = explode('/', $this->Uri);
        $controllerName = ucfirst($uriParts[0] ?? 'Usuarios') . 'Controller'; 
        $action = $uriParts[1] ?? 'index'; 
        $params = array_slice($uriParts, 2);

        $controllerNamespace = "\\App\\Controller\\{$controllerName}";

        if (class_exists($controllerNamespace) && method_exists($controllerNamespace, $action)) {
            $controllerInstance = new $controllerNamespace();
            call_user_func_array([$controllerInstance, $action], $params); 
        } else {
            echo "404 - Controller ou método não encontrado.";
        }
    }

    private function executePost()
    {
        $uriParts = explode('/', $this->Uri);
        $controllerName = ucfirst($uriParts[0] ?? 'Usuarios') . 'Controller'; 
        $action = $uriParts[1] ?? 'store';
        $params = array_slice($uriParts, 2);

        $controllerNamespace = "\\App\\Controller\\{$controllerName}";

        if (class_exists($controllerNamespace) && method_exists($controllerNamespace, $action)) {
            $controllerInstance = new $controllerNamespace();
            call_user_func_array([$controllerInstance, $action], array_merge($params, [$_POST]));
        } else {
            echo "404 - Controller ou método não encontrado.";
        }
    }

    private function normalize($uriTrat)
    {
        return array_values(array_filter($uriTrat));
    }
}
