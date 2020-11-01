<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Request;

class IndexController extends AbstractController
{
    private $session;

    public function __construct()
    {
        $this->session = new Session();
    }
    /**
     * @Route("/", name="index")
     */
    public function index()
    {
        return $this->redirectToRoute('main');
    }
    /**
     * @Route("/en", name="en")
     */
    public function en(Request $request)
    {
        $request->getSession()->set('_locale', 'en');
        return $this->redirectToRoute('app_login');
    }
    /**
     * @Route("/es", name="es")
     */
    public function es(Request $request)
    {
        $request->getSession()->set('_locale', 'es');
        return $this->redirectToRoute('app_login');
    }
}
