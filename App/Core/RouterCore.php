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
        require '../UniFood/App/Config/Router.php';
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

    public function addRoute($router, $callBack)
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
        foreach ($this->getArray as $get) {
            $routerR = substr($get['router'], 1);
            if ($routerR == $this->Uri) {
                if(is_callable( $get['callBack'])) {
                    $get['callBack']();
                break;
                }
            }
        }
    }

    private function Normalize($uriTrat)
    {
        return array_values(array_filter($uriTrat));
    }
}
