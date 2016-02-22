<?php

namespace AppBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\View\View;
use JMS\Serializer\SerializationContext;
use FOS\RestBundle\Controller\Annotations as Rest;

class LocationController extends FOSRestController
{
    /**
     * @return View
     *
     * @Rest\Get("/countries.{_format}")
     */
    public function getCountriesAction()
    {
        $view = $this->view($this->getDoctrine()->getRepository('AppBundle:Country')->findAll())->setSerializationContext(SerializationContext::create()->setGroups(['list']));

        return $view;
    }
}