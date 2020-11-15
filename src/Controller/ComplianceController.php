<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ComplianceController extends AbstractController
{
    /**
     * @Route("/privacy-policy", name="compliance")
     */
    public function index()
    {
        return $this->render('compliance/index.html.twig');
    }
}
