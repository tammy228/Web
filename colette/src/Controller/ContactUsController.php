<?php

namespace App\Controller;

use App\Entity\ContactUs;
use App\Service\FileUploader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ContactUsController extends AbstractController
{
    /**
     * @Route("contactUs/create", name="contactUs.create")
     * @param Request $request
     * @param FileUploader $fileUploader
     * @return Response
     */
    public function adminCreateBanner(Request $request, FileUploader $fileUploader)
    {
        $contactUs = new ContactUs();
        $contactUs->setUuid();

        $contactUs->setName($_POST['name']);
        $contactUs->setContent($_POST['name']);
        $contactUs->setEmail($_POST['email']);

        $em = $this->getDoctrine()->getManager();
        $em->persist($contactUs);
        $em->flush();

        return $this->redirectToRoute('user.index');
    }

    /***
     *                  __            _
     *      ____ _ ____/ /____ ___   (_)____
     *     / __ `// __  // __ `__ \ / // __ \
     *    / /_/ // /_/ // / / / / // // / / /
     *    \__,_/ \__,_//_/ /_/ /_//_//_/ /_/
     *
     */

    /**
     * @Route("/admin/contactUs", name="admin.contactUs.list")
     * @param Request $request
     * @return Response
     */
    public function adminListContactUs(Request $request)
    {
        $em = $this ->getDoctrine()->getManager();
        $cRepository = $em->getRepository(ContactUs::class);

        $contactUs = $cRepository->findAll();

        return $this -> render("admin/contactUs/list.html.twig", array(
            "contactUs" => $contactUs,
        ));
    }

    /**
     * @Route("/admin/contactUs/{uuid}/delete", name="admin.contactUs.delete")
     * @param Request $request
     * @param $uuid
     * @return Response
     */
    public function adminDeleteContactUs(Request $request, $uuid)
    {
        $em = $this->getDoctrine()->getManager();
        $cRepository = $em->getRepository(ContactUs::class);
        $contactUs = $cRepository->findOneBy(array("uuid" => $uuid));

        if(!$contactUs) return $this->redirectToRoute("admin.contactUs.list");

        $em->remove($contactUs);
        $em->flush();

        return $this->redirectToRoute("admin.contactUs.list");
    }

    /**
     * @Route("/admin/contactUs/{uuid}/info", name="admin.contactUs.fetch", methods={"GET"})
     * @param Request $request
     * @param $uuid
     * @return string
     */
    public function adminFetchContactUs(Request $request, $uuid)
    {
        $em = $this->getDoctrine()->getManager();
        $cRepository = $em->getRepository(ContactUs::class);
        $contactUs = $cRepository->findOneBy(array("uuid" =>$uuid));

        if(!$contactUs) return $this->redirectToRoute("admin.contactUs.list");

        return $this->render("admin/contactUs/fetch.html.twig", array(
            "contactUs" => $contactUs
        ));
    }

}
