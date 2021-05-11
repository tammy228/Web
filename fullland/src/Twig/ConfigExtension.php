<?php

namespace App\Twig;

use App\Entity\Config;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class ConfigExtension extends AbstractExtension
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
        return [
            // If your filter generates SAFE HTML, you should add a third
            // parameter: ['is_safe' => ['html']]
            // Reference: https://twig.symfony.com/doc/2.x/advanced.html#automatic-escaping
        ];
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('getConfig', [$this, 'getConfig']),
        ];
    }

    public function getConfig($i)
    {
        $configRepository = $this->em->getRepository(Config::class);

        $config =  $configRepository->findOneBy(['owner'=>'admin']);

        $value ='';

        /**
         * 0 => Title
         * 1 => Description
         * 2 => Keyword
         * 3 => ShippingStandard
         */
        if($i == 0)
            $value=$config->getTitle();
        elseif($i == 1)
            $value = $config->getDescription();
        elseif($i == 2)
            $value = $config->getKeyword();
        elseif($i == 3)
            $value = $config->getShippingStandard();

        return $value;
    }
}
