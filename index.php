<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UniFood</title>

    <?php 
        require '../UniFood/vendor/autoload.php';
        require '../UniFood/App/Config/config.php';

        use App\Core\AssetManager;

        AssetManager::addCss("https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css");
        AssetManager::renderCss(); 

        AssetManager::addCss('../templates/CSS/setFrm1.css');
        AssetManager::addCss('../templates/CSS/styleTablePad.css');
        AssetManager::addCss('../templates/CSS/showToast.css');
        AssetManager::addCss('../fontawesome/css/all.css');
        AssetManager::renderCss();
    ?>
</head>
<body>

<?php
    new \App\Core\RouterCore();
?>

<?php
    AssetManager::addJs("https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js");
    AssetManager::addJs('../templates/JS/showToast.js');
    AssetManager::renderJs();
?>

</body>
</html>
