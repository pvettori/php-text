<?php

use PHPUnit\Framework\TestCase;

class StyleTest extends TestCase
{
    public function styleProvider()
    {
        return [
            [
                '$string' => 'Aaa AAA. aBa',
                '$style' => Text::STYLE_VAR_CAMEL_CASE,
                '$expect' => 'aaaAaaABa',
            ],
            [
                '$string' => 'Aaa AAA. aBa',
                '$style' => Text::STYLE_VAR_SNAKE_CASE,
                '$expect' => 'aaa_aaa__a_ba',
            ],
            [
                '$string' => 'Aaa AAA. aBa',
                '$style' => Text::STYLE_TEXT_TITLE,
                '$expect' => 'Aaa AAA. Aba',
            ],
            [
                '$string' => 'some text ,in a paragraph .with wrong punctuation    .',
                '$style' => Text::STYLE_TEXT_PARAGRAPH,
                '$expect' => 'Some text, in a paragraph. With wrong punctuation. ',
            ],
        ];
    }

    /**
     * @dataProvider styleProvider
     */
    public function testCanStyleText($string, $style, $expect)
    {
        $this->assertEquals($expect, Text::style($string, $style));
    }
}
