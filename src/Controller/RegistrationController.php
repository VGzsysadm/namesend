<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Suser;
use App\Form\RegistrationFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Contracts\Translation\TranslatorInterface;
use Doctrine\ORM\EntityManagerInterface;

/**
 * @Route(")
*/
class RegistrationController extends AbstractController
{
    private $em;
    private $session;
    public function __construct(EntityManagerInterface $em)
    {
        $this->session = new Session();
        $this->em = $em;
    }
    /**
     * @Route("/register", name="app_register")
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder, TranslatorInterface $translator): Response
    {
        $user = new User();
        $suser = new Suser();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $suser_ = $this->em->getRepository('App:Suser')->findOneBy(['email' => $user->getEmail()]);
            if (!$suser_) {
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
            $message = $translator->trans('Registration success.');
            $this->session->getFlashBag()->add("success", $message);
            return $this->redirectToRoute('app_login');
            }
            $message = $translator->trans('There is already an account with this email.');
            $this->session->getFlashBag()->add("danger", $message);
        }
        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}
