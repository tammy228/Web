<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class WebsiteController extends AbstractController
{
    /**
     * @Route("/about" ,name="about")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */

    public function about(Request $request)
    {
        $locale = $request->getLocale();
        return $this->render('website/about.html.twig',[
            'locale' => $locale
        ]);
    }

    /**
     * @Route("/advantages" ,name="advantages")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function advantages(Request $request)
    {
        $locale = $request->getLocale();
        return $this->render('website/advantages.html.twig',[
            'locale' => $locale
        ]);
    }

    /**
     * @Route("/certificates" ,name="certificates")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function certificates(Request $request)
    {
        $locale = $request->getLocale();
        return $this->render('website/certificates.html.twig',[
            'locale' => $locale
        ]);
    }

    /**
     * @Route("/equipment" ,name="equipment")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function equipment(Request $request)
    {
        $locale = $request->getLocale();
        return $this->render('website/equipment.html.twig',[
            'locale' => $locale
        ]);
    }

    /**
     * @Route("/contact" ,name="contact")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function contact(Request $request)
    {
        $locale = $request->getLocale();
        return $this->render('website/contact.html.twig',[
            'locale' => $locale
        ]);
    }


}
