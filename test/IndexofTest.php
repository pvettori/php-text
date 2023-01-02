<?php

use PHPUnit\Framework\TestCase;
use PVproject\Utils\Text;

class IndexofTest extends TestCase
{
    public function searchProvider()
    {
        return [
            [
                '$substr' => 'Som',
                '$offset' => 0,
                '$cs' => true,
                '$expect' => 0,
            ],
            [
                '$substr' => 'som',
                '$offset' => 0,
                '$cs' => false,
                '$expect' => 0,
            ],
            [
                '$substr' => 'som',
                '$offset' => 0,
                '$cs' => true,
                '$expect' => -1,
            ],
            [
                '$substr' => 'ran',
                '$offset' => 3,
                '$cs' => true,
                '$expect' => 5,
            ],
            [
                '$substr' => 'ran',
                '$offset' => 6,
                '$cs' => true,
                '$expect' => -1,
            ],
        ];
    }

    /**
     * @dataProvider searchProvider
     */
    public function testCanGetIndex($substr, $offset, $cs, $expect)
    {
        $this->assertEquals($expect, Text::indexOf($substr, 'Some random text.', $offset, $cs));
    }
}
