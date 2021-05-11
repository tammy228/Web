<?php

namespace App\Twig;

use App\Entity\Product;
use App\Entity\User;
use App\Entity\UserToUser;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class UserExtension extends AbstractExtension
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
        return [
            // If your filter generates SAFE HTML, you should add a third
            // parameter: ['is_safe' => ['html']]
            // Reference: https://twig.symfony.com/doc/2.x/advanced.html#automatic-escaping
            new TwigFilter('filter_name', [$this, 'doSomething']),
        ];
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('getRelation', [$this, 'getRelation']),
            new TwigFunction('getFarmers', [$this, 'getFarmers']),
            new TwigFunction('isEmail', [$this, 'isEmail']),
        ];
    }

    //判斷 user 有沒有follow 這個 farmer
    public function getRelation($userId, $productId)
    {
        $user = $this->em->getRepository(User::class)->find($userId);
        $farmer = $this->em->getRepository(Product::class)->find($productId)->getUser();

        $userToUserRepo = $this->em->getRepository(UserToUser::class);
        $relation = $userToUserRepo->findOneBy(array(
           "user" => $user,
           "farmer" => $farmer
        ));

        return $relation;
    }

    public function getFarmers()
    {
        return $this->em->getRepository(User::class)->findBy(array('roleCodes'=>1));
    }

}
