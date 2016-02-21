<?php

namespace AppBundle\Controller;

use AppBundle\Entity\EventApplication;
use AppBundle\Entity\Message;
use AppBundle\Entity\User;
use Exception;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\View\View;
use JMS\Serializer\SerializationContext;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class MessageController extends FOSRestController
{
    /**
     * @param $event_id
     * @param $application_id
     * @return View
     * @Rest\Get("/events/{event_id}/applications/{application_id}/messages.{_format}")
     */
    public function getEventApplicationMessageThreadAction($event_id, $application_id)
    {
        $view = $this->view();

        $user = $this->getUser();

        if ($user instanceof User
            and filter_var($event_id, FILTER_VALIDATE_INT) !== false
            and filter_var($application_id, FILTER_VALIDATE_INT) !== false) {

            $application = $this->getDoctrine()->getRepository('AppBundle:EventApplication')->findOneBy([
                'id' =>$application_id,
                'event' => $this->getDoctrine()->getEntityManager()->getReference('AppBundle\Entity\Event', $event_id)
            ]);

            if ($application instanceof EventApplication and
                ($user == $application->getApplicant() or $user == $application->getEvent()->getHost())) {

                $serializationGroups = ['details'];
                $view->setSerializationContext(SerializationContext::create()->setGroups($serializationGroups));
                $view->setData($application->getMessages());

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
     * @param $application_id
     * @return View
     * @Rest\Post("/events/{event_id}/applications/{application_id}/messages.{_format}")
     */
    public function postNewMessageToEventApplicationThreadAction(Request $request, $event_id, $application_id)
    {
        $view = $this->view()->setSerializationContext(SerializationContext::create()->setGroups(['details', 'owner']));

        $user = $this->getUser();

        $em = $this->getDoctrine()->getEntityManager();

        if ($user instanceof User
            and filter_var($event_id, FILTER_VALIDATE_INT) !== false
            and filter_var($application_id, FILTER_VALIDATE_INT) !== false) {

            $application = $em->getRepository('AppBundle:EventApplication')->findOneBy([
                'id' =>$application_id,
                'event' => $em->getReference('AppBundle\Entity\Event', $event_id)
            ]);

            if ($application instanceof EventApplication and
                ($user == $application->getApplicant() or $user == $application->getEvent()->getHost())) {

                $message = new Message();

                $message->setContent($request->get('message'));
                $message->setEventApplication($application);
                $message->setSender($user);

                $application->addMessage($message);

                try {
                    $em->persist($message);
                    $em->flush();
                    $view->setStatusCode(Response::HTTP_CREATED);
                } catch (Exception $e) {
                    $view->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
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