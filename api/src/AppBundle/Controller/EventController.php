<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Country;
use AppBundle\Entity\Event;
use AppBundle\Entity\EventApplication;
use AppBundle\Entity\EventPicture;
use AppBundle\Entity\Tag;
use AppBundle\Entity\User;
use AppBundle\Location\LocationSearchParameters;
use Doctrine\Common\Persistence\ObjectManager;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\View\View;
use JMS\Serializer\SerializationContext;
use FOS\RestBundle\Controller\Annotations as Rest;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\File\UploadedFile;
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

        $events = $this->getDoctrine()->getRepository('AppBundle:Event')->findBy([], ['when' => 'ASC'], $limit, $offset);

        $view->setData($events);

        return $view;
    }

    /**
     * @param Request $request
     * @return View
     * @Rest\Get("/events/search.{_format}")
     */
    public function searchEventsAction(Request $request)
    {
        $view = $this->view()->setSerializationContext(SerializationContext::create()->setGroups(['list']));

        $offset = $request->query->getInt('offset', 0);
        $limit = $request->query->getInt('limit', 10);
        $whoPays = $request->query->getInt('who_pays', null);
        $whosePlace = $request->query->getInt('whose_place', null);
        $time = $request->query->get('time', null);
        $tags = $request->query->get('tags', null);
        if ($tags) {
            $tags = explode(',', $tags);
        }

        $geolocationApi = $this->get('google_geolocation.geolocation_api');
        $location = $geolocationApi->locateAddress($request->query->get('location'));

        if ($location->getMatches() > 0)
        {
            $coordinates = $location->getLatLng(0);

            $locationParams = new LocationSearchParameters();
            $locationParams->setLatitude($coordinates['lat']);
            $locationParams->setLongitude($coordinates['lng']);
            $locationParams->setRadius($request->query->get('radius', 100));
            $locationParams->setUnit($request->query->get('unit') == 'miles' ? LocationSearchParameters::UNIT_MILE : LocationSearchParameters::UNIT_KM);
            $events = $this->getDoctrine()->getRepository('AppBundle:Event')->searchWithLocation($locationParams, $time, $tags, $whoPays, $whosePlace, $limit, $offset);
        } else {
            $events = $this->getDoctrine()->getRepository('AppBundle:Event')->search($time, $tags, $whoPays, $whosePlace, $limit, $offset);
        }


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

            $when = $request->request->get('when');
            if ($when) {
                $when = new \DateTime($when);
                $event->setWhen($when);
            } else {
                $errors []= 'You must enter date and time of the event.';
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
                $tag = $em->getRepository('AppBundle:Tag')->findOneBy([
                   'name' => $tagName
                ]);
                if (!($tag instanceof Tag)) {
                    $tag = new Tag();
                    $tag->setName($tagName);
                    $em->persist($tag);
                }
                $event->addTag($tag);
            }

            $geolocationApi = $this->get('google_geolocation.geolocation_api');
            $location = $geolocationApi->locateAddress($city . ', ' . $country->getName());

            if ($location->getMatches() > 0)
            {
                $coordinates = $location->getLatLng(0);
                $event->setLatitude($coordinates['lat']);
                $event->setLongitude($coordinates['lng']);
            } else {
                $errors []= 'Location not found.';
            }

            if (count($errors) > 0) {
                $view->setData($errors)->setStatusCode(Response::HTTP_BAD_REQUEST);
            }
            try {
                $em->persist($event);
                $em->flush();
                $view->setStatusCode(Response::HTTP_CREATED);
            } catch (\Exception $e) {
                $view->setData($e->getMessage());
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
        $view = $this->view()->setSerializationContext(SerializationContext::create()->setGroups(['list', 'owner']));

        $user = $this->getUser();

        if ($user instanceof User) {
            $view->setData($this->getDoctrine()->getRepository('AppBundle:Event')->findEventsHostedBy($user));
        } else {
            $view->setStatusCode(Response::HTTP_UNAUTHORIZED);
        }

        return $view;
    }

    /**
     * @return View
     *
     * @Rest\Get("/events/applied.{_format}")
     */
    public function getEventsAppliedAction()
    {
        $view = $this->view()->setSerializationContext(SerializationContext::create()->setGroups(['list', 'applicant']));

        $user = $this->getUser();

        if ($user instanceof User) {
            $events = $this->getDoctrine()->getRepository('AppBundle:Event')->findEventsAppliedBy($user);
            foreach ($events as $event) {
                foreach ($event->getApplications() as $application) {
                    if ($application->getApplicant() != $user) {
                        $event->removeApplication($application);
                    }
                }
            }
            $view->setData($events);
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
        $view = $this->view()->setSerializationContext(SerializationContext::create()->setGroups(['list', 'applicant']));

        $user = $this->getUser();

        if ($user instanceof User) {
            $events = $this->getDoctrine()->getRepository('AppBundle:Event')->findEventsAttending($user);
            foreach ($events as $event) {
                foreach ($event->getApplications() as $application) {
                    if ($application->getApplicant() != $user) {
                        $event->removeApplication($application);
                    }
                }
            }
            $view->setData($events);
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

        if (filter_var($event_id, FILTER_VALIDATE_INT) !== false) {
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


    /**
     * @param Request $request
     * @param $event_id
     * @return View
     * @Rest\Post("/events/{event_id}/gallery.{_format}")
     */
    public function addEventPictureAction(Request $request, $event_id)
    {
        $view = $this->view();

        $filesystem = $this->get('knp_gaufrette.filesystem_map')->get('event_pictures');
        $content = $request->getContent();

        $user = $this->getUser();

        if (filter_var($event_id, FILTER_VALIDATE_INT) !== false and $user instanceof User) {
            $event = $this->getDoctrine()->getRepository('AppBundle:Event')->find($event_id);

            if ($event) {
                if ($event->getHost() == $user) {
                    $file = tmpfile();
                    $path = stream_get_meta_data($file)['uri'];
                    file_put_contents($path, $content);
                    $uploadedFile = new UploadedFile($path, $path, null, null, null, true);

                    $uuid = Uuid::uuid4()->toString();
                    $ext = $uploadedFile->guessExtension();
                    $filename = "{$uuid}.{$ext}";

                    if ($filesystem->write($filename, $content) > 0) {
                        $picture = new EventPicture();
                        $picture->setEvent($event);
                        $picture->setName($filename);
                        $this->getDoctrine()->getManager()->persist($picture);
                        $this->getDoctrine()->getManager()->flush();
                    }

                    $view->setStatusCode(Response::HTTP_CREATED)
                        ->setHeader('Location', "/api/uploads/event/{$filename}");
                } else {
                    $view->setStatusCode(Response::HTTP_FORBIDDEN);
                }
            } else {
                $view->setStatusCode(Response::HTTP_NOT_FOUND);
            }
        } else {
            $view->setStatusCode(Response::HTTP_FORBIDDEN);
        }

        return $view;
    }

    /**
     * @param $event_id
     * @param $application_id
     * @return View
     * @Rest\Get("/events/{event_id}/applications/{application_id}/confirm.{_format}")
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

            if ($application instanceof EventApplication) {
                if ($user == $application->getApplicant()) {
                    $application->setGuestConfirmed(true);
                } elseif ($user == $application->getEvent()->getHost()) {
                    $application->setHostConfirmed(true);
                } else {
                    $view->setStatusCode(Response::HTTP_UNAUTHORIZED);
                }
                $this->getDoctrine()->getManager()->flush();
            } else {
                $view->setStatusCode(Response::HTTP_NOT_FOUND);
            }
        } else {
            $view->setStatusCode(Response::HTTP_UNAUTHORIZED);
        }

        return $view;
    }
}