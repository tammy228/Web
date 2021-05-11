<?php

namespace App\Controller;

use App\Entity\Message;
use App\Entity\User;
use App\Form\MessageType;
use App\Repository\CategoryRepository;
use App\Repository\MessageRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\FileUploader;

class MessageController extends AbstractController
{
    /**
     *     _      _       _
     *    /_\  __| |_ __ (_)_ _
     *   / _ \/ _` | '  \| | ' \
     *  /_/ \_\__,_|_|_|_|_|_||_|
     */

    /**
     * @Route("/admin/message/list", name="admin.message.list")
     * @param Request $request
     */
    public function adminMessageList()
    {

        $users=[];
        $em = $this->getDoctrine()->getManager();
        $messageRepository = $em->getRepository(Message::class);
        $userRepository = $em->getRepository(User::class);

        $Ids = $messageRepository->findByDistinct();
        foreach ($Ids as $id)
        {
            $users[] = $userRepository->findOneBy(array('id'=>$id));
        }
        return $this->render("admin/message/list.html.twig",
            array(
                'users' => $users,
            ));
    }

    /**
     * @Route("/admin/message/{id}/fetch", name="admin.message.fetch", requirements={"id"="\d+"})
     * @param Request $request
     */
    public function adminCreateMessage(Request $request, $id)
    {
        $newMessage = new Message();

        $em = $this->getDoctrine()->getManager();

        $userRepository = $em->getRepository(User::class);
        $user = $userRepository->find($id);

        $messageRepository = $em->getRepository(Message::class);
        $admin = $this->getUser();
        $adminId=$admin->getId();

        $messages = $messageRepository->findBy(array('user'=>$id),array('create_at'=>'ASC'));
        $form = $this->createForm(MessageType::class, $newMessage);
        $form -> handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $content=$form['content']->getData();
            $newMessage->setSenderId($adminId);
            $newMessage->setContent($content);
            $newMessage->setUser($user);
            $newMessage->setCreateAt(new \Datetime('now + 8hours'));

            $em->persist($newMessage);
            $em->flush();

            return $this->redirectToRoute('admin.message.fetch', array(
                'id' => $id,
            ));
        }

        return $this->render("admin/message/fetch.html.twig",
            array('form' => $form->createView(),
                'messages' => $messages,
                'newMessage' => $newMessage,
                'id'=>$id,
                'user'=>$user,
            ));
    }

    /**
     * @Route("admin/message/delete", name="admin.message.delete")
     */
    public function adminMessageDelete(MessageRepository $messageRepository)
    {
        /**
         * 取得資料庫名
         */
        $em = $this->getDoctrine()->getManager();
        $databaseName = $_ENV['DATABASE_URL'];

        $databaseName = strrchr($databaseName,'/');
        $databaseName = strtok($databaseName,'?');
        $databaseName = trim($databaseName,'/');
        $now = new \Datetime('now - 6months');

        /**
         * 以SOL語法清除jointable關聯
         */
        $RAW_QUERY = 'DELETE FROM `'.$databaseName.'`.`message`  WHERE create_at < '.$now->format('YmdHis').';';

        $statement = $em->getConnection()->prepare($RAW_QUERY);
        $statement->execute();

        return $this->redirectToRoute('admin.message.list');
    }


    /**
     *     _      _   _
     *    /_\  __| |_(_)___ _ _  ___
     *   / _ \/ _|  _| / _ \ ' \(_-<
     *  /_/ \_\__|\__|_\___/_||_/__/
     */

    /**
     * @Route("/message/fetch", name="message.fetch")
     * @param Request $request
     */
    public function createMessage(Request $request)
    {
        $newMessage = new Message();

        $em = $this->getDoctrine()->getManager();
        $messageRepository = $em->getRepository(Message::class);
        $user = $this->getUser();
        $id=$user->getId();

        $messages = $messageRepository->findBy(array('user'=>$id),array('create_at'=>'ASC'));

        $form = $this->createForm(MessageType::class, $newMessage);
        $form -> handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $content=$form['content']->getData();
            $newMessage->setSenderId($id);
            $newMessage->setContent($content);
            $newMessage->setUser($user);
            $newMessage->setCreateAt(new \Datetime('now + 8hours'));

            $em->persist($newMessage);
            $em->flush();

            return $this->redirectToRoute('message.fetch');
        }

        return $this->render("message/fetch.html.twig",
            array('form' => $form->createView(),
                'messages' => $messages,
                'newMessage' => $newMessage,
                'id'=>$id,
                ));

    }
}