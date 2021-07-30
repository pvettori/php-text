<?php

use PHPUnit\Framework\TestCase;

class MiscTest extends TestCase
{
    public function testLowercase()
    {
        $this->assertEquals('lowercase', Text::lowercase('LowerCase'));
    }

    public function testUppercase()
    {
        $this->assertEquals('UPPERCASE', Text::uppercase('UpperCase'));
    }
}
