<?php 
require_once '../vendor/autoload.php';

use App\Controller\TestesController;
new \App\Config\Router();

$controller = new TestesController();
?>