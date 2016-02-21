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
     * @Serializer\Groups({"details"})
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
     * @Serializer\Groups({"details"})
     */
    protected $tags;

    /**
     * @var float
     *
     * @ORM\Column(type="decimal")
     * @Serializer\Groups({"details"})
     */
    protected $costEstimate;

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
     * @var EventApplication[]
     *
     * @ORM\OneToMany(targetEntity="EventApplication", mappedBy="event")
     * @Serializer\Groups({"details"})
     */
    protected $applications;

    public function __construct()
    {
        $this->applications = new ArrayCollection();
        $this->tags = new ArrayCollection();
    }

}
