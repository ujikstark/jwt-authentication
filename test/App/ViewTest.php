<?php

namespace Belajar\App;

use PHPUnit\Framework\TestCase;

class ViewTest extends TestCase
{
    public function testRender()
    {
        View::render('User/login', [
            "Authentication JWT"
        ]);

        $this->expectOutputRegex('[Authentication JWT]');
        $this->expectOutputRegex('[html]');
        $this->expectOutputRegex('[body]');
        $this->expectOutputRegex('[Login]');
        $this->expectOutputRegex('[Username]');
        $this->expectOutputRegex('[Password]');
    }
}