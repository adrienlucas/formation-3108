<?php

namespace App\Controller;

use App\Gateway\NasaGateway;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class DangerController extends AbstractController
{
    /**
     * @Route("/danger", name="danger")
     */
    public function index(NasaGateway $nasaGateway)
    {
        $danger = $nasaGateway->isEarthInDanger();

        return $this->render('danger/index.html.twig', [
            'earth_in_danger' => $danger,
        ]);
    }
}
