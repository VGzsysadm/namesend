<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\UrlHelper;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use App\Service\Randomize;
use App\Security\Datasec;
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
    public function index(Request $request, Randomize $randomize, Datasec $libsec): Response
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
            $encrypted_message = $libsec->encrypt($brbmessage);
            $message->setMessage($encrypted_message);
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
    public function get_message(Request $request, MessageRepository $message, $url, Datasec $libsec): Response
    {
        try{
            $url = $request->attributes->get('url');
            $entityManager = $this->getDoctrine()->getManager();
            $message = $entityManager->getRepository(Message::class)->findOneBy(['url' => $url]);
            if ( !$message ) {
                throw $this->createNotFoundException('This message doesnt exist');
            }
            $encrypted_message = $message->getMessage();
            $decrypted_message = $libsec->decrypt($encrypted_message);
            $message->setMessage($decrypted_message);
            $entityManager->remove($message);
            $entityManager->flush();
            return $this->render('message/show_message.html.twig', [
                'message' => $message,
            ]);
        }
        catch(\Exception $e){
            error_log($e->getMessage());
            throw $this->createNotFoundException('This message doesnt exist');
        }
    }
}
