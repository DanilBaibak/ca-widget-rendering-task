<?php
namespace DM\WidgetDemoBundle\Tests\Services;

use DM\WidgetDemoBundle\Entity\User;
use DM\WidgetDemoBundle\Services\WidgetRenderer;
use DM\WidgetDemoBundle\Tests\ContainerAwareTestCase;
use DM\WidgetDemoBundle\Utils\ColorUtils;

class WidgetRendererTest extends ContainerAwareTestCase
{
    /**
     * @var WidgetRenderer
     */
    private $renderer;

    public function setUp()
    {
        parent::setUp();
        $this->renderer = $this->container->get('dm_widget_demo.widget_renderer');
    }

    /**
     * @param $image
     * @param $hexColor
     * @param $x
     * @param $y
     */
    private function assertImageColorAt($image, $hexColor, $x, $y)
    {
        $imgRes = imagecreatefromstring($image);
        $rgb = imagecolorat($imgRes, $x, $y);
        $r = ($rgb >> 16) & 0xFF;
        $g = ($rgb >> 8) & 0xFF;
        $b = $rgb & 0xFF;

        $this->assertEquals([$r, $g, $b], ColorUtils::hex2rgb($hexColor));
    }

    /**
     * @param $image
     * @param array $options
     */
    private function assertImage($image, array $options)
    {
        $imgInfo = getimagesizefromstring($image);
        $this->assertEquals('image/png', $imgInfo['mime']);
        $this->assertEquals($options['width'], $imgInfo[0]);
        $this->assertEquals($options['height'], $imgInfo[1]);
        $this->assertImageColorAt($image, $options['background_color'], 0, 0);
    }

    /**
     * @dataProvider renderDataProvider
     */
    public function testRender(array $rendererOptions, array $expectedOptions)
    {
        $this->assertImage($this->renderer->render($this->getMock(User::class), $rendererOptions), $expectedOptions);
    }

    public function renderDataProvider()
    {
        return [
            [[], ['width' => 100, 'height' => 100, 'background_color' => '000000']],
            [['width' => 300], ['width' => 300, 'height' => 100, 'background_color' => '000000']],
            [['height' => 300], ['width' => 100, 'height' => 300, 'background_color' => '000000']],
            [['background_color' => 'af0055'], ['width' => 100, 'height' => 100, 'background_color' => 'af0055']],
        ];
    }
}