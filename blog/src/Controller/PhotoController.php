<?php

namespace App\Controller;

use App\Entity\Album;
use App\Entity\Photo;
use App\Form\DataTransformer\TextToDataTransformer;
use App\Form\PhotoEditType;
use App\Repository\AlbumRepository;
use App\Repository\PhotoRepository;
use App\Service\FileUploader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\PhotoType;

class PhotoController extends AbstractController
{
    /**
     *     _      _   _
     *    /_\  __| |_(_)___ _ _  ___
     *   / _ \/ _|  _| / _ \ ' \(_-<
     *  /_/ \_\__|\__|_\___/_||_/__/
     */

//    /**
//     * @Route("/photos/{name}", methods={"GET"}, name="photo.fetch")
//     * @param Request $request
//     * @param $name
//     * @return Response
//     * @throws
//     */
//    public function fetchPhoto(Request $request, $name)
//    {
//        $em = $this->getDoctrine()->getManager();
//        $photoRepository = $em->getRepository(Photo::class);
//        $photo = $photoRepository->findOneBy(array('name'=> $name));
//
//        return $this->render("photo/list.html.twig", array(
//            "photo" => $photo
//        ));
//    }
    /**
     * @Route("albums/{albumId}/photos", name="albums.list_photos", requirements={"albumId"="\d+"})
     * @param Request $request
     * @param $albumId
     * @return Response
     * @throws
     */
    public function listPhotoByAlbum(Request $request, $albumId)
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
        $album = $albumRepository->find(intval($albumId));

        if(!$album) return $this->redirectToRoute("albums.list");

        $photos = $album->getPhotos();
        $count = count($photos);
        $maxPage = $limit ? ceil($count / $limit) : 1;


        $now = new \DateTime("now + 8 hours");

        return $this->render("photo/list.html.twig", array(
            "page" => $page,
            "limit" => $limit,
            "maxPage" => $maxPage,
            "count" => $count,
            "photos" => $photos,
            "album" => $album,
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
     * @Route("/admin/{albumId}/photos/create", name="admin.photo.create")
     * @param Request $request
     * @param FileUploader $fileUploader
     * @param  $albumId
     * @return Response
     * @throws \Exception
     */
    public function adminCreatePhoto(Request $request, FileUploader $fileUploader, $albumId)
    {

        $photo = new Photo();
        $form = $this->createForm(PhotoType::class, $photo)
                ;
        $form->handleRequest($request);



        if($form->isSubmitted() && $form->isValid())
        {
            $em = $this->getDoctrine()->getManager();
            $albumRepository = $em->getRepository(Album::class);


            /**
             * @var UploadedFile $content
             */
            $contents = $form['content']->getData();
            foreach ($contents as $content)
            {

                $photo = new Photo();
                $contentName = $fileUploader->upload($content);
                $photo->setContentName("/uploads/photos/".$contentName);
                $fileName = $fileUploader->getOriginalName($content);
                $photo->setName($fileName);

                $albumId = $form['albums']->getData();
                $album = $albumRepository->find($albumId[0]);

                $offline = $form['offlineAt']->getData();
                $photo->setOfflineAt($offline);


                $photo->addAlbums($album);

                $em->persist($photo);
                $em->flush();

            }

            return $this->redirectToRoute("admin.albums.list");
        }

        return $this->render("admin/photo/create.html.twig", array(
            "form" => $form->createView(),
            "albumId" => $albumId
        ));

    }

    /**
     * @Route("/admin/photos/{id}/update", name="admin.photo.update", requirements={"id"="\d+"})
     * @param Request $request
     * @param $id
     * @param FileUploader $fileUploader
     * @return Response
     */
    public function adminUpdatePhoto(Request $request, $id, FileUploader $fileUploader)
    {
        $em = $this->getDoctrine()->getManager();
        $photoRepository = $em->getRepository(Photo::class);
        $photo = $photoRepository->find($id);

        if(!$photo) return $this->redirectToRoute("admin.photos.list");

        $form = $this->createForm(PhotoEditType::class, $photo);
        $form->handleRequest($request);


        if($form->isSubmitted() && $form->isValid())
        {
            /**
             * @var Photo $photo
             */
            $photo = $form->getData();

            /**
             * handle content
             */

            $content = $form['content']->getData();
            if($content)
            {
                $contentName = $fileUploader->upload($content);
                $photo->setContentName("/uploads/photos/".$contentName);
                $fileName = $fileUploader->getOriginalName($content);
                $photo->setName($fileName);
            }

            /**
             * handle albums
             */
            $albumIds = $request->get('photo')['albums'];
            $albumRepository = $em->getRepository(Album::class);

            $selectedAlbums = $photo->getAlbums();
            foreach($selectedAlbums as $selectedAlbum)
            {
                $photo->removeAlbums($selectedAlbum);
                $em->persist($photo);
            }

            $em->flush();

            foreach($albumIds as $id)
            {
                $album = $albumRepository->find($id);
                $photo->addAlbums($album);
                $em->persist($photo);
            }

            $em->flush();

            return $this->redirectToRoute("admin.photo.fetch", array(
                "id" => $photo->getId()
            ));

        }
        return $this->render("admin/photo/update.html.twig", array(
            "form" => $form->createView(),
            "photo" => $photo
        ));
    }

    /**
     * @Route("/admin/photos/{id}/info", name="admin.photo.fetch", methods={"GET"})
     * @param Request $request
     * @param $id
     * @return string
     */
    public function adminFetchPhoto(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $photoRepository = $em->getRepository(Photo::class);
        $photo = $photoRepository->find($id);

        if(!$photo) return $this->redirectToRoute("admin.photos.list");

        return $this->render("admin/photo/fetch.html.twig", array(
            "photo" => $photo
        ));
    }

//    /**
//     * @Route("/admin/photos", name="admin.photos.list")
//     * @param Request $request
//     * @return Response
//     */
//    public function adminListPhoto(Request $request)
//    {
//        /**
//         * page data handler
//         */
//        $page = $request->query->get('page', "1");
//        $page = preg_match("/^[0-9]+$/", $page) ? intval($page) : 1;
//
//        /**
//         * limit data handler
//         */
//        $limit = $request->query->get('limit', "0");
//        $limit = preg_match("/^[0-9]+$/", $limit) ? intval($limit) : 0;
//
//        $em = $this->getDoctrine()->getManager();
//
//        /**
//         * @var PhotoRepository $photoRepository
//         */
//        $photoRepository = $em->getRepository(Photo::class);
//        $count = $photoRepository->count(array());
//        $maxPage = $limit ? ceil($count / $limit) : 1;
//
//        if($limit) {
//            $photos = $photoRepository->findBy(array(), array("updateAt" => "DESC"), $limit, ($page - 1) * $limit);
//        } else {
//            $photos = $photoRepository->findBy(array(), array("updateAt" => "DESC"));
//        }
//
//        return $this->render("admin/photo/list.html.twig", array(
//            "page" => $page,
//            "limit" => $limit,
//            "count" => $count,
//            "maxPage" => $maxPage,
//            "photos" => $photos
//        ));
//    }

    /**
     * @Route("admin/albums/{albumId}/photos", name="admin.albums.list_photos", requirements={"albumId"="\d+"})
     * @param Request $request
     * @param $albumId
     * @return Response
     */
    public function adminListPhotoByAlbum(Request $request, $albumId)
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
        $album = $albumRepository->find($albumId);

        if(!$album) return $this->redirectToRoute("admin.photos.list");

        $photos = $album->getPhotos();
        $count = count($photos);
        $maxPage = $limit ? ceil($count / $limit) : 1;


        return $this->render("admin/photo/list.html.twig", array(
            "page" => $page,
            "limit" => $limit,
            "maxPage" => $maxPage,
            "count" => $count,
            "photos" => $photos,
            "album" => $album,
            "albumId" => $albumId
        ));
    }

    /**
     * @Route("/admin/photos/{id}/delete", name="admin.photo.delete", requirements={"id"="\d+"})
     * @param Request $request
     * @param $id
     * @return Response
     */
    public function adminDeletePhoto(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $photoRepository = $em->getRepository(Photo::class);
        $photo = $photoRepository->find($id);

        if($photo)
        {
            $em->remove($photo);
            $em->flush();
        }

        $response = new RedirectResponse($request->headers->get('Referer')
            ?? $this->generateUrl("admin.albums.list"));
        return $response;
    }
}
