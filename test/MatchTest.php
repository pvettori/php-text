<?php

use PHPUnit\Framework\TestCase;

class MatchTest extends TestCase
{
    public function matchProvider()
    {
        return [
            [
                '$string' => 'Some random text.',
                '$pattern' => 'me',
                '$match' => 'me',
            ],
            [
                '$string' => 'Some random text.',
                '$pattern' => 'xyz',
                '$match' => null,
            ],
            [
                '$string' => 'Some random text.',
                '$pattern' => '/m./',
                '$match' => 'me',
            ],
            [
                '$string' => 'Some random text.',
                '$pattern' => '/(m.)/',
                '$match' => 'me',
                '$groups' => ['me'],
            ],
        ];
    }

    /**
     * @dataProvider matchProvider
     */
    public function testCanMatchPattern($string, $pattern, $match, $groups = [])
    {
        $this->assertEquals($match, Text::match($string, $pattern, $capture));
        $this->assertEquals($groups, $capture);
    }
}
