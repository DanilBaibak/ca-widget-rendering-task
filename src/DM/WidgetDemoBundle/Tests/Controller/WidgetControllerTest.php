<?php

namespace DM\WidgetDemoBundle\Tests\Controller;

use DM\BaseTestBundle\Kernel\TestAppKernel;
use DM\BaseTestBundle\Tests\BaseWebTestCase;
use DM\WidgetDemoBundle\DataFixtures\ORM\UserFixtures;
use DM\WidgetDemoBundle\Entity\User;
use DM\WidgetDemoBundle\Services\WidgetRenderer;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Component\OptionsResolver\Exception\InvalidOptionsException;

class WidgetControllerTest extends BaseWebTestCase
{
    /**
     * @var Client
     */
    private $client;

    /**
     * @var WidgetRenderer|\PHPUnit_Framework_MockObject_MockObject
     */
    private $widgetRendererMock;

    public function setUp()
    {
        $this->client = $this->createClient();
        $this->widgetRendererMock = $this
            ->getMockBuilder(WidgetRenderer::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;

        /** @var $kernel TestAppKernel */
        $kernel = $this->client->getKernel();
        $kernel->setService('dm_widget_demo.widget_renderer', $this->widgetRendererMock);

        parent::setUp();
    }

    /**
     * @param $hash
     * @return User|null
     */
    private function findUserByHash($hash)
    {
        return $this->client
            ->getContainer()
            ->get('doctrine')
            ->getRepository('DMWidgetDemoBundle:User')
            ->findOneByHash($hash)
        ;
    }

    public function testRenderReturns404ForNonExistingUser()
    {
        $this->client->request('GET', '/widget/non_existing_hash');
        $this->assertResponseStatusCode(404, $this->client);
    }

    public function testRenderReturns404ForInactiveUser()
    {
        $this->client->request('GET', '/widget/'.UserFixtures::HASH_INACTIVE);
        $this->assertResponseStatusCode(404, $this->client);
    }

    public function testRenderReturns400ForInvalidOptionsAndActiveUser()
    {
        $this->widgetRendererMock
            ->expects($this->once())
            ->method('getDefinedOptions')
            ->willReturn([])
        ;

        $this->widgetRendererMock
            ->expects($this->once())
            ->method('render')
            ->willThrowException(new InvalidOptionsException())
        ;

        $this->client->request('GET', '/widget/'.UserFixtures::HASH_ACTIVE);
        $this->assertResponseStatusCode(400, $this->client);
    }

    public function testRenderPassesUserAndDefinedOptionsToRendererForActiveUser()
    {
        $this->widgetRendererMock
            ->expects($this->once())
            ->method('getDefinedOptions')
            ->willReturn(['defined'])
        ;

        $user = $this->findUserByHash(UserFixtures::HASH_ACTIVE);

        $this->widgetRendererMock
            ->expects($this->once())
            ->method('render')
            ->with(
                $this->callback(function(User $u) use ($user) { return $u->getId() == $user->getId(); }),
                $this->equalTo(['defined' => 100])
            )
        ;

        $this->client->request('GET', '/widget/'.UserFixtures::HASH_ACTIVE, ['defined' => 100, 'undefined' => 200]);
    }

    public function testRenderReturnsPngImageDataFromRendererForActiveUser()
    {
        $this->widgetRendererMock
            ->expects($this->once())
            ->method('getDefinedOptions')
            ->willReturn([])
        ;

        $this->widgetRendererMock
            ->expects($this->once())
            ->method('render')
            ->willReturn("awesome_image_content");
        ;

        $this->client->request('GET', '/widget/'.UserFixtures::HASH_ACTIVE);
        $this->assertResponseStatusCode(200, $this->client);
        $this->assertEquals('image/png', $this->client->getResponse()->headers->get('Content-Type'));
        $this->assertEquals('awesome_image_content', $this->client->getResponse()->getContent());
    }
}
