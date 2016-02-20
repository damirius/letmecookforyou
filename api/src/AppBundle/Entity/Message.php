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
}