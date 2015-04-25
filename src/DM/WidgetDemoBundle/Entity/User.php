<?php

namespace DM\WidgetDemoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 *
 * @ORM\Table("user", uniqueConstraints={
 *  @ORM\UniqueConstraint(name="idx_hash", columns={"hash"})
 * }))
 * @ORM\Entity()
 */
class User
{
    const STATUS_ACTIVE = 'active';
    const STATUS_INACTIVE = 'inactive';
    const STATUSES = [self::STATUS_ACTIVE, self::STATUS_INACTIVE];

    /**
     * @var integer
     *
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     */
    private $hash;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=10)
     */
    private $status = self::STATUS_ACTIVE;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set hash
     *
     * @param string $hash
     * @return User
     */
    public function setHash($hash)
    {
        $this->hash = $hash;

        return $this;
    }

    /**
     * Get hash
     *
     * @return string
     */
    public function getHash()
    {
        return $this->hash;
    }

    /**
     * Set status
     *
     * @param string $status
     * @return User
     */
    public function setStatus($status)
    {
        if (!in_array($status, self::STATUSES)) {
            throw new \InvalidArgumentException(
                sprintf("%s is not in the list of valid statuses [%s]", $status, implode(',', self::STATUSES))
            );
        }
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }
}
