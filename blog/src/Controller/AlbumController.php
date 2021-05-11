<?php

namespace App\Controller;

use App\Entity\Album;
use App\Entity\Photo;
use App\Form\AlbumType;
use App\Repository\AlbumRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AlbumController extends AbstractController
{
    /**
     *     _      _   _
     *    /_\  __| |_(_)___ _ _  ___
     *   / _ \/ _|  _| / _ \ ' \(_-<
     *  /_/ \_\__|\__|_\___/_||_/__/
     */

    /**
     * @Route("user/albums" ,name="albums.list")
     * @param Request $request
     * @return Response
     * @throws
     */
    public function listAlbum(Request $request)
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

        $em = $this->getDoctrine()->getManager();
        /**
         * @var AlbumRepository $albumRepository
         */
        $albumRepository = $em->getRepository(Album::class);

        $count = $albumRepository->count(array());
        $maxPage = $limit ? ceil($count / $limit) : 1;

        if($limit) {
            $albums = $albumRepository->findBy(
                array(),
                array('updateAt' => "DESC"),
                $limit,
                ($page - 1) * $page);
        } else {
            $albums = $albumRepository->findBy(
                array(),
                array('updateAt' => "DESC"));
        }

        $now = new \DateTime("now + 8 hours");
        return $this->render("album/list.html.twig", array(
            "page" => $page,
            "limit" => $limit,
            "count" => $count,
            "maxPage" => $maxPage,
            "albums" => $albums,
            "now" => $now
        ));
    }

    /**
     *     _      _       _
     *    /_\  __| |_ __ (_)_ _
     *   / _ \/ _` | '  \| | ' \
     *  /_/ \_\__,_|_|_|_|_|_||_|
     */

    /**
     * @Route("/admin/albums/create", name="admin.album.create")
     * @param Request $request
     * @return Response
     * @throws \Exception
     */
    public function adminCreateAlbum(Request $request)
    {
        $album = new Album();

        $form = $this->createForm(AlbumType::class, $album);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $em = $this->getDoctrine()->getManager();
            $em->persist($album);
            $em->flush();

            return $this->redirectToRoute("admin.albums.list");
        }

        return $this->render("admin/album/create.html.twig", array(
            "form" => $form->createView()
        ));
    }

    /**
     * @Route("/admin/albums/{id}/update", name="admin.album.update", requirements={"id"="\d+"})
     * @param Request $request
     * @param $id
     * @return Response
     */
    public function adminUpdateAlbum(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $albumRepository = $em->getRepository(Album::class);
        $album = $albumRepository->find($id);
        $offlineAt = $album->getOfflineAt();

        if(!$album) return $this->redirectToRoute("admin.albums.list");

        $form = $this->createForm(AlbumType::class, $album);
        $form->handleRequest($request);


        if($form->isSubmitted() && $form->isValid())
        {
            /**
             * @var Album $album
             */
            $album = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($album);
            $em->flush();

            return $this->redirectToRoute("admin.albums.list");
        }

        return $this->render("admin/album/update.html.twig", array(
            "form" => $form->createView(),
            "album" => $album
        ));
    }

    /**
     * @Route("/admin/albums", name="admin.albums.list")
     * @param Request $request
     * @return Response
     */
    public function adminListAlbum(Request $request)
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

        $em = $this->getDoctrine()->getManager();
        /**
         * @var AlbumRepository $albumRepository
         */
        $albumRepository = $em->getRepository(Album::class);

        $count = $albumRepository->count(array());
        $maxPage = $limit ? ceil($count / $limit) : 1;

        if($limit) {
            $albums = $albumRepository->findBy(
                array(),
                array('updateAt' => "DESC"),
                $limit,
                ($page - 1) * $page);
        } else {
            $albums = $albumRepository->findBy(
                array(),
                array('updateAt' => "DESC"));
        }

        return $this->render("admin/album/list.html.twig", array(
            "page" => $page,
            "limit" => $limit,
            "count" => $count,
            "maxPage" => $maxPage,
            "albums" => $albums
        ));

    }

    /**
     * @Route("/admin/albums/{id}/delete", name="admin.album.delete", requirements={"id"="\d+"})
     * @param Request $request
     * @param $id
     * @return Response
     */
    public function adminDeleteAlbum(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $albumRepository = $em->getRepository(Album::class);
        $album = $albumRepository->find($id);
        $uncategorizedAlbum = $albumRepository->find(1);

        /**
         * @var Album $album
         */
        $protect = $album->isDeletable();

        if($album && $protect)
        {
            $em->remove($album);
            $em->flush();

            $photos = $album->getPhotos();
            foreach($photos as $photo)
            {
                /**
                 * @var Photo $photo
                 */
                $photo->addAlbums($uncategorizedAlbum);
                $em->persist($photo);
            }

            $em->flush();
        }

        return $this->redirectToRoute("admin.albums.list");
    }
}
