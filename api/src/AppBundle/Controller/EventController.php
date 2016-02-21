<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Event;
use AppBundle\Entity\EventApplication;
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
                $view->setSerializationContext(SerializationContext::create()->setGroups($serializationGroups));
                $view->setData($event);
            } else {
                $view->setStatusCode(Response::HTTP_NOT_FOUND);
            }
        } else {
            $view->setStatusCode(Response::HTTP_UNAUTHORIZED);
        }

        return $view;
    }

    /**
     * @param $event_id
     * @return View
     * @Rest\Post("/events/{event_id}/apply.{_format}")
     */
    public function applyForEventAction($event_id)
    {
        $view = $this->view();

        $user = $this->getUser();

        if ($user instanceof User and filter_var($event_id, FILTER_VALIDATE_INT) !== false) {
            $em = $this->getDoctrine()->getManager();

            $event = $em->getRepository('AppBundle:Event')->find($event_id);

            $application = $em->getRepository('AppBundle:EventApplication')->findOneBy([
                'event' => $event,
                'applicant' => $user
            ]);

            if ($event instanceof Event) {
                if ($application instanceof EventApplication) {
                    $view->setStatusCode(Response::HTTP_CREATED);
                } elseif ($event->getHost() == $user) {
                    $view->setStatusCode(Response::HTTP_UNPROCESSABLE_ENTITY);
                } else {
                    $application = new EventApplication();
                    $application->setEvent($event);
                    $application->setApplicant($user);

                    try {
                        $em->persist($application);
                        $em->flush();
                        $view->setStatusCode(Response::HTTP_CREATED);
                    } catch (\Exception $e) {
                        $view->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
                    }
                }
            } else {
                $view->setStatusCode(Response::HTTP_NOT_FOUND);
            }
        } else {
            $view->setStatusCode(Response::HTTP_UNAUTHORIZED);
        }

        return $view;
    }

}