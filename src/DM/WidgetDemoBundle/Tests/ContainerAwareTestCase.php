<?php

namespace DM\WidgetDemoBundle\Tests;

use Symfony\Component\DependencyInjection\ContainerInterface;

class ContainerAwareTestCase extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    public function setUp()
    {
        $kernel = new \TestAppKernel('test', true);
        $kernel->boot();
        $this->container = $kernel->getContainer();
        \TestAppKernel::beginTransaction();
    }

    public function tearDown()
    {
        \TestAppKernel::rollBack();
    }
}