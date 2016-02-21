<?php

namespace Irenicus\MinecraftWorldBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\User;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadUserData implements FixtureInterface, ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * Sets the Container.
     *
     * @param ContainerInterface|null $container A ContainerInterface instance or null
     *
     * @api
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    function load(ObjectManager $manager)
    {
        $userManager = $this->container->get("fos_user.user_manager");

        /** @var User $user */
        $user = $userManager->createUser();
        $user->setUsername("admin");
        $user->setEmail("admin@localhost");
        $user->setPlainPassword("admin");
        $user->setEnabled(true);
        $user->setSuperAdmin(true);

        $userManager->updateUser($user);

        /** @var User $user */
        $user = $userManager->createUser();
        $user->setUsername("demo");
        $user->setEmail("demo@localhost");
        $user->setPlainPassword("demo");
        $user->setEnabled(true);

        $userManager->updateUser($user);
    }
}