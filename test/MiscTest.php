<?php

use PHPUnit\Framework\TestCase;

class MiscTest extends TestCase
{
    public function testTextClassCannotBeInstantiated()
    {
        $this->expectException(BadMethodCallException::class);
        new Text();
    }

    public function testLowercase()
    {
        $this->assertEquals('lowercase', Text::lowercase('LowerCase'));
    }

    public function testUppercase()
    {
        $this->assertEquals('UPPERCASE', Text::uppercase('UpperCase'));
    }

    public function patternProvider()
    {
        return [
            ['not a regex', false],
            ['/valid regex/is', true],
            ['/.+/', true],
        ];
    }

    /**
     * @dataProvider patternProvider
     */
    public function testIsregex($pattern, $isRegex)
    {
        $this->assertEquals($isRegex, Text::isRegEx($pattern));
    }

    public function stringSplitProvider()
    {
        return [
            [
                '$string' => 'Some random string',
                '$pattern' => 'nd',
                '$limit' => 0,
                '$expect' => ['Some ra', 'om string'],
            ],
            [
                '$string' => 'Some random string',
                '$pattern' => '/n+/',
                '$limit' => 0,
                '$expect' => ['Some ra', 'dom stri', 'g'],
            ],
            [
                '$string' => 'Some random string',
                '$pattern' => '/n+/',
                '$limit' => 2,
                '$expect' => ['Some ra', 'dom string'],
            ],
        ];
    }

    /**
     * @dataProvider stringSplitProvider
     */
    public function testSplit($string, $pattern, $limit, $expect)
    {
        $this->assertEquals($expect, Text::split($string, $pattern, $limit));
    }

    public function stringTrimProvider()
    {
        return [
            [
                '$string' => 'xxxTRIMMEDxxx',
                '$chars' => 'x',
                '$side' => Text::TRIM_BOTH,
                '$expect' => 'TRIMMED',
            ],
            [
                '$string' => 'xXxTRIMMEDxXx',
                '$chars' => 'x',
                '$side' => Text::TRIM_BOTH,
                '$expect' => 'XxTRIMMEDxX',
            ],
            [
                '$string' => 'xxxTRIMMEDxxx',
                '$chars' => 'x',
                '$side' => Text::TRIM_LEFT,
                '$expect' => 'TRIMMEDxxx',
            ],
            [
                '$string' => 'xxxTRIMMEDxxx',
                '$chars' => 'x',
                '$side' => Text::TRIM_RIGHT,
                '$expect' => 'xxxTRIMMED',
            ],
        ];
    }

    /**
     * @dataProvider stringTrimProvider
     */
    public function testTrim($string, $chars, $side, $expect)
    {
        $this->assertEquals($expect, Text::trim($string, $chars, $side));
    }
}
