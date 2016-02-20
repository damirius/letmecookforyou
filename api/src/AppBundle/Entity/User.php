<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints\Country;

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
     * @Serializer\Groups({"owner"})
     */
    protected $eventsHosting;

    /**
     * @var EventApplication[]
     *
     * @ORM\OneToMany(targetEntity="EventApplication", mappedBy="applicant")
     * @Serializer\Groups({"owner"})
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
     * @ORM\Column(type="string")
     * @Serializer\Groups({"list", "details"})
     */
    protected $city;

    public function __construct()
    {
        parent::__construct();
        $this->eventsAttending = new ArrayCollection();
        $this->eventsHosting = new ArrayCollection();
    }
}
