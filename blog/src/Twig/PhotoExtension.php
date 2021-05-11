<?php

namespace App\Twig;

use App\Entity\Album;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\PersistentCollection;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class PhotoExtension extends AbstractExtension
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
            new TwigFunction('getAlbums', [$this, 'getAlbums']),
            new TwigFunction('getFirstPhoto', [$this, 'getFirstPhoto']),
            new TwigFunction('getAlbum', [$this, 'getAlbum']),
            new TwigFunction('getAlbumId', [$this, 'getAlbumId']),

        ];
    }

    public function getAlbums()
    {
        $albumRepository = $this->em->getRepository(Album::class);

        return $albumRepository->findBy(array(), array("id" => "ASC"));
    }

    public function getFirstPhoto()
    {
        $albumRepository = $this->em->getRepository(Album::class);

        return $albumRepository->find(1);
    }

    public function getAlbum($albumId)
    {
        $albumRepository = $this->em->getRepository(Album::class);

        return $albumRepository->find($albumId);
    }

    public function getAlbumId(Album $album)
    {
        return $album->getId();
    }

}
