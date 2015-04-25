<?php

namespace DM\WidgetDemoBundle\Services;

use DM\WidgetDemoBundle\Entity\User;
use DM\WidgetDemoBundle\Utils\ColorUtils;
use Symfony\Component\OptionsResolver\OptionsResolver;

class WidgetRenderer
{
    /**
     * @var OptionsResolver
     */
    private $optionsResolver;

    /**
     * @var UserRatingFetcher
     */
    private $userRatingFetcher;

    /**
     * @var string
     */
    private $fontPath;

    function __construct(UserRatingFetcher $userRatingFetcher, $fontPath)
    {
        $this->optionsResolver = $this->configureOptionsResolver();
        $this->userRatingFetcher = $userRatingFetcher;
        $this->fontPath = $fontPath;
    }

    /**
     * @return OptionsResolver
     */
    private function configureOptionsResolver()
    {
        $isValidIntBetween = function ($value, $min, $max) {
            return (is_int($value) || preg_match('/^[\d]+$/', $value)) && $value >= $min && $value <= $max;
        };

        return (new OptionsResolver())
            ->setDefaults([
                'width' => 100,
                'height' => 100,
                'background_color' => '000000',
                'text_color' => 'FFFFFF',
            ])
            ->setAllowedValues([
                'width' => function ($value) use ($isValidIntBetween) {
                    return $isValidIntBetween($value, 100, 500);
                },
                'height' => function ($value) use ($isValidIntBetween) {
                    return $isValidIntBetween($value, 100, 500);
                },
                'background_color' => function ($value) {
                    return ColorUtils::isValidHexColorCode($value);
                },
                'text_color' => function ($value) {
                    return ColorUtils::isValidHexColorCode($value);
                },
            ]);
    }

    /**
     * @param $width
     * @param $height
     * @return int
     */
    private function getFontSize($width, $height)
    {
        return (int)(min($width, $height) * 0.15);
    }

    /**
     * @param $fontSize
     * @param $width
     * @param $height
     * @return int[]
     */
    private function getTextPosition($text, $fontSize, $width, $height)
    {
        $tbb = imagettfbbox($fontSize, 0, $this->fontPath, $text);

        $textWidth = $tbb[4] - $tbb[0];
        $textHeight = $tbb[1] - $tbb[5];

        return [
            (int)(floor($width / 2.0) - floor($textWidth / 2.0)),
            (int)(floor($height / 2.0) + floor($textHeight / 2.0)),
        ];
    }

    /**
     * @param string $text
     * @param array $options
     * @return string
     * @throws \Exception
     */
    private function getImageContent($text, array $options)
    {
        $img = imagecreatetruecolor($options['width'], $options['height']);

        if ($img === false) {
            throw new \Exception('Cannot initialize new image');
        }

        $backgroundRgb = ColorUtils::hex2rgb($options['background_color']);
        $backgroundColor = imagecolorallocate($img, $backgroundRgb[0], $backgroundRgb[1], $backgroundRgb[2]);
        imagefill($img, 0, 0, $backgroundColor);

        $textRgb = ColorUtils::hex2rgb($options['text_color']);
        $textColor = imagecolorallocate($img, $textRgb[0], $textRgb[1], $textRgb[2]);
        $fontSize = $this->getFontSize($options['width'], $options['height']);
        $textPosition = $this->getTextPosition($text, $fontSize, $options['width'], $options['height']);

        imagettftext($img, $fontSize, 0, $textPosition[0], $textPosition[1], $textColor, $this->fontPath, $text);

        ob_start();
        imagepng($img);
        imagecolordeallocate($img, $backgroundColor);
        imagecolordeallocate($img, $textColor);
        imagedestroy($img);

        return ob_get_clean();
    }

    /**
     * @param User $user
     * @param array $options
     * @return string
     */
    public function render(User $user, array $options)
    {
        return $this->getImageContent(
            $this->userRatingFetcher->fetch($user) . '%',
            $this->optionsResolver->resolve($options)
        );
    }

    /**
     * @return string[]
     */
    public function getDefinedOptions()
    {
        return $this->optionsResolver->getDefinedOptions();
    }
}