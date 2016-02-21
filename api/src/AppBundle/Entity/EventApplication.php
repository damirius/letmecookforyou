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
     * @Serializer\Groups({"list", "details"})
     */
    protected $applicant;

    /**
     * @var Message[]
     *
     * @ORM\OneToMany(targetEntity="Message", mappedBy="eventApplication")
     */
    protected $messages;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean")
     * @Serializer\Groups({"details"})
     */
    protected $hostConfirmed = false;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean")
     * @Serializer\Groups({"details"})
     */
    protected $guestConfirmed = false;

    public function __construct()
    {
        $this->messages = new ArrayCollection();
    }

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
     * Set hostConfirmed
     *
     * @param boolean $hostConfirmed
     *
     * @return EventApplication
     */
    public function setHostConfirmed($hostConfirmed)
    {
        $this->hostConfirmed = $hostConfirmed;

        return $this;
    }

    /**
     * Get hostConfirmed
     *
     * @return boolean
     */
    public function getHostConfirmed()
    {
        return $this->hostConfirmed;
    }

    /**
     * Set guestConfirmed
     *
     * @param boolean $guestConfirmed
     *
     * @return EventApplication
     */
    public function setGuestConfirmed($guestConfirmed)
    {
        $this->guestConfirmed = $guestConfirmed;

        return $this;
    }

    /**
     * Get guestConfirmed
     *
     * @return boolean
     */
    public function getGuestConfirmed()
    {
        return $this->guestConfirmed;
    }

    /**
     * Set event
     *
     * @param Event $event
     *
     * @return EventApplication
     */
    public function setEvent(Event $event = null)
    {
        $this->event = $event;

        return $this;
    }

    /**
     * Get event
     *
     * @return Event
     */
    public function getEvent()
    {
        return $this->event;
    }

    /**
     * Set applicant
     *
     * @param User $applicant
     *
     * @return EventApplication
     */
    public function setApplicant(User $applicant = null)
    {
        $this->applicant = $applicant;

        return $this;
    }

    /**
     * Get applicant
     *
     * @return User
     */
    public function getApplicant()
    {
        return $this->applicant;
    }

    /**
     * Add message
     *
     * @param Message $message
     *
     * @return EventApplication
     */
    public function addMessage(Message $message)
    {
        $this->messages[] = $message;

        return $this;
    }

    /**
     * Remove message
     *
     * @param Message $message
     */
    public function removeMessage(Message $message)
    {
        $this->messages->removeElement($message);
    }

    /**
     * Get messages
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMessages()
    {
        return $this->messages;
    }
}
