<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ApplyUserToFarmerType;
use App\Form\FarmerType;
use App\Service\FileUploader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
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
     * @Route("/test/verify/{id}", name="verify.user_to_farmer", requirements={"id"="\d+"})
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function verifyUserToFarmer($id)
    {
        $em = $this->getDoctrine()->getManager();
        $userRepository = $em->getRepository(User::class);
        $user = $userRepository->find($id);

        $user->setRoles(['ROLE_FARMER']);
        $user->setRoleCodes(1);
        $user->setApplied(0);

        $em->persist($user);
        $em->flush();

        return $this->redirectToRoute("admin.user.list");
    }

    /**
     * @Route("/admin/user", name="admin.user.list")
     * @param Request $request
     * @return Response
     */
    public function adminListUser(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $userRepository = $em->getRepository(User::class);

        $users = $userRepository->findBy(array(
            "roleCodes" => 2,
            "deleted" => 0
        ));

        return $this->render("/admin/user/list.html.twig", array(
           "users" => $users
        ));
    }

    /**
     * @Route("/admin/user/{id}/delete", name="admin.user.delete", requirements={"id"="\d+"})
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function adminDeleteUser(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $userRepository = $em->getRepository(User::class);

        $user = $userRepository->find($id);

        $user->setDeleted(1);

        $em->persist($user);
        $em->flush();

        return $this->redirectToRoute("admin.user.list");

    }

    /**
     * @Route("/admin/farmer", name="admin.farmer.list")
     * @param Request $request
     * @return Response
     */
    public function adminListFarmer(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $userRepository = $em->getRepository(User::class);

        $farmers = $userRepository->findBy(array("roleCodes" => 1));

        return $this->render("/admin/farmer/list.html.twig", array(
            "farmers" => $farmers
        ));
    }

    /**
     * @Route("/admin/farmer/{uuid}/info", name="admin.farmer.fetch")
     * @param Request $request
     * @param $uuid
     * @return Response
     */
    public function adminFetchFarmer(Request $request, $uuid)
    {
        $em = $this->getDoctrine()->getManager();
        $userRepository = $em->getRepository(User::class);

        $farmer = $userRepository->findOneBy(array("uuid" => $uuid));

        return $this->render("/admin/farmer/fetch.html.twig", array(
           "farmer" => $farmer
        ));

    }

    /**
     * @Route("/admin/farmer/{id}/delete", name="admin.farmer.delete", requirements={"id"="\d+"})
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function adminDeleteFarmer(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $userRepository = $em->getRepository(User::class);

        $user = $userRepository->find($id);

        //刪除小農，先不要整個刪掉，先降級為user
        $user->setRoles(['ROLE_USER']);
        $user->setRoleCodes(2);

        $em->persist($user);
        $em->flush();

        return $this->redirectToRoute("admin.farmer.list");
    }

    /***
     *        ____
     *       / __/____ _ _____ ____ ___   ___   _____
     *      / /_ / __ `// ___// __ `__ \ / _ \ / ___/
     *     / __// /_/ // /   / / / / / //  __// /
     *    /_/   \__,_//_/   /_/ /_/ /_/ \___//_/
     *
     */

    /**
     * @Route("/farmer/info", name="farmer.info")
     * @param Request $request
     * @return Response
     */
    public function farmerInfo(Request $request , FileUploader $fileUploader)
    {
        $user = $this->getUser();
        $form = $this->createForm(FarmerType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $image = $form['image']->getData();

            $i = 0;

            if($image)
            {
                $contentName = $fileUploader->upload($image);
                $content="/uploads/images/".$contentName;
            }
            else
                $content = '';
            $user->setImage($content);

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            return $this->render('farmer/info/fetch.html.twig',array(
                'form' => $form->createView(),
                'user' => $user
            ));
        }

        return $this->render('farmer/info/fetch.html.twig',array(
            'form' => $form->createView(),
            'user' => $user
        ));
    }


    /***
     *                    __   _
     *      ____ _ _____ / /_ (_)____   ____
     *     / __ `// ___// __// // __ \ / __ \
     *    / /_/ // /__ / /_ / // /_/ // / / /
     *    \__,_/ \___/ \__//_/ \____//_/ /_/
     *
     */

    /**
     * @Route("/join", name="join")
     * @param Request $request
     * @return Response
     */
    public function applyUserToFarmer(Request $request)
    {
        if(!$this->getUser()) return $this->redirectToRoute("auth.login");

        $form = $this->createForm(ApplyUserToFarmerType::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {

            $em = $this->getDoctrine()->getManager();
            $userRepository = $em->getRepository(User::class);

            $email = $form['email']->getData();

//            $user = $userRepository->findOneBy(array("email" => $email));

            $user = $this->getUser();

            //檢查是否真的為user
            if($user->getRoleCodes() == 2)
            {
                $user->setApplied(1);
                $em->persist($user);
                $em->flush();
            }

            return $this->redirectToRoute("applicationCompleted");
        }

        return $this->render("user/user/join.html.twig", array(
            "form" => $form->createView()
        ));
    }

}
