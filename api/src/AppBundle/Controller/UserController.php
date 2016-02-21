<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\View\View;
use JMS\Serializer\SerializationContext;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Response;

class UserController extends FOSRestController
{

    /**
     * @return View
     *
     * @Rest\Get("/me.{_format}")
     */
    public function getCurrentUserAction()
    {
        $view = $this->view()->setSerializationContext(SerializationContext::create()->setGroups(['details', 'owner']));

        $user = $this->getUser();

        if ($user instanceof User) {
            $view->setData($user);
        } else {
            $view->setStatusCode(Response::HTTP_UNAUTHORIZED);
        }

        return $view;
    }

}