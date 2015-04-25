<?php
namespace DM\WidgetDemoBundle\DataFixtures\ORM;

use DM\WidgetDemoBundle\Entity\User;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class UserFixtures implements FixtureInterface
{
    const HASH_ACTIVE = 'abcde';
    const HASH_INACTIVE = 'edcba';

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $activeUser = new User();
        $activeUser->setHash(self::HASH_ACTIVE);
        $activeUser->setStatus(User::STATUS_ACTIVE);
        $manager->persist($activeUser);

        $inActiveUser = new User();
        $inActiveUser->setHash(self::HASH_INACTIVE);
        $inActiveUser->setStatus(User::STATUS_INACTIVE);
        $manager->persist($inActiveUser);

        $manager->flush();
    }
}