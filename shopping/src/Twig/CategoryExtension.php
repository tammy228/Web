<?php

namespace App\Twig;

use App\Entity\Category;
use App\Entity\Message;
use App\Entity\Product;
use App\Entity\UserOrder;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class CategoryExtension extends AbstractExtension
{
    /**
     * @var EntityManagerInterface $em
     */
    private $em;

    private $router;

    public function __construct(EntityManagerInterface $entityManager, UrlGeneratorInterface $router)
    {
        $this->em = $entityManager;
        $this->router = $router;
    }

    public function getFilters(): array
    {
        return [];
    }

    public function getFunctions(): array
    {
        return [

            new TwigFunction('getCategories', [$this, 'getCategories']),
            new TwigFunction('getMessages', [$this, 'getMessages']),
            new TwigFunction('haveChildren',[$this, 'haveChildren']),
            new TwigFunction('getFinalDate',[$this, 'getFinalDate']),
            new TwigFunction('getFormats',[$this, 'getFormats']),
            new TwigFunction('getStatus',[$this, 'getStatus']),
            new TwigFunction('countCategory',[$this, 'countCategory']),
            new TwigFunction('findChildren',[$this, 'findChildren']),
            new TwigFunction('getParent',[$this,'getParent']),
        ];
    }

    public function getParent(Category $category)
    {
        $page = $this->router->generate('admin.category.fetch',array('id'=>$category->getId()));
        $commit='<a href="'.$page.'" >'.$category->getName().'</a>';
        $parent = $category->getParent();
        while($parent)
        {
            $page = $this->router->generate('admin.category.fetch',array('id'=>$parent->getId()));
            $commit = $commit='<a href="'.$page.'" >'.$parent->getName().'</a>➔'.$commit;
            $parent = $parent->getParent();
        }
        return $commit;
    }


    public function findChildren(Category $category, $select = null)
    {
        $content='';
        $children= $category->getChildren();
        foreach ($children as $child)
        {
            if($select != $child)
            {
                $id = $child->getId();
                $name = $child->getName();
                $content = $content. '<option value="'.$id.'">├'.$name.'</option>';
                $content = $content.$this->findChildren2($child,1, $select);
            }
            else{
                $id = $child->getId();
                $name = $child->getName();
                $content = $content. '<option value="'.$id.'" selected="selected">├'.$name.'</option>';
                $content = $content.$this->findChildren2($child,1, $select);
            }

        }
        return new Response(
            $content
        );
    }

    public function findChildren2(Category $category,$time,$select)
    {
        $dash='├';
        while($time)
        {
            $dash=$dash.'─';
            $time--;
        }
        $content='';
        $children= $category->getChildren();
        foreach ($children as $child)
        {
            if($select != $child)
            {
                $id = $child->getId();
                $name = $child->getName();
                $content = $content. '<option value="'.$id.'">'.$dash.$name.'</option>';
                $content = $content.$this->findChildren2($child,2, $select);
            }
            else{
                $id = $child->getId();
                $name = $child->getName();
                $content = $content. '<option value="'.$id.'" selected="selected">'.$dash.$name.'</option>';
                $content = $content.$this->findChildren2($child,2, $select);
            }
        }

        return $content;
    }


    public function countCategory(Category $category)
    {
        $count = 0;
        $sonCategories=$category->getChildren();
        if(isset($sonCategories)){
            foreach ($sonCategories as $sonCategory)
            {
                $count = $count + $this->countCategory($sonCategory);
            }
        }
        $count= $count + $this->countProducts($category->getProduct());

        return $count;
    }

    public function countProducts($products)
    {
        return count($products);
    }


    public function getStatus($id)
    {
        $message = '';
        if($id == 0)$message = '';
        if($id == 1)$message = '已下單';
        if($id == 2)$message = '訂單處理中';
        if($id == 3)$message = '已出貨';
        if($id == 4)$message = '已到貨';
        if($id == 5)$message = '已取貨';
        if($id == 6)$message = '已取消';

        return $message;
    }

    public function getFormats($id): array
    {
        $productRepository = $this->em->getRepository(Product::class);
        $product = $productRepository->find($id);

        return $product->getFormat();;
    }


    public function getFinalDate($id)
    {
        $messageRepository = $this->em->getRepository(Message::class);
        $messages = $messageRepository->findBy(array('senderId'=>$id),array('create_at'=>"DESC"));
        return $messages[0]->getCreateAt();
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