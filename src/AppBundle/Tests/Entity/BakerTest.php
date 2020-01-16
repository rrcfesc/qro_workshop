<?php

namespace AppBundle\Tests\Entity;

use AppBundle\Entity\Baker;
use phpDocumentor\Reflection\DocBlock\Tags\Param;
use PHPUnit\Framework\TestCase;

class BakerTest extends TestCase
{

    /**
     * @dataProvider getData
     */
    public function testGetAbbreviatedName($firstName, $lastName, $expectedResult)
    {

        $baker = new Baker();
        $baker->setFirstName($firstName);
        $baker->setLastName($lastName);
        $this->assertSame($expectedResult, $baker->getAbbreviatedName(), sprintf('We expect the abreviation like %s', $expectedResult));
    }

    /**
     * @return array<int, array<int, string>>
     */
    public function getData()
    {
        return [
            ['Rik', 'Qwerty', 'Rik Q'],
            [null, null, ' '],
            [null, "Test", ' T'],
            [null, "", ' '],
            ["", "", ' '],
            [' ', ' ', '   '],
        ];
    }
}