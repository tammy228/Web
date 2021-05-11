<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Product;
use App\Entity\ProductToCategory;
use App\Form\CategoryType;
use App\Form\ChildCategoryType;
use App\Service\Base64ToFile;
use App\Service\FileUploader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\RedirectResponse;
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
     * @param FileUploader $fileUploader
     * @return Response
     */
    public function adminCreateCategory(Request $request, FileUploader $fileUploader)
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $em = $this->getDoctrine()->getManager();

            $dataUrls = $_POST['image'];
            $imageNames = $_POST['image_name'];

            $dataUrls = explode(",data:",$dataUrls);
            $imageNames = explode(",", $imageNames);
            $content="";

            /**
             * @var UploadedFile $image
             */
            for ($i=0; $i<count($dataUrls); $i++)
            {
                $image = new Base64ToFile($dataUrls[$i], $imageNames[$i]);
                $contentName = $fileUploader->upload($image);
                $content="/uploads/images/".$contentName;
            }

            $category->setThumbnail($content);
            $category->setUuid();

            $em->persist($category);
            $em->flush();

            return $this->redirectToRoute("admin.category.list");
        }

        return $this->render("admin/category/create.html.twig", [
            "form" => $form->createView(),
            "category" => $category
        ]);
    }

    /**
     * @Route("admin/category", name="admin.category.list")
     * @param Request $request
     * @return Response
     */
    public function adminListCategory(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $categoryRepository = $em->getRepository(Category::class);

        $categories = $categoryRepository->findBy(array());

        return $this->render("admin/category/list.html.twig",[
           "categories" => $categories
        ]);
    }

    /**
     * @Route("admin/category/{uuid}/update", name="admin.category.update")
     * @param Request $request
     * @param FileUploader $fileUploader
     * @param $uuid
     * @return RedirectResponse|Response
     */
    public function adminUpdateCategory(Request $request, FileUploader $fileUploader, $uuid)
    {
        $em = $this->getDoctrine()->getManager();
        $categoryRepository = $em->getRepository(Category::class);
        $category = $categoryRepository->findOneBy(array("uuid" => $uuid));


        if(!$category) return $this->redirectToRoute("admin.category.list");

        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            //先刪掉原本的thumbnail
            /**
             * @var Category $category
             */
            $thumbnailPath = $category->getThumbnail();
            if($thumbnailPath)
                unlink($this->getParameter('kernel.project_dir').'/public'.$thumbnailPath);
            $category->setThumbnail("");
            $em->persist($category);
            $em->flush();

            //再更新thumbnail
            $dataUrls = $_POST['image'];
            $imageNames = $_POST['image_name'];

            $dataUrls = explode(",data:",$dataUrls);
            $imageNames = explode(",", $imageNames);
            $content="";

            /**
             * @var UploadedFile $image
             */
            for ($i=0; $i<count($dataUrls); $i++)
            {
                $image = new Base64ToFile($dataUrls[$i], $imageNames[$i]);
                $contentName = $fileUploader->upload($image);
                $content="/uploads/images/".$contentName;
            }

            $category->setThumbnail($content);

            $em->persist($category);
            $em->flush();

            return $this->redirectToRoute("admin.category.list");
        }

        return $this->render("admin/category/update.html.twig", [
            "form" => $form->createView(),
            "category" => $category
        ]);
    }

    /**
     * @Route("admin/category/{uuid}/info", name="admin.category.fetch")
     * @param Request $request
     * @param $uuid
     * @return RedirectResponse|Response
     */
    public function adminFetchCategory(Request $request, $uuid)
    {
        $em = $this->getDoctrine()->getManager();
        $categoryRepository = $em->getRepository(Category::class);
        $category = $categoryRepository->findOneBy(array("uuid" => $uuid));

        if(!$category) return $this->redirectToRoute("admin.category.list");

        return $this->render("admin/category/fetch.html.twig", [
           "category" => $category
        ]);
    }

    /**
     * @Route("/admin/category/{uuid}/delete", name="admin.category.delete")
     * @param Request $request
     * @param $uuid
     * @return Response
     */
    public function adminDeleteCategory(Request $request, $uuid)
    {
        $em = $this->getDoctrine()->getManager();
        $categoryRepository = $em->getRepository(Category::class);

        /**
         * @var Category $category
         */
        $category = $categoryRepository->findOneBy(array("uuid" => $uuid));


        if(!$category) return $this->redirectToRoute("admin.category.list");

        //unlink thumbnail
        $thumbnailPath = $category->getThumbnail();
        if($thumbnailPath)
            unlink($this->getParameter('kernel.project_dir').'/public'.$thumbnailPath);

        //屬於該分類的都會被導到未分類
        $productToCategoryRepo = $em->getRepository(ProductToCategory::class);
        $relations = $productToCategoryRepo->findBy(array(
            "category" => $category
        ));
        $uncategorized = $categoryRepository->findOneBy(array("zhName" => "未分類"));
        foreach ($relations as $relation)
        {
            /**
             * @var ProductToCategory $relation
             */
            $relation->setCategory($uncategorized);
            $em->persist($relation);
        }

        //再刪除該分類
        $em->remove($category);
        $em->flush();

        return $this->redirectToRoute("admin.category.list");
    }


    /***
     *                  __            _                 __     _  __     __                     __
     *      ____ _ ____/ /____ ___   (_)____     _____ / /_   (_)/ /____/ /       _____ ____ _ / /_ ___   ____ _ ____   _____ __  __
     *     / __ `// __  // __ `__ \ / // __ \   / ___// __ \ / // // __  /______ / ___// __ `// __// _ \ / __ `// __ \ / ___// / / /
     *    / /_/ // /_/ // / / / / // // / / /  / /__ / / / // // // /_/ //_____// /__ / /_/ // /_ /  __// /_/ // /_/ // /   / /_/ /
     *    \__,_/ \__,_//_/ /_/ /_//_//_/ /_/   \___//_/ /_//_//_/ \__,_/        \___/ \__,_/ \__/ \___/ \__, / \____//_/    \__, /
     *                                                                                                 /____/              /____/
     */

    /**
     * @Route("admin/category/{id}/child/create", name="admin.child-category.create",requirements={"id"="\d+"})
     * @param Request $request
     * @param $id
     * @return RedirectResponse|Response
     */
    public function adminCreateChildCategory(Request $request,$id)
    {
        $category = new Category();
        $form = $this->createForm(ChildCategoryType::class, $category);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $em = $this->getDoctrine()->getManager();
            $categoryRepository = $em->getRepository(Category::class);
            $parentCategory = $categoryRepository->find($id);

            /**
             * @var Category $parentCategory
             */
            $parentCategory->addChild($category);
            $category->setThumbnail("");
            $category->setUuid();

            $em->persist($parentCategory);
            $em->persist($category);
            $em->flush();

            return $this->redirectToRoute("admin.child-category.list",array(
                'id' => $parentCategory->getId()
            ));
        }

        return $this->render("admin/child-category/create.html.twig", [
            "form" => $form->createView(),
            "category" => $category
        ]);
    }

    /**
     * @Route("admin/category/{id}/child", name="admin.child-category.list",requirements={"id"="\d+"})
     * @param Request $request
     * @param $id
     * @return Response
     */
    public function adminListChildCategory(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $categoryRepository = $em->getRepository(Category::class);
        $childCategories = $categoryRepository->findBy(array("parent" => $id));

        return $this -> render("admin/child-category/list.html.twig", array(
            "childCategories" => $childCategories,
        ));
    }

    /**
     * @Route("admin/category/{uuid}/child/update", name="admin.child-category.update")
     * @param Request $request
     * @param FileUploader $fileUploader
     * @param $uuid
     * @return RedirectResponse|Response
     */
    public function adminUpdateChildCategory(Request $request, FileUploader $fileUploader, $uuid)
    {
        $em = $this->getDoctrine()->getManager();
        $categoryRepository = $em->getRepository(Category::class);
        $category = $categoryRepository->findOneBy(array("uuid" => $uuid));

        if(!$category) return $this->redirectToRoute("admin.category.list");

        $form = $this->createForm(ChildCategoryType::class, $category);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {

            $em->persist($category);
            $em->flush();

            return $this->redirectToRoute("admin.child-category.list", array(
                "id" => $category->getParent()->getId()
            ));
        }

        return $this->render("admin/child-category/update.html.twig", [
            "form" => $form->createView(),
            "category" => $category
        ]);
    }


    /***
     *
     *      __  __ _____ ___   _____
     *     / / / // ___// _ \ / ___/
     *    / /_/ /(__  )/  __// /
     *    \__,_//____/ \___//_/
     *
     */

    /**
     * @Route("category/{id}/child", name="user.child-category.list",requirements={"id"="\d+"})
     * @param $id
     * @return Response
     */
    public function userListChildCategory($id)
    {
        $em = $this->getDoctrine()->getManager();
        $categoryRepository = $em->getRepository(Category::class);
        $childCategories = $categoryRepository->findBy(array("parent" => $id));

        $products = [];
        if($childCategories)
        {
            $productToCategoryRepository = $em->getRepository(ProductToCategory::class);
            $relations = $productToCategoryRepository->findBy(array(
                "category" => $childCategories[0]
            ));

            $products = [];
            foreach ($relations as $relation)
            {
                array_push($products, $relation->getProduct());
            }
        }


        return $this -> render("user/product/list.html.twig", array(
            "childCategories" => $childCategories,
            "products" => $products,
        ));
    }
}
