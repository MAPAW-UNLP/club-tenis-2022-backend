<?php

namespace App\Controller;

use App\Entity\Pagos;
use App\Entity\Persona;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\CustomService as ServiceCustomService;
use DateTime;
use Symfony\Component\HttpFoundation\Request;

    /**
     * @Route(path="/api")
     */

class PagosController extends AbstractController
{

     /**
     * @Route("/pagos", name="get_pagos", methods={"GET"})
     */
    public function getPagos(
        Request $request,
        ManagerRegistry $doctrine,
        ServiceCustomService $cs
    ): Response
    {

        $em = $doctrine->getManager();

        $pagos = $em->getRepository( Pagos::class )->findAll();

        // dd($pagos);
        $objPagos = array();
        foreach($pagos as $pago){

            $nombrePersona =  $em->getRepository( Persona ::class )->findOneById($pago->getIdPersona())->getNombre();

           array_push($objPagos, array(
            "idPersona" => $pago->getIdPersona(),
            "nombrePersona" => $nombrePersona,
            "idTipoClase" => $pago->getIdTipoClase(), 
            "cantidad" => $pago->getCantidad(), 
            "fecha" => $cs->getFormattedDate($pago->getFecha())));
        }
        return $this->json($objPagos);
    }


    /**
     * @Route("/pagos_por_persona", name="app_Pagos_personaId", methods={"GET"})
     */
    public function getPagosByPersonaId(
        Request $request,
        ManagerRegistry $doctrine,
        ServiceCustomService $cs
    ): Response
    {
        $personaId = $request->query->get('personaId');

        // dd($personaId);
        $em = $doctrine->getManager();

        $pagos = $em->getRepository( Pagos::class )->findPagosByPersonaId($personaId);

        // dd($pagos);
        $objPagos = array();
        foreach($pagos as $pago){

           array_push($objPagos, array(
            "idTipoClase" => $pago->getIdTipoClase(), 
            "cantidad" => $pago->getCantidad(), 
            "fecha" => $cs->getFormattedDate($pago->getFecha())));
        }
        return $this->json($objPagos);
    }

     /**
     * @Route("/pagos", name="add_pagos", methods={"POST"})
     */
    public function addPagos(
        Request $request, 
        // ManagerRegistry $doctrine,
        ServiceCustomService $cs

         ): Response
    {

        $data = json_decode( $request->getContent());
        $idPersona = $data->idPersona;
        $pagos = $data->pagos;  // string [{idTipoClase:cantidad},{idTipoClase:cantidad}]
        $pagosArray =  explode(',',$pagos);
        $fecha =  isset($data->fecha)? new DateTime($data->fecha) : null;
        
        foreach($pagosArray as $pago){
            $data = explode(':', $pago );
            $cs->registrarPago($idPersona,$data[0], $data[1], $fecha);
        }
    
        $resp = array(
            "rta"=> "ok",
            "detail"=> "Registro de pagos exitoso."
        );

        return $this->json(($resp));
    }
}
