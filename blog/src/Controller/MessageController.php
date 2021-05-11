<?php

namespace App\Controller;

use App\Entity\Message;
use App\Form\MessageType;
use App\Security\LoginFormAuthenticator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Doctrine\ORM\EntityManagerInterface ;
use App\Repository\UserRepository;


class MessageController extends AbstractController
{
    /**
     * @Route("/message", name="message")
     */
    public function index()
    {
        return $this->render('message/index.html.twig', [
            'controller_name' => 'MessageController',
        ]);
    }

    /**
     * @Route("admin/message", name="admin.message.list")
     * @param Request $request
     * @return Response
     */
    public function adminMessageList(Request $request)
    {
        $entityManager = $this->getDoctrine()->getManager();

        $messageRepository = $entityManager->getRepository(Message::class);


        $messages = $messageRepository->findBy(array(), array("id" => "ASC"));


        return $this->render("admin/message/list.html.twig", array(

            "messages" => $messages
        ));
    }



    /**
     * @Route("/admin/messages/{id}/delete", name="admin.messages.delete", requirements={"id"="\d+"})
     * @param Request $request
     * @param $id
     * @return Response
     */
    public function adminDeleteMessage(Request $request, $id)
    {
        $messageId = intval($id);
        $entityManager = $this->getDoctrine()->getManager();
        $messageRepository = $entityManager->getRepository(Message::class);
        $message = $messageRepository->find($messageId);

        if(!$message) 
        {
            return $this->redirectToRoute("admin.messages.list");
        }
        $entityManager->remove($message);
        $entityManager->flush();
        return $this->redirectToRoute("admin.messages.list");
    }

    /**
     * @Route("/user/message/{id}/edit", name="user.message.edit", requirements={"id"="\d+"})
     * @param Request $request
     * @param $id
     * @return Response
     * @throws \Exception
     */
    public function userMessageEdit(Request $request, $id)
    {
        $messageId = intval($id);
        $entityManager = $this->getDoctrine()->getManager();

        $messageRepository = $entityManager->getRepository(Message::class);
        $message = $messageRepository->find($messageId);

        if(!$message) return $this->redirectToRoute("user.article.fetch", ['id' => $id ]);

        $form = $this->createForm(MessageType::class, $message);
        $form->handleRequest($request);
        $articleId = $message ->getArticleId();
        if($form->isSubmitted() && $form->isValid())
        {
            $content = $form['content']->getData();
            $content2 = substr($content,0,150);
            $message->setContent($content2);

            $now = new \Datetime('now + 8hours');
            $message->setUpdateAt($now);

            $entityManager->persist($message);
            $entityManager->flush();
            return $this->redirectToRoute("user.article.fetch", ['id' => $articleId ]);
        }

        return $this->render("/message/update.html.twig", array(
            "form" => $form->createView(),
            "message" => $message

        ));

    }

    /**
     * @Route("user/message/{id}/delete", name="user.message.delete", requirements={"id"="\d+"})
     * @param Request $request
     * @param $id
     * @return Response
     */
    public function userMessageDelete(Request $request, $id)
    {
        $messageId = intval($id);
        $entityManager = $this->getDoctrine()->getManager();
        $messageRepository = $entityManager->getRepository(Message::class);

        $message = $messageRepository->find($messageId);
        $id = $message ->getArticleId();

        if(!$message)
        {
            return $this->redirectToRoute("user.article.fetch", ['id' => $id ]);
        }
        $entityManager->remove($message);

        $entityManager->flush();

        return $this->redirectToRoute("user.article.fetch", ['id' => $id ]);
    }

    /**
     * @Route("user/message/{id}/create", name="user.message.create", requirements={"id"="\d+"})
     * @param Request $request
     * @param $id
     * @return Response
     */
    public function userMessageCreate(Request $request, $id)
    {
        $user=$this->getUser();
        $username=$user->getUsername();
        $messageId = intval($id);
        $entityManager = $this->getDoctrine()->getManager();
        $messageRepository = $entityManager->getRepository(Message::class);
        $parentMessage = $messageRepository->find($messageId);
        $message = New Message();
        $articleId = $parentMessage->getArticleId();
        if(!$message) return $this->redirectToRoute("user.article.fetch", ['id' => $id ]);

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
            $parentMessage->addChild($message);

            $entityManager->persist($parentMessage);
            $entityManager->persist($message);

            $entityManager->flush();

            return $this->redirectToRoute("user.article.fetch", ['id' => $articleId ]);
        }

        return $this->render("/message/update.html.twig", array(
            "form" => $form->createView(),
            "message" => $parentMessage

        ));
    }

    /**
     * @Route("user/message/{id}/fetch", name="user.message.fetch", requirements={"id"="\d+"})
     * @param Request $request
     * @param $id
     * @return Response
     * @throws
     */
    public function userMessageFetch(Request $request, $id)
    {

        $user=$this->getUser();
        $username=$user->getUsername();

        $id = intval($id);
        $entityManager = $this->getDoctrine()->getManager();
        $messageRepository = $entityManager->getRepository(Message::class);
        $parentMessage = $messageRepository->findOneBy(array('id'=>$id));
        $sonMessage = $messageRepository->findByparent($id);
        $message = New Message();
        $form = $this->createForm(MessageType::class, $message);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $message = $form->getData();
            $now = new \Datetime('now + 8hours');
            $message->setCreatedAt($now);
            $message->setUpdatedAt($now);
            $message->setUser($username);
            $parentMessage->addChild($message);

            $entityManager->persist($parentMessage);
            $entityManager->persist($message);

            $entityManager->flush();

            return $this->redirectToRoute("user.message.fetch", ['id' => $id ]);
        }

        return $this->render("/message/sonlist.html.twig", array(
            "form" => $form->createView(),
            "parentMessage" => $parentMessage,
            "sonMessage" => $sonMessage,
        ));
    }

}
