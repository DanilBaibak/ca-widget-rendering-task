<?php
namespace DM\WidgetDemoBundle\Utils;

class ColorUtils
{
    /**
     * http://bavotasan.com/2011/convert-hex-color-to-rgb-using-php/
     *
     * @param $hex
     * @return array
     */
    public static function hex2rgb($hex)
    {
        $hex = str_replace("#", "", $hex);

        if (strlen($hex) == 3) {
            $r = hexdec(substr($hex, 0, 1) . substr($hex, 0, 1));
            $g = hexdec(substr($hex, 1, 1) . substr($hex, 1, 1));
            $b = hexdec(substr($hex, 2, 1) . substr($hex, 2, 1));
        } else {
            $r = hexdec(substr($hex, 0, 2));
            $g = hexdec(substr($hex, 2, 2));
            $b = hexdec(substr($hex, 4, 2));
        }

        return [$r, $g, $b];
    }

    /**
     * @param $value
     * @return bool
     */
    public static function isValidHexColorCode($value)
    {
        return (bool) preg_match('/^#?([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/', $value);
    }
}