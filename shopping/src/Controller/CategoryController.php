<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Product;
use App\Form\CategoryType;
use App\Form\ProductType;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\FileUploader;

class CategoryController extends AbstractController
{
    /**
     * @Route("admin/category/create", name="admin.category.create")
     * @param Request $request
     * @return Response
     */
    public function adminCategoryCreate(Request $request)
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form -> handleRequest($request);

        if($form->isSubmitted() && $form-> isValid()){
            $category->setName($form->get('name')->getData());

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($category);
            $entityManager->flush();

            return $this->redirect('/admin/category');
        }
        return $this->render('admin/category/create.html.twig', [
            'form' => $form->createView(),
        ]);


    }

    /**
     * @Route("admin/category", name="admin.category.list")
     * @param Request $request
     * @return Response
     */
    public function adminCategoryList(Request $request)
    {
        $entityManager = $this ->getDoctrine()->getManager();
        $categoryRepository = $entityManager->getRepository(Category::class);

        $Categories = $categoryRepository->findBy(array('parent'=> NULL ), array("id" => "ASC"));

        return $this -> render("admin/category/list.html.twig", array(
            "categories" => $Categories,
        ));
    }

    /**
     * @Route("/admin/category/{id}/update", name="admin.category.update", requirements={"id"="\d+"})
     * @param Request $request
     * @param $id
     * @return Response
     */
    public function adminUpdateCategory(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $categoryRepository = $em->getRepository(Category::class);
        $category = $categoryRepository->find($id);

        if(!$category) return $this->redirectToRoute("admin.category.list");

        $form = $this->createForm(CategoryType::class, $category);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $category = $form->getData();

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($category);
            $entityManager->flush();

            return $this->redirectToRoute("admin.category.list");
        }

        return $this->render("admin/category/update.html.twig", array(
            "form" => $form->createView(),
            "category" => $category
        ));
    }


    /**
     * @Route("/admin/category/{id}/delete", name="admin.category.delete", requirements={"id"="\d+"})
     * @param Request $request
     * @param $id
     * @return Response
     */
    public function adminCategoryDelete(Request $request, $id)
    {
        $categoryId = intval($id);
        $entityManager = $this->getDoctrine()->getManager();
        $categoryRepository = $entityManager->getRepository(Category::class);

        /**
         * @var Category $category
         */
        $category = $categoryRepository->find($categoryId);

        if(!$category) return $this->redirectToRoute("admin.category.list");

        $entityManager->remove($category);
        $entityManager->flush();

        return $this->redirectToRoute("admin.category.list");
    }

    /**
     * @Route("/admin/category/{id}/list", name="admin.category.fetch", requirements={"id"="\d+"})
     * @param Request $request
     * @param $id
     * @return Response
     */
    public function adminCategoryFetch(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $categoryRepository = $em->getRepository(Category::class);
        $category = $categoryRepository->find($id);

        $sonCategory = $category->getChildren();

        $products = $category->getProduct();

        return $this -> render("admin/category/fetch.html.twig", array(
            "category" => $category,
            "sonCategory" => $sonCategory,
            "products" => $products,
        ));
    }

    /**
     * @Route("/admin/category/{id}/create", name= "admin.soncategory.create" ,requirements={"id"="\d+"})
     * @param Request $request
     * @param $id
     * @return Response
     * @throws \Exception
     */
    public function adminSonCategoryCreate(Request $request, $id)
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form -> handleRequest($request);

        $entityManager = $this ->getDoctrine()->getManager();
        $categoryRepository = $entityManager->getRepository(Category::class);

        if($form->isSubmitted() && $form-> isValid()){

            $parentCategory = $categoryRepository->find($id);

            $category->setName($form->get('name')->getData());

            $parentCategory->addChild($category);

            $entityManager->persist($parentCategory);
            $entityManager->persist($category);
            $entityManager->flush();

            return $this->redirectToRoute('admin.category.fetch', array(
                "id" => $id,
            ));
        }

        return $this->render('admin/category/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}