<?php

namespace App\Controller;

use App\Entity\Config;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ConfigController extends AbstractController
{
    /***
     *                  __            _
     *      ____ _ ____/ /____ ___   (_)____
     *     / __ `// __  // __ `__ \ / // __ \
     *    / /_/ // /_/ // / / / / // // / / /
     *    \__,_/ \__,_//_/ /_/ /_//_//_/ /_/
     *
     */

    /**
     * @Route("admin/config/general", name="config.general")
     * @param Request $request
     * @return Response
     */
    public function configGeneral(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $websiteConfigRepository = $em->getRepository(Config::class);
        $config = $websiteConfigRepository->findOneBy(['owner'=>'admin']);
        if(!$config)
        {
            $config = new Config();

            $config->setTitle('Collete');
            $config->setKeyword('Collete');
            $config->setDescription('Collete');
            $config->setShippingStandard(8);
            $config->setOwner('admin');

            $em->persist($config);
            $em->flush();
            return $this->redirectToRoute('config.general');
        }
        $data = array(
            "title" => $config->getTitle(),
            "description" => $config->getDescription(),
            "keyword" => $config->getKeyword(),
            "shippingStandard" => $config->getShippingStandard(),
        );


        $form = $this->createFormBuilder($data)
            ->add("title", TextType::class, array(
                "required" => true,
                "label" => "網站名稱"
            ))
            ->add("description", TextareaType::class, array(
                "required" => false,
                "label" => "網站敘述"
            ))
            ->add("keyword", TextType::class, array(
                "required" => false,
                "label" => "網站關鍵字"
            ))
            ->add("shippingStandard", TextType::class, array(
                "label" => "最少出貨數量"
            ))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $data = $form->getData();

            $config->setTitle($data['title']);
            $config->setKeyword($data['keyword']);
            $config->setDescription($data['description']);
            $config->setShippingStandard($data['shippingStandard']);

            $em->persist($config);
            $em->flush();

            $em->flush();
        }

        return $this->render("admin/config/general.html.twig", array(
            "form" => $form->createView()
        ));
    }



}