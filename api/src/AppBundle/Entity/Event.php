<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use JMS\Serializer\Annotation as Serializer;

/**
 * Event
 *
 * @ORM\Entity(repositoryClass="AppBundle\Repository\Event")
 */
class Event
{
    const PLACE_HOST = 1;
    const PLACE_GUEST = 2;
    const PLACE_OTHER = 3;

    const PAYS_HOST = 1;
    const PAYS_GUEST = 2;
    const PAYS_BOTH = 3;
    
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
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="User", inversedBy="eventsHosting")
     * @Serializer\Groups({"list", "details"})
     */
    protected $host;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     * @Serializer\Groups({"list", "details"})
     */
    protected $city;

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
     * @ORM\Column(type="string")
     * @Serializer\Groups({"list", "details"})
     */
    protected $mealName;

    /**
     * @var string
     *
     * @ORM\Column(type="text")
     * @Serializer\Groups({"list","details"})
     */
    protected $description;

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

    /**
     * @var Tag[]
     *
     * @ORM\ManyToMany(targetEntity="Tag", inversedBy="events")
     * @Serializer\Groups({"list", "details"})
     */
    protected $tags;

    /**
     * @var EventPicture[]
     *
     * @ORM\OneToMany(targetEntity="EventPicture", mappedBy="event")
     * @Serializer\Groups({"details"})
     */
    protected $gallery;

    /**
     * @var float
     *
     * @ORM\Column(type="decimal", nullable=true)
     * @Serializer\Groups({"details"})
     */
    protected $costEstimate = null;

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    protected $whosePlace;

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    protected $whoPays;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     * @Serializer\Groups({"list", "details"})
     */
    protected $when;

    /**
     * @var EventApplication[]
     *
     * @ORM\OneToMany(targetEntity="EventApplication", mappedBy="event")
     * @Serializer\Groups({"owner", "applicant"})
     */
    protected $applications;

    public function __construct()
    {
        $this->applications = new ArrayCollection();
        $this->tags = new ArrayCollection();
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
     * Set city
     *
     * @param string $city
     *
     * @return Event
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
     * Set mealName
     *
     * @param string $mealName
     *
     * @return Event
     */
    public function setMealName($mealName)
    {
        $this->mealName = $mealName;

        return $this;
    }

    /**
     * Get mealName
     *
     * @return string
     */
    public function getMealName()
    {
        return $this->mealName;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Event
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set latitude
     *
     * @param float $latitude
     *
     * @return Event
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
     * @return Event
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

    /**
     * Set costEstimate
     *
     * @param string $costEstimate
     *
     * @return Event
     */
    public function setCostEstimate($costEstimate)
    {
        $this->costEstimate = $costEstimate;

        return $this;
    }

    /**
     * Get costEstimate
     *
     * @return string
     */
    public function getCostEstimate()
    {
        return $this->costEstimate;
    }

    /**
     * Set whosePlace
     *
     * @param integer $whosePlace
     *
     * @return Event
     */
    public function setWhosePlace($whosePlace)
    {
        $this->whosePlace = $whosePlace;

        return $this;
    }

    /**
     * Get whosePlace
     *
     * @return integer
     */
    public function getWhosePlace()
    {
        return $this->whosePlace;
    }

    /**
     * Set whoPays
     *
     * @param integer $whoPays
     *
     * @return Event
     */
    public function setWhoPays($whoPays)
    {
        $this->whoPays = $whoPays;

        return $this;
    }

    /**
     * Get whoPays
     *
     * @return integer
     */
    public function getWhoPays()
    {
        return $this->whoPays;
    }

    /**
     * Set host
     *
     * @param User $host
     *
     * @return Event
     */
    public function setHost(User $host = null)
    {
        $this->host = $host;

        return $this;
    }

    /**
     * Get host
     *
     * @return User
     */
    public function getHost()
    {
        return $this->host;
    }

    /**
     * Set country
     *
     * @param Country $country
     *
     * @return Event
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
     * Add tag
     *
     * @param Tag $tag
     *
     * @return Event
     */
    public function addTag(Tag $tag)
    {
        $this->tags[] = $tag;

        return $this;
    }

    /**
     * Remove tag
     *
     * @param Tag $tag
     */
    public function removeTag(Tag $tag)
    {
        $this->tags->removeElement($tag);
    }

    /**
     * Get tags
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * Add application
     *
     * @param EventApplication $application
     *
     * @return Event
     */
    public function addApplication(EventApplication $application)
    {
        $this->applications[] = $application;

        return $this;
    }

    /**
     * Remove application
     *
     * @param EventApplication $application
     */
    public function removeApplication(EventApplication $application)
    {
        $this->applications->removeElement($application);
    }

    /**
     * Get applications
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getApplications()
    {
        return $this->applications;
    }

    /**
     * @return \DateTime
     */
    public function getWhen()
    {
        return $this->when;
    }

    /**
     * @param \DateTime $when
     * @return $this
     */
    public function setWhen($when)
    {
        $this->when = $when;

        return $this;
    }



    /**
     * Add gallery
     *
     * @param EventPicture $gallery
     *
     * @return Event
     */
    public function addGallery(EventPicture $gallery)
    {
        $this->gallery[] = $gallery;

        return $this;
    }

    /**
     * Remove gallery
     *
     * @param EventPicture $gallery
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeGallery(EventPicture $gallery)
    {
        return $this->gallery->removeElement($gallery);
    }

    /**
     * Get gallery
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getGallery()
    {
        return $this->gallery;
    }
}
