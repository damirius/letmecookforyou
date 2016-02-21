<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\View\View;
use JMS\Serializer\SerializationContext;
use FOS\RestBundle\Controller\Annotations as Rest;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
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

    /**
     * @param Request $request
     * @return View
     * @Rest\Put("/me/profile-picture.{_format}")
     */
    public function setProfilePictureAction(Request $request)
    {
        $view = $this->view();

        $filesystem = $this->get('knp_gaufrette.filesystem_map')->get('profile_pictures');
        $content = $request->getContent();

        $user = $this->getUser();

        if ($user instanceof User) {
            $oldPicture = $user->getProfilePicture();

            $file = tmpfile();
            $path = stream_get_meta_data($file)['uri'];
            file_put_contents($path, $content);
            $uploadedFile = new UploadedFile($path, $path, null, null, null, true);

            $uuid = Uuid::uuid4()->toString();
            $ext = $uploadedFile->guessExtension();
            $filename = "{$uuid}.{$ext}";

            if ($filesystem->write($filename, $content) > 0) {
                $user->setProfilePicture($filename);
                $this->getDoctrine()->getManager()->flush();

                if ($oldPicture) {
                    $filesystem->delete($oldPicture);
                }
            }

            $view->setStatusCode(Response::HTTP_CREATED)
                ->setHeader('Location', "/api/uploads/profile/{$filename}");
        } else {
            $view->setStatusCode(Response::HTTP_FORBIDDEN);
        }

        return $view;
    }

}