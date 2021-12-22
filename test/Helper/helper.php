<?php

namespace Belajar\App {
    
    function header(string $value) {
        echo $value;
    }
}

namespace Belajar\Service {
    
    function setcookie(string $name, string $value) {
        echo "$name: $value";
    }
    
}