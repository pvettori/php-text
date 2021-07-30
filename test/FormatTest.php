<?php

use PHPUnit\Framework\TestCase;

class FormatTest extends TestCase
{
    public function formatProvider()
    {
        return [
            [
                '$format' => 'test %s',
                '$values' => ['x'],
                '$expect' => 'test x',
            ],
            [
                '$format' => 'test %{a} %{0} %{[1].b}',
                '$values' => ['x', ['b' => 'y'], 'a' => 1],
                '$expect' => 'test 1 x y',
            ],
            [
                '$format' => '${0:d}',
                '$values' => [1.234],
                '$expect' => '1.234',
            ],
            [
                '$format' => '${0:d}',
                '$values' => [-1234.567],
                '$expect' => '-1234.567',
            ],
            [
                '$format' => '${0:c}',
                '$values' => [-1234.567],
                '$expect' => '€ -1.234,56',
            ],
            [
                '$format' => '${0:c}',
                '$values' => [-1234.567],
                '$expect' => '€ -1.234,56',
            ],
            [
                '$format' => '${0:c}',
                '$values' => ['-1234.567'],
                '$expect' => '€ -1.234,56',
            ],
            [
                '$format' => '${0:$ 0,000.00}',
                '$values' => [-1234.567],
                '$expect' => '€ -1.234,56',
            ],
            [
                '$format' => '${0:\$ 0,000.00}',
                '$values' => [-1234.567],
                '$expect' => '$ -1.234,56',
            ],
            [
                '$format' => '${0:t}',
                '$values' => [1234.567],
                '$expected' => '1970-01-01 00:20:34 567000',
            ],
            [
                '$format' => '${0:t}',
                '$values' => ['1234.567'],
                '$expected' => '1970-01-01 00:20:34 567000',
            ],
            [
                '$format' => '${0:Y-m-d H:i:s}',
                '$values' => [1234.567],
                '$expected' => '1970-01-01 00:20:34',
            ],
            [
                '$format' => '${0:Y-m-d}',
                '$values' => ['2021-01-01 00:00:00.123456'],
                '$expected' => '2021-01-01',
            ],
            [
                '$format' => '${0:Y-m-d H:i:00}',
                '$values' => ['now'],
                '$expected' => date_format(date_create('now', timezone_open('UTC')), 'Y-m-d H:i:00'),
            ],
        ];
    }

    /**
     * @dataProvider formatProvider
     */
    public function testCanFormatString($format, $values, $expect)
    {
        Text::setLocale([
            'currency_symbol' => '€',
            'decimal_separator' => ',',
            'thousand_separator' => '.',
            'timezone' => 'UTC',
        ]);

        $this->assertEquals($expect, Text::format($format, $values));
    }

    public function testCanGetSystemLocale()
    {
        $this->assertEquals([
            'currency_abbrev' => 'USD',
            'currency_digits' => 2,
            'currency_symbol' => '$',
            'decimal_separator' => '.',
            'thousand_separator' => ',',
            'timezone' => 'UTC',
        ], Text::getSystemLocale());
    }
}
