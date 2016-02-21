<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Event;
use AppBundle\Entity\User;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\View\View;
use JMS\Serializer\SerializationContext;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Response;

class EventController extends FOSRestController
{

    /**
     * @return View
     *
     * @Rest\Get("/events/hosting.{_format}")
     */
    public function getEventsHostingAction()
    {
        $view = $this->view()->setSerializationContext(SerializationContext::create()->setGroups(['details', 'owner']));

        $user = $this->getUser();

        if ($user instanceof User) {
            $view->setData($user->getEventsHosting());
        } else {
            $view->setStatusCode(Response::HTTP_UNAUTHORIZED);
        }

        return $view;
    }

    /**
     * @return View
     *
     * @Rest\Get("/events/attending.{_format}")
     */
    public function getEventsAttendingAction()
    {
        $view = $this->view()->setSerializationContext(SerializationContext::create()->setGroups(['details', 'owner']));

        $user = $this->getUser();

        if ($user instanceof User) {
            $view->setData($user->getEventsAttending());
        } else {
            $view->setStatusCode(Response::HTTP_UNAUTHORIZED);
        }

        return $view;
    }

    /**
     * @param $event_id
     * @return View
     * @Rest\Get("/events/{event_id}.{_format}")
     */
    public function getMyEventDetailsAction($event_id)
    {
        $view = $this->view()->setSerializationContext(SerializationContext::create()->setGroups(['details', 'owner']));

        $user = $this->getUser();

        if ($user instanceof User and filter_var($event_id, FILTER_VALIDATE_INT) !== false) {
            $event = $this->getDoctrine()->getRepository('AppBundle:Event')->find($event_id);

            if ($event instanceof Event) {
                $serializationGroups = ['details'];
                if ($event->getHost() == $user) {
                    $serializationGroups []= 'owner';
                }
                $view = $this->view()->setSerializationContext(SerializationContext::create()->setGroups($serializationGroups));
                $view->setData($event);
            } else {
                $view->setStatusCode(Response::HTTP_NOT_FOUND);
            }
        } else {
            $view->setStatusCode(Response::HTTP_UNAUTHORIZED);
        }

        return $view;
    }

}