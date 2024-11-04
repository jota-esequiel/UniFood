<?php
namespace App\Core;

class AssetManager {
    private static $styles = [];
    private static $scripts = [];

    public static function addCss($url) {
        self::$styles[] = $url;
    }

    public static function addJs($url) {
        self::$scripts[] = $url;
    }

    public static function renderCss() {
        foreach (self::$styles as $style) {
            echo '<link rel="stylesheet" href="' . $style . '">' . PHP_EOL;
        }
    }

    public static function renderJs() {
        foreach (self::$scripts as $script) {
            echo '<script src="' . $script . '"></script>' . PHP_EOL;
        }
    }
}
