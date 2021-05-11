<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Category;
use App\Entity\Message;
use App\Form\ArticleType;
use App\Form\CategoryType;
use App\Form\ChooseCategoryType;
use App\Repository\CategoryRepository;
use App\Service\FileUploader;
use App\Form\MessageType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Doctrine\ORM\EntityManagerInterface ;
use App\Repository\UserRepository;


class ArticleController extends AbstractController
{
    /**
     * @Route("/admin/article/create", name="admin.article.create")
     * @param Request $request
     * @param FileUploader $fileUploader
     * @return Response
     * @throws \Exception
     */
    public function adminArticleCreate(Request $request, FileUploader $fileUploader): Response
    {
        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);
        $entityManager = $this->getDoctrine()->getManager();

        if($form->isSubmitted() && $form->isValid())
        {

            $article->setVisitor(0);

            $categoryId = $form['categories']->getData();
            $categoryRepository = $entityManager->getRepository(Category::class);
            $category = $categoryRepository->find($categoryId[0]);

            $article->addCategories($category);

            $entityManager->persist($article);
            $entityManager->flush();

            return $this->redirect('/admin/article');
        }

        return $this->render('admin/article/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("admin/article", name="admin.article.list")
     * @param Request $request
     * @return Response
     * @throws
     */
    public function adminArticleList(Request $request)
    {
        /**
         * page data handler
         */
        $page = $request->query->get('page', "1");
        $page = preg_match("/^[0-9]+$/", $page) ? intval($page) : 1;


        /**
         * limit data handler
         */
        $limit = $request->query->get('limit', "0");
        $limit = preg_match("/^[0-9]+$/", $limit) ? intval($limit) : 0;

        $entityManager = $this->getDoctrine()->getManager();

        $articleRepository = $entityManager->getRepository(Article::class);

        $now = new \Datetime('now + 8hours');
        $articles = $articleRepository->findBy(array(), array("createAt" => "DESC"));

        $count = count($articles);
        $maxPage = $limit ? ceil($count / $limit) : 1;

        return $this->render("admin/article/list.html.twig", array(
            "page" => $page,
            "limit" => $limit,
            "maxPage" => $maxPage,
            "count" => $count,
            "articles" => $articles,
            'now' => $now,
        ));
    }

    /**
     * @Route("admin/categories/{categoryId}/articles",
     *     name="admin.categories.list_article",
     *     requirements={"categoryId"="\d+"})
     * @param Request $request
     * @param $categoryId
     * @return Response
     */
    public function listArticleByCategory(Request $request, $categoryId)
    {
        $em = $this->getDoctrine()->getManager();

        /**
         * @var CategoryRepository $categoryRepository
         */
        $categoryRepository = $em->getRepository(Category::class);
        $category = $categoryRepository->find($categoryId);

        if(!$category) return $this->redirectToRoute("admin.article.list");


        $articles = $category->getArticles();

        return $this->render("admin/article/list.html.twig", array(
            "articles" => $articles,
        ));

    }

    /**
     * @Route("admin/article/listdraft", name="admin.article.listdraft")
     * @return Response
     */
    public function adminArticleListDraft(Request $request)
    {
        /**
         * page data handler
         */
        $page = $request->query->get('page', "1");
        $page = preg_match("/^[0-9]+$/", $page) ? intval($page) : 1;


        /**
         * limit data handler
         */
        $limit = $request->query->get('limit', "0");
        $limit = preg_match("/^[0-9]+$/", $limit) ? intval($limit) : 0;

        $entityManager = $this->getDoctrine()->getManager();

        $articleRepository = $entityManager->getRepository(Article::class);

        $now = new \Datetime('now + 8hours');
        $articles = $articleRepository->findBy(array('draft'=>true), array("createAt" => "DESC"));

        $count = count($articles);
        $maxPage = $limit ? ceil($count / $limit) : 1;

        return $this->render("admin/article/list.html.twig", array(
            "page" => $page,
            "limit" => $limit,
            "maxPage" => $maxPage,
            "count" => $count,
            "articles" => $articles,
            'now' => $now,
        ));
    }

    /**
     * @Route("admin/article/{id}/fetch", name="admin.article.fetch" ,requirements={"id"="\d+"})
     * @param Request $request
     * @param $id
     * @return Response
     */
    public function adminArticleFetch(Request $request, $id)
    {
        $user=$this->getUser();
        $username=$user->getUsername();


        $message = new Message();
        $articleId = intval($id);
        $entityManager = $this->getDoctrine()->getManager();
        $articleRepository = $entityManager->getRepository(Article::class);
        $article = $articleRepository->find($articleId);
        $messageRepository = $entityManager->getRepository(Message::class);
        $messagelist = $messageRepository->findBy(array('articleId'=>$id, 'parent'=>NULL));
        $sonmessagelist = $messageRepository->findBy(array('articleId'=>$id));

        $form = $this->createForm(MessageType::class, $message);

        $form->handleRequest($request);
            if($form->isSubmitted() && $form->isValid())
            {

                $message = $form->getData();
                $content = $form['content']->getData();
                $content2 = substr($content,0,150);
                $message->setUser($username);
                $message->setArticleId($articleId);
                $message->setContent($content2);
                $article->addMessage($message);


                $entityManager->persist($message);
                $entityManager->persist($article);
                $entityManager->flush();

                return $this->redirectToRoute('user.article.fetch',['id' => $id]);
            }
        return $this->render('admin/article/fetch.html.twig', [
            'article' => $article,
            'form' => $form->createView(),
            'messagelist' => $messagelist,
            'sonmessagelist' => $sonmessagelist,
            'user' => $username,
        ]);
    }

    /**
     * @Route("admin/article/{id}/delete", name="admin.article.delete", requirements={"id"="\d+"})
     * @param Request $request
     * @param $id
     * @return Response
     */
    public function adminArticleDelete(Request $request, $id)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $articleRepository = $entityManager->getRepository(Article::class);
        $article = $articleRepository->find($id);

        if(!$article) return $this->redirectToRoute("admin.article.list");

        $entityManager->remove($article);

        $entityManager->flush();

        return $this->redirectToRoute("admin.article.list");

    }

    /**
     * @Route("admin/article/{id}/update", name="admin.article.update", requirements={"id"="\d+"})
     * @param Request $request
     * @param $id
     * @return Response
     * @throws \Exception
     */
    public function adminUpdateArticle(Request $request, $id)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $articleRepository = $entityManager->getRepository(Article::class);
        $article = $articleRepository->find($id);

        if(!$article) return $this->redirectToRoute("admin.article.list");

        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {

            /**
             * @var Article $article
             */
            $article = $form->getData();

            /**
             * handle Category
             */
            $categoryIds = $form['categories']->getData();
            $categoryRepository = $entityManager->getRepository(Category::class);

            $selectedCategories = $article->getCategories();
            foreach($selectedCategories as $selectedCategory)
            {
                $article->removeCategories($selectedCategory);
                $entityManager->persist($article);
            }

            $entityManager->flush();

            foreach($categoryIds as $id)
            {
                $category = $categoryRepository->find($id);
                $article->addCategories($category);
                $entityManager->persist($article);
            }

            $entityManager->flush();

            return $this->redirectToRoute("admin.article.fetch", array(
                "id" => $article->getId()
            ));
        }

        return $this->render("admin/article/update.html.twig", array(
            "form" => $form->createView(),
            "article" =>$article,

        ));
    }

    /**
     *     _      _   _
     *    /_\  __| |_(_)___ _ _  ___
     *   / _ \/ _|  _| / _ \ ' \(_-<
     *  /_/ \_\__|\__|_\___/_||_/__/
     */

    /**
     * @Route("user/article", name="user.article.list")
     */
    public function userArticleList(Request $request)
    {
        $entityManager = $this->getDoctrine()->getManager();

        $articleRepository = $entityManager->getRepository(Article::class);

        $articles = $articleRepository->findBy(array('draft'=>false), array("createAt" => "DESC"));
        $now = new \Datetime('now + 8hours');

        return $this->render("article/list.html.twig", array(
            "articles" => $articles,
            'now' => $now,

        ));
    }

    /**
     * @Route("user/article/{id}/fetch", name="user.article.fetch" ,requirements={"id"="\d+"})
     * @param Request $requset
     * @param $id
     * @return Response
     */
    public function userArticleFetch(Request $request, $id)
    {
        $user=$this->getUser();
        $username=$user->getUsername();


        $message = new Message();
        $articleId = intval($id);
        $entityManager = $this->getDoctrine()->getManager();
        $articleRepository = $entityManager->getRepository(Article::class);
        $article = $articleRepository->find($articleId);
        $messageRepository = $entityManager->getRepository(Message::class);
        $messagelist = $messageRepository->findBy(array('articleId'=>$id, 'parent'=>NULL));
        $sonmessagelist = $messageRepository->findBy(array('articleId'=>$id));

        $form = $this->createForm(MessageType::class, $message);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {

            $message = $form->getData();
            $message->setUser($username);
            $message->setArticleId($id);
            $article->addMessage($message);


            $entityManager->persist($message);
            $entityManager->persist($article);
            $entityManager->flush();

            return $this->redirectToRoute('user.article.fetch',['id' => $id]);
        }
        return $this->render('/article/fetch.html.twig', [
            'article' => $article,
            'form' => $form->createView(),
            'messagelist' => $messagelist,
            'sonmessagelist' => $sonmessagelist,
            'user' => $username,
        ]);
    }


}
