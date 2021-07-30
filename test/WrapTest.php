<?php

use PHPUnit\Framework\TestCase;

class WrapTest extends TestCase
{
    public function stringsProvider()
    {
        return [
            'WRAP_AFTER_00' => [
                '$string' => '            A very  looooooooooooooooong string.',
                '$length' => 12,
                '$cut' => Text::WRAP_AFTER,
                '$expect' => "A very  looooooooooooooooong\nstring.",
            ],
            'WRAP_AFTER_01' => [
                '$string' => "A very  looooooooooooooooong\nstring.",
                '$length' => 12,
                '$cut' => Text::WRAP_AFTER,
                '$expect' => "A very  looooooooooooooooong\nstring.",
            ],
            'WRAP_AFTER_02' => [
                '$string' => "Looooooooong string.",
                '$length' => 12,
                '$cut' => Text::WRAP_AFTER,
                '$expect' => "Looooooooong\nstring.",
            ],

            'WRAP_BEFORE_00' => [
                '$string' => 'A very  looooooooooooooooong string.',
                '$length' => 12,
                '$cut' => Text::WRAP_BEFORE,
                '$expect' => "A very\nlooooooooooooooooong\nstring.",
            ],
            'WRAP_BEFORE_01' => [
                '$string' => "A very\r\n looooooooooooooooong string.",
                '$length' => 12,
                '$cut' => Text::WRAP_BEFORE,
                '$expect' => "A very\nlooooooooooooooooong\nstring.",
            ],
            'WRAP_BEFORE_02' => [
                '$string' => "A very\n\n looooooooooooooooong string.",
                '$length' => 12,
                '$cut' => Text::WRAP_BEFORE,
                '$expect' => "A very\n\nlooooooooooooooooong\nstring.",
            ],

            'WRAP_BREAK_00' => [
                '$string' => 'A very  looooooooooooong string.',
                '$length' => 12,
                '$cut' => Text::WRAP_BREAK,
                '$expect' => "A very  looo\noooooooooong\nstring.",
            ],
            'WRAP_BREAK_01' => [
                '$string' => '            A very loooooooooooong string.',
                '$length' => 12,
                '$cut' => Text::WRAP_BREAK,
                '$expect' => "A very loooo\noooooooong s\ntring.",
            ],
            'WRAP_BREAK_02' => [
                '$string' => "A very\nlooooooooooooooooong string.",
                '$length' => 12,
                '$cut' => Text::WRAP_BREAK,
                '$expect' => "A very\nlooooooooooo\noooooong str\ning.",
            ],
        ];
    }

    /**
     * @dataProvider stringsProvider
     */
    public function testCanWrapString($string, $length, $cut, $expect)
    {
        $this->assertEquals($expect, Text::wrap($string, $length, "\n", $cut));
    }
}
