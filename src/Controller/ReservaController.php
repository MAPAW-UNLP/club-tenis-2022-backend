<?php

namespace App\Controller;

use App\Entity\Reserva;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

    /**
     * @Route(path="/api")
     */

class ReservaController extends AbstractController
{
    /**
     * @Route("/reservas", name="app_reservas", methods={"GET"})
     */
    public function getReservas(): Response
    {
        $reservas = $this->getDoctrine()->getRepository( Reserva::class )->findAll();
        return $this->json($reservas);
    }
}
