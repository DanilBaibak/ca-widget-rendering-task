<?php
require_once __DIR__.'/bootstrap.php.cache';
require_once __DIR__.'/TestAppKernel.php';

$kernel = new TestAppKernel('test', true);
$kernel->boot();
$kernel->recreateSchema();

$loadFixturesCmd = new \Doctrine\Bundle\FixturesBundle\Command\LoadDataFixturesDoctrineCommand();
$app = new \Symfony\Bundle\FrameworkBundle\Console\Application($kernel);
$app->add($loadFixturesCmd);

$input = new \Symfony\Component\Console\Input\ArrayInput(array(
    'command' => 'doctrine:fixtures:load'
));

$input->setInteractive(false);
$loadFixturesCmd->run($input, new \Symfony\Component\Console\Output\ConsoleOutput());
$kernel->shutdown();