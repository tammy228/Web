<?php

namespace App\Twig;

use App\Entity\Category;
use App\Entity\Article;
use App\Entity\Message;
use Doctrine\ORM\EntityManagerInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class CategoryExtension extends AbstractExtension
{
    /**
     * @var EntityManagerInterface $em
     */
    private $em;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
    }

    public function getFilters(): array
    {
        return [];
    }

    public function getFunctions(): array
    {
        return [

            new TwigFunction('getCategories', [$this, 'getCategories']),
            new TwigFunction('addVisitor', [$this, 'addVisitor']),
            new TwigFunction('getArticleTitle', [$this, 'getArticleTitle']),
            new TwigFunction('getMessages', [$this, 'getMessages']),
            new TwigFunction('countArticles', [$this, 'countArticles']),
            new TwigFunction('haveChildren',[$this, 'haveChildren'])
        ];
    }

    public function countArticles($articles)
    {
        $count = 0;
        foreach ($articles as $article){
            if($article->getDraft() == false){
                $count++;
            }
        }

        return $count;
    }

    public function getMessages($id)
    {
        $messageRepository = $this->em->getRepository(Message::class);
        $messages = $messageRepository->findBy(array('articleId'=>$id),null);
        return count($messages);


    }

    public function getArticleTitle($id)
    {
        $articleRepository = $this->em->getRepository(Article::class);
        $article = $articleRepository->find($id);
        $title = $article->getTitle();
        return $title;
    }
    public function addVisitor($visitor,$id)
    {
        if (!isset($_SESSION['count'])) //count 是代號
        {

            $articleRepository = $this->em->getRepository(Article::class);

            $article = $articleRepository->find($id);

            $visitor++;

            $article->setVisitor($visitor);
            $this->em->persist($article);
            $this->em->flush();
            $count_session=rand(1000000,9999999); //一個由1000000 - 9999999 隨機提供的數字

            $_SESSION['count'] = "$count_session"; //將上面的數字儲存在伺服器內
        }
    }

    public function getCategories()
    {
        $categoryRepository = $this->em->getRepository(Category::class);

        return $categoryRepository->findBy(array('parent'=>null), array("id" => "ASC"));
    }

    public function haveChildren($message)
    {
        $messageRepository = $this->em->getRepository(Message::class);

        $son = $messageRepository->findBy(array('parent'=>$message), array("id" => "ASC"));

        return count($son);
    }
}