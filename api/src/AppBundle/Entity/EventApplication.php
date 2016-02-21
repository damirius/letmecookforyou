<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use JMS\Serializer\Annotation as Serializer;

/**
 * EventApplication
 *
 * @ORM\Entity(repositoryClass="AppBundle\Repository\Event")
 */
class EventApplication
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
     * @var Event
     *
     * @ORM\ManyToOne(targetEntity="Event", inversedBy="applications")
     * @Serializer\Groups({"owner"})
     */
    protected $event;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="User", inversedBy="eventsAttending")
     */
    protected $applicant;

    /**
     * @var Message[]
     *
     * @ORM\OneToMany(targetEntity="Message", mappedBy="eventApplication")
     * @Serializer\Groups({"list", "details"})
     */
    protected $messages;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean")
     * @Serializer\Groups({"details"})
     */
    protected $hostConfirmed;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean")
     * @Serializer\Groups({"details"})
     */
    protected $guestConfirmed;

    public function __construct()
    {
        $this->messages = new ArrayCollection();
    }
}