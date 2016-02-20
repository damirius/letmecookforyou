<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use JMS\Serializer\Annotation as Serializer;

/**
 * Tag
 *
 * @ORM\Entity(repositoryClass="AppBundle\Repository\Event")
 */
class Tag
{
    use TimestampableEntity;

    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Serializer\Groups({"list", "details"})
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     * @Serializer\Groups({"list", "details"})
     */
    protected $name;

    /**
     * @var Event[]
     *
     * @ORM\ManyToMany(targetEntity="Event", mappedBy="tags")
     */
    protected $events;

    public function __construct()
    {
        $this->events = new ArrayCollection();
    }
}