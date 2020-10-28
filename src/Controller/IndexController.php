<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\Session;

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
}
