<?php

namespace App\Controller;

use App\Entity\Cancha;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

    /**
     * @Route(path="/api")
     */

class CanchaController extends AbstractController
{
    /**
     * @Route("/canchas", name="app_canchas", methods={"GET"})
     */
    public function getCanchas(): Response
    {
        $canchas = $this->getDoctrine()->getRepository( Cancha::class )->findAll();

        return $this->json($canchas);
    }


    /**
     * @Route("/canchas", name="add_canchas", methods={"POST"})
     */
    public function addCancha(Request $request, ManagerRegistry $doctrine ): Response
    {

        $name = $request->request->get('nombre');

        $cancha = new Cancha();
        $cancha->setNombre($name);

        $em = $doctrine->getManager();
        $em->persist($cancha);
        $em->flush();

        return $this->json(($name));
    }


    /**
     * @Route("/canchas", name="mod_canchas", methods={"PUT"})
     */
    public function modCancha(Request $request, ManagerRegistry $doctrine ): Response
    {

        $id = $request->request->get('id');
        $name = $request->request->get('nombre');

        $em = $doctrine->getManager();
        $cancha = $em->getRepository(Cancha::class)->findOneById($id);
        dd($cancha);
        $cancha->setNombre($name);

        $em = $doctrine->getManager();
        $em->persist($cancha);
        $em->flush();

        return $this->json(($name));
    }

}
