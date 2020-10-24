<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\UrlHelper;

use App\Service\Randomize;

use App\Entity\Message;
use App\Form\MessageType;
use App\Repository\MessageRepository;

/**
 * @Route("/message")
 */
class MessagesController extends AbstractController
{
    private $session;
    private $urlHelper;
    public function __construct(UrlHelper $urlHelper)
    {
        $this->urlHelper = $urlHelper;
        $this->session = new Session();
    }
    
    /**
     * @Route("/new", name="main", methods={"GET","POST"})
     */
    public function index(Request $request, Randomize $randomize): Response
    {
        $message = new Message();
        $form = $this->createForm(MessageType::class, $message);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $randomUrl = $randomize->randomize();
            $entityManager = $this->getDoctrine()->getManager();
            $message->setUrl($randomUrl);
            $messageBody = $form->getData()->getMessage();
            $brbmessage = nl2br($messageBody);
            #$hash = password_hash($brbmessage, PASSWORD_DEFAULT);
            $message->setMessage($brbmessage);
            $entityManager->persist($message);
            $entityManager->flush();
            $full_path = $this->urlHelper->getAbsoluteUrl($message->getUrl());
            $this->session->getFlashBag()->add('success', $full_path);
            return $this->redirectToRoute('main');
        }

        return $this->render('message/index.html.twig', [
            'message' => $message,
            'form' => $form->createView(),
        ]);
    }
    /**
     * @Route("/{url}", name="people_show", methods={"GET","POST"})
     */
    public function get_message(Request $request, MessageRepository $message, $url): Response
    {
        $url = $request->attributes->get('url');
        $entityManager = $this->getDoctrine()->getManager();
        $message = $entityManager->getRepository(Message::class)->findOneBy(['url' => $url]);
        #$message->setMessage("0");
        #$message->setUrl("0");
        #$message->setStatus(true);
        $entityManager->remove($message);
        $entityManager->flush();
        return $this->render('message/show_message.html.twig', [
            'message' => $message,
        ]);
    }
}
