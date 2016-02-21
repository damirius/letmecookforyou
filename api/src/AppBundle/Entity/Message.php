<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use JMS\Serializer\Annotation as Serializer;

/**
 * Message
 *
 * @ORM\Entity(repositoryClass="AppBundle\Repository\Event")
 */
class Message
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
     * @var EventApplication
     *
     * @ORM\ManyToOne(targetEntity="EventApplication", inversedBy="messages")
     */
    protected $eventApplication;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @Serializer\Groups({"list", "details"})
     */
    protected $sender;

    /**
     * @var string
     *
     * @ORM\Column(type="text")
     * @Serializer\Groups({"list", "details"})
     */
    protected $content;

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
     * Set content
     *
     * @param string $content
     *
     * @return Message
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set eventApplication
     *
     * @param EventApplication $eventApplication
     *
     * @return Message
     */
    public function setEventApplication(EventApplication $eventApplication = null)
    {
        $this->eventApplication = $eventApplication;

        return $this;
    }

    /**
     * Get eventApplication
     *
     * @return EventApplication
     */
    public function getEventApplication()
    {
        return $this->eventApplication;
    }

    /**
     * Set sender
     *
     * @param User $sender
     *
     * @return Message
     */
    public function setSender(User $sender = null)
    {
        $this->sender = $sender;

        return $this;
    }

    /**
     * Get sender
     *
     * @return User
     */
    public function getSender()
    {
        return $this->sender;
    }
}
