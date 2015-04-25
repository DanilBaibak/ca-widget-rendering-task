<?php

namespace DM\WidgetDemoBundle\Test\Utils;

use DM\WidgetDemoBundle\Utils\ColorUtils;

class ColorUtilsTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider hex2RgbDataProvider
     *
     * @param $hex
     * @param array $rgb
     */
    public function testHex2Rgb($hex, array $rgb)
    {
        $this->assertEquals($rgb, ColorUtils::hex2rgb($hex));
    }

    public function hex2RgbDataProvider()
    {
        return [
            ['#000000', [0,0,0]],
            ['000000', [0,0,0]],
            ['FFFFFF', [255,255,255]],
            ['ffffff', [255,255,255]],
            ['fff', [255,255,255]]
        ];
    }

    /**
     * @dataProvider isValidHexColorCodeDataProvider
     *
     * @param $hex
     * @param bool $isValid
     */
    public function testIsValidHexColorCode($hex, $isValid)
    {
        $this->assertEquals($isValid, ColorUtils::isValidHexColorCode($hex));
    }

    public function isValidHexColorCodeDataProvider()
    {
        return [
            ['#000000', true],
            ['000000', true],
            ['FFFFFF', true],
            ['ffffff', true],
            ['fff', true],
            ['zzz', false],
            ['#zzz', false],
            ['zzzzzz', false],
            ['#zzzzzz', false],
            ['afafzz', false],
            ['', false],
            [null, false]
        ];
    }
}