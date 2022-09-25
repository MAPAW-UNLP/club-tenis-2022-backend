<?php

namespace App\Controller;

use App\Entity\Persona;
use App\Entity\Reserva;
use App\Service\CustomService as ServiceCustomService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

// use App\Services\CustomService;

/**
 * @Route(path="/api")
 */

class ReservaController extends AbstractController
{
    /**
     * @Route("/reservas", name="app_reservas", methods={"GET"})
     */
    public function getReservas(
        ServiceCustomService $cs
    ): Response {
        $reservas = $this->getDoctrine()->getRepository(Reserva::class)->findAll();

        $rtaReservas =  array();
        foreach ($reservas as $reserva) {
                $reservaRta = $cs->reservaFromObject($reserva);
                array_push($rtaReservas, $reservaRta);
        }


        $resp = array(
            "rta" => "error",
            "detail" => "Se produjo un error en la consulta de las reservas."
        );
        if (isset($rtaReservas)) {

            $resp['rta'] =  "ok";
            $resp['detail'] = $rtaReservas;
        }


        return $this->json($resp);
    }
}
