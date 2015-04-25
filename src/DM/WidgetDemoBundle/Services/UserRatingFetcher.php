<?php
namespace DM\WidgetDemoBundle\Services;

use DM\WidgetDemoBundle\Entity\User;

class UserRatingFetcher
{
    /**
     * @param User $user
     * @return int
     */
    public function fetch(User $user)
    {
        return rand(1,100);
    }
}