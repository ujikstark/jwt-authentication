<?php

namespace Belajar\App;

class View
{
    public static function render(string $view, $data)
    {   
        require __DIR__ . '/../View/header.php';
        require __DIR__ . '/../View/' . $view . '.php';
        require __DIR__ . '/../View/footer.php';
    }


    public static function redirect(string $url, $data = null) {
        header("Location: $url");

        if (getenv("mode") != "test") {
            exit();
        }
    }

}