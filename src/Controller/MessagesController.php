<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\HttpFoundation\UrlHelper;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

use App\Service\Randomize;
use App\Service\passwordGenerator;
use App\Security\Datasec;
use App\Entity\Message;
use App\Entity\User;
use App\Entity\messagePassword;
use App\Form\MessageType;
use App\Repository\MessageRepository;

/**
 * @Route("message")
*/
class MessagesController extends AbstractController
{
    private $session;
    private $urlHelper;
    public function __construct(UrlHelper $urlHelper)
    {
        $this->session = new Session();
        $this->urlHelper = $urlHelper;
    }

    /**
     * @Route("/new", name="main", methods={"GET","POST"})
     */
    public function index(Request $request, Randomize $randomize, Datasec $libsec, passwordGenerator $pgen, UserPasswordEncoderInterface $encoder): Response
        {
            $message = new Message();
            $user = new User();
            $form = $this->createForm(MessageType::class , $message);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid())
            {
                $mPassword = new messagePassword();
                if ( $form->getData()->getProtection()== null)
                {
                    $message->setProtection(false);
                } else{
                    $message->setProtection(true);
                    $password = $pgen->password_generate();
                    $encoded = password_hash($password, PASSWORD_ARGON2I, ['memory_cost' => 2048, 'time_cost' => 4, 'threads' => 3]);
                    $message->setMessagePassword($mPassword->setPassword($encoded));
                    $message->setMessagePassword($mPassword->setCount(5));
                }
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
                if ($form->getData()->getProtection()!= null) {
                    $this->session->getFlashBag()->add('secret', $password);
                    $this->session->getFlashBag()->add('secret2', $password);
                }
                return $this->redirectToRoute('main');
            }

            return $this->render('message/index.html.twig',[
                'message' => $message,
                'form' => $form->createView()
                ]);
        }
        /**
         * @Route("/{url}", name="people_show", methods={"GET","POST"})
         */
        public function get_message(Request $request, MessageRepository $message, $url, Datasec $libsec, TranslatorInterface $translator): Response
            {
                $url = $request->attributes->get('url');
                $entityManager = $this->getDoctrine()->getManager();
                $message = $entityManager->getRepository(Message::class)->findOneBy(['url' => $url]);
                if (!$message)
                    {
                        throw $this->createNotFoundException('This message doesnt exist');
                    }
                try
                {
                    if ($request->isMethod('POST'))
                    {
                        if ($message->getProtection() == 1 ) {
                            $password = $request->get('_password');
                            $tmp = $message->getMessagePassword();
                            $destination_hash = $tmp->getPassword();
                            if (password_verify($password, $destination_hash)) {
                                $encrypted_message = $message->getMessage();
                                $decrypted_message = $libsec->decrypt($encrypted_message);
                                $message->setMessage($decrypted_message);
                                $message->setProtection(0);
                                $entityManager->remove($message);
                                $entityManager->flush();
                                return $this->render('message/show_message.html.twig', ['message' => $message, ]);
                            } else {
                                $y = $message->getMessagePassword();
                                if ( $y->getCount() == 1 ) {
                                    $msg = $translator->trans('The message has been deleted');
                                    $this->session->getFlashBag()->add("danger", $msg);
                                    $entityManager->remove($message);
                                    $entityManager->flush();
                                } else {
                                    $y->setCount($y->getCount()-1);
                                    $entityManager->flush();
                                    $msg = $translator->trans($y->getCount(). ' '.' Attempts left for unlock the message.');
                                    $this->session->getFlashBag()->add("danger", $msg);
                                }
                            }
                            return $this->render('message/show_message.html.twig', ['message' => $message]);
                        }
                        if ($message->getProtection() == 0 ) {
                        $encrypted_message = $message->getMessage();
                        $decrypted_message = $libsec->decrypt($encrypted_message);
                        $message->setMessage($decrypted_message);
                        $entityManager->remove($message);
                        $entityManager->flush();
                        return $this->render('message/show_message.html.twig', ['message' => $message, ]);
                        }
                    }
                    return $this->render('message/show_message.html.twig');
                }
                catch(\Exception $e)
                {
                    error_log($e->getMessage());
                    throw $this->createNotFoundException('This message doesnt exist');
                }
            }
        }