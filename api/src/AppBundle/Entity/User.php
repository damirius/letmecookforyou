<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use JMS\Serializer\Annotation as Serializer;

/**
 * User
 *
 * @ORM\Entity(repositoryClass="AppBundle\Repository\User")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 */
class User extends BaseUser
{
    use TimestampableEntity;
    use SoftDeleteableEntity;

    /**
     * @var integer
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Serializer\Groups({"list", "details"})
     */
    protected $id;

    /**
     * @var Event[]
     *
     * @ORM\OneToMany(targetEntity="Event", mappedBy="host")
     */
    protected $eventsHosting;

    /**
     * @var EventApplication[]
     *
     * @ORM\OneToMany(targetEntity="EventApplication", mappedBy="applicant")
     */
    protected $eventsAttending;

    /**
     * @var Country
     *
     * @ORM\ManyToOne(targetEntity="Country")
     * @Serializer\Groups({"list", "details"})
     */
    protected $country;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     * @Serializer\Groups({"list", "details"})
     */
    protected $city;

    /**
     * @var float
     *
     * @ORM\Column(type="float")
     */
    protected $latitude;

    /**
     * @var float
     *
     * @ORM\Column(type="float")
     */
    protected $longitude;

    public function __construct()
    {
        parent::__construct();
        $this->eventsAttending = new ArrayCollection();
        $this->eventsHosting = new ArrayCollection();
    }

    /**
     * Set city
     *
     * @param string $city
     *
     * @return User
     */
    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get city
     *
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Add eventsHosting
     *
     * @param Event $eventsHosting
     *
     * @return User
     */
    public function addEventsHosting(Event $eventsHosting)
    {
        $this->eventsHosting[] = $eventsHosting;

        return $this;
    }

    /**
     * Remove eventsHosting
     *
     * @param Event $eventsHosting
     */
    public function removeEventsHosting(Event $eventsHosting)
    {
        $this->eventsHosting->removeElement($eventsHosting);
    }

    /**
     * Get eventsHosting
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEventsHosting()
    {
        return $this->eventsHosting;
    }

    /**
     * Add eventsAttending
     *
     * @param EventApplication $eventsAttending
     *
     * @return User
     */
    public function addEventsAttending(EventApplication $eventsAttending)
    {
        $this->eventsAttending[] = $eventsAttending;

        return $this;
    }

    /**
     * Remove eventsAttending
     *
     * @param EventApplication $eventsAttending
     */
    public function removeEventsAttending(EventApplication $eventsAttending)
    {
        $this->eventsAttending->removeElement($eventsAttending);
    }

    /**
     * Get eventsAttending
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEventsAttending()
    {
        return $this->eventsAttending;
    }

    /**
     * Set country
     *
     * @param Country $country
     *
     * @return User
     */
    public function setCountry(Country $country = null)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * Get country
     *
     * @return Country
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Set latitude
     *
     * @param float $latitude
     *
     * @return User
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;

        return $this;
    }

    /**
     * Get latitude
     *
     * @return float
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * Set longitude
     *
     * @param float $longitude
     *
     * @return User
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;

        return $this;
    }

    /**
     * Get longitude
     *
     * @return float
     */
    public function getLongitude()
    {
        return $this->longitude;
    }
}
