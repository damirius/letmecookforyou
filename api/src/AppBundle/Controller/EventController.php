<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Country;
use AppBundle\Entity\Event;
use AppBundle\Entity\EventApplication;
use AppBundle\Entity\Tag;
use AppBundle\Entity\User;
use Doctrine\Common\Persistence\ObjectManager;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\View\View;
use JMS\Serializer\SerializationContext;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class EventController extends FOSRestController
{

    /**
     * @param Request $request
     * @return View
     * @Rest\Get("/events.{_format}")
     */
    public function getEventsAction(Request $request)
    {
        $view = $this->view()->setSerializationContext(SerializationContext::create()->setGroups(['list']));

        $offset = $request->query->getInt('offset', 0);
        $limit = $request->query->getInt('limit', 10);

        $events = $this->getDoctrine()->getRepository('AppBundle:Event')->findBy([], ['createdAt' => 'DESC'], $limit, $offset);

        $view->setData($events);

        return $view;
    }

    /**
     * @param Request $request
     * @return View
     * @Rest\Post("/events.{_format}")
     */
    public function createEventAction(Request $request)
    {
        $view = $this->view()->setSerializationContext(SerializationContext::create()->setGroups(['list']));

        $user = $this->getUser();
        
        /** @var ObjectManager $em */
        $em = $this->getDoctrine()->getManager();
        
        $errors = [];
        if ($user instanceof User) {
            $event = new Event();

            $event->setHost($user);
            
            $city = $request->request->getAlpha('city');
            if ($city) {
                $event->setCity($city);
            } else {
                $errors []= 'You must specify a city in which will event be held.';
            }
            
            $country = $em->getRepository('AppBundle:Country')->find($request->request->getAlpha('country'));
            if ($country instanceof Country) {
                $event->setCountry($country);
            } else {
                $errors []= 'You must specify a country in which will event be held.';
            }

            $costEstimate = $request->request->get('cost_estimate', null);
            if ($costEstimate) {
                $event->setCostEstimate($costEstimate);
            }

            $mealName = $request->request->get('meal_name');
            if ($mealName) {
                $event->setMealName($mealName);
            } else {
                $errors []= 'You must specify meal name (title).';
            }

            $description = $request->request->get('description');
            if ($description) {
                $event->setDescription($description);
            } else {
                $errors []= 'You must enter description of the event.';
            }

            $whosePlace = $request->request->getInt('whose_place');
            if ($whosePlace == Event::PLACE_GUEST or $whosePlace == Event::PLACE_HOST or $whosePlace == Event::PLACE_OTHER) {
                $event->setWhosePlace($whosePlace);
            } else {
                $errors []= "You must enter at whose place event will be held:\n1 - Host\n2 - Guest\n3 - Other";
            }

            $whoPays = $request->request->getInt('who_pays');
            if ($whoPays == Event::PAYS_HOST or $whoPays == Event::PAYS_GUEST or $whoPays == Event::PAYS_BOTH) {
                $event->setWhoPays($whoPays);
            } else {
                $errors []= "You must enter at who pays for the event:\n1 - Host\n2 - Guest\n3 - Split bill";
            }

            $tagNames = $request->request->get('tags');
            foreach ($tagNames as $tagName) {
                $tag = $em->getRepository('AppBundle:Tag')->findBy([
                   'name' => $tagName
                ]);
                if (!($tag instanceof Tag)) {
                    $tag = new Tag();
                    $tag->setName($tagName);
                    $em->persist($tag);
                }
                $event->addTag($tag);
            }

            if (count($errors) > 0) {
                $view->setData($errors)->setStatusCode(Response::HTTP_BAD_REQUEST);
            }
            try {
                $em->persist($event);
                $em->flush();
            } catch (\Exception $e) {
                $view->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        }

        return $view;
    }

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