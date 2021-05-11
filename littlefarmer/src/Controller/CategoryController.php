<?php

namespace App\Controller;


use App\Entity\Category;
use App\Form\CategoryType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
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
     * @Route("admin/category/create", name="admin.category.create")
     * @param Request $request
     * @return Response
     */
    public function adminCreateCategory(Request $request)
    {
        $category = New Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form -> handleRequest($request);

        if($form->isSubmitted() && $form-> isValid())
        {
            $category->setUuid();

            $em = $this->getDoctrine()->getManager();
            $em->persist($category);
            $em->flush();

            return $this->redirectToRoute('admin.category.list');
        }
        return $this->render('admin/category/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/category", name="admin.category.list")
     * @param Request $request
     * @return Response
     */
    public function adminListCategory(Request $request)
    {
        $em = $this ->getDoctrine()->getManager();
        $categoryRepository = $em->getRepository(Category::class);

        $Categories = $categoryRepository->findBy(array());

        return $this -> render("admin/category/list.html.twig", array(
            "categories" => $Categories,
        ));
    }

    /**
     * @Route("/admin/category/{uuid}/update", name="admin.category.update")
     * @param Request $request
     * @param $uuid
     * @return Response
     */
    public function adminUpdateCategory(Request $request, $uuid)
    {
        $em = $this->getDoctrine()->getManager();
        $categoryRepository = $em->getRepository(Category::class);
        $category = $categoryRepository->findOneBy( array("uuid" => $uuid));

        if(!$category) return $this->redirectToRoute("admin.category.list");

        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $category = $form->getData();

            $em->persist($category);
            $em->flush();

            return $this->redirectToRoute("admin.category.list");
        }

        return $this->render("admin/category/update.html.twig", array(
            "form" => $form->createView(),
            "category" => $category
        ));
    }

    /**
     * @Route("/admin/category/{uuid}/delete", name="admin.category.delete")
     * @param Request $request
     * @param $uuid
     * @return Response
     */
    public function adminCategoryDelete(Request $request, $uuid)
    {
        $em = $this->getDoctrine()->getManager();
        $categoryRepository = $em->getRepository(Category::class);

        /**
         * @var Category $category
         */
        $category = $categoryRepository->findOneBy(array("uuid" => $uuid));

        if(!$category) return $this->redirectToRoute("admin.category.list");

        $em->remove($category);
        $em->flush();

        return $this->redirectToRoute("admin.category.list");
    }

}
