<?php

namespace App\Controller;

use App\Entity\Persona;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

    /**
     * @Route(path="/api")
     */

class PersonaController extends AbstractController
{
    /**
     * @Route("/personas", name="app_personas", methods={"GET"})
     */
    public function getPersonas(): Response
    {
        $personas = $this->getDoctrine()->getRepository( Persona::class )->findAll();
        return $this->json($personas);
    }
}
