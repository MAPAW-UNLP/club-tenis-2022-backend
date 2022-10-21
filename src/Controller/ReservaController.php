<?php

namespace App\Controller;

use App\Entity\Alquiler;
use App\Entity\Cancha;
use App\Entity\Grupo;
use App\Entity\Persona;
use App\Entity\Reserva;
use App\Service\CustomService as ServiceCustomService;
use DateTime;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

use function PHPUnit\Framework\isNan;

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

    //Objeto con reservas de cada cancha en una fecha dada
    /**
     * @Route("/reservas_por_canchas_por_fecha", name="app_reservas_por_canchas_por_fecha", methods={"GET"})
     */
    public function getReservasPorCanchasPorFecha(
        ServiceCustomService $cs,
        Request $request
    ): Response {
        $canchaId = $request->query->get('cancha');
        $fecha = $request->query->get('fecha');

        $fechaPhp = new DateTime(date("Y-m-d", strtotime($fecha)));

        $reservasPorCanchaObj = [];

        $canchas = $this->getDoctrine()->getRepository(Cancha::class)->findAll();

        foreach ($canchas as $cancha) {
            if ((isset($canchaId) && ($cancha->getId() != $canchaId))) {
                continue;
            }

            $reservas = $this->getDoctrine()->getRepository(Reserva::class)->findReservasBycanchaIdAndDate($cancha->getId(), $fechaPhp);
            // dd($reservas, $cancha->getId(), $fechaPhp);
            $reservasObj = [];
            foreach ($reservas as $reserva) {

                array_push($reservasObj, $cs->reservaFromObject($reserva));
            }

            $canchaObj = array(
                "canchaId" => $cancha->getId(),
                "nombre" => $cancha->getNombre(),
                "reservas" => $reservasObj,
            );

            array_push($reservasPorCanchaObj, $canchaObj);
        }

        $resp = array(
            "rta" => "error",
            "detail" => "Se produjo un error en la consulta de las reservas por fecha y cancha."
        );
        if (isset($reservasPorCanchaObj) && (count($reservasPorCanchaObj) > 0)) {

            $resp['rta'] =  "ok";
            $resp['detail'] = $reservasPorCanchaObj;
        }


        return $this->json($resp);
    }


    /**
     * @Route("/reserva", name="app_alta_reserva", methods={"POST"})
     */
    public function postReserva(
        Request $request,
        ManagerRegistry $doctrine,
        ServiceCustomService $cs

    ): Response {

        $parametros = $request->request->all();


        $clienteParam = array(
            "nombre"    => isset($parametros['nombre']) ? $parametros['nombre']: null,
            "telefono"  => $parametros['telefono'] || null,
        );

        $reservaParam = array(
            "cancha_id"     =>  $parametros['cancha_id'],
            "fecha"         =>  new DateTime($parametros['fecha']),
            "hora_ini"      =>  new DateTime($parametros['hora_ini']),
            "hora_fin"      =>  new DateTime($parametros['hora_fin']),
            "persona_id"    =>  isset($parametros['persona_id'])? $parametros['persona_id']:null,
            "replica"       =>  (isset($parametros['replica']) && $parametros['replica'] == 'true')? true:false,
            "estado_id"     =>  0,
            "grupo"         => isset($parametros['grupo_ids'])? $parametros['grupo_ids']:null,
        );



        $em = $doctrine->getManager();

        $reserva = new Reserva();

        $reserva->setCanchaId($reservaParam['cancha_id']);
        $reserva->setFecha($reservaParam['fecha']);
        $reserva->setHoraIni($reservaParam['hora_ini']);
        $reserva->setHoraFin($reservaParam['hora_fin']);
        $reserva->setPersonaId($reservaParam['persona_id']);
        $reserva->setReplica($reservaParam['replica']);
        $reserva->setEstadoId($reservaParam['estado_id']);

        $reservaId =  $em->persist($reserva);
        

        $lastReservaId = (int) $cs->getLastReservaId();
        $idReserva = $lastReservaId + 1;

        if ($reservaParam['persona_id'] != null){
            $ids_grupo = explode(',',$reservaParam['grupo']);
            foreach($ids_grupo as $alumno_id){
                if (is_numeric($alumno_id)){
                    $grupo_alumno = new Grupo();
                    $grupo_alumno->setReservaId($idReserva);
                    $grupo_alumno->setPersonaId($alumno_id);
                    $em->persist($grupo_alumno);
                }
            }

        } else {
            $alquiler = new Alquiler();
            $alquiler->setNombre($clienteParam['nombre']);
            $alquiler->setTelefono($clienteParam['telefono']);
            $alquiler->setReservaId($idReserva);
            $em->persist($alquiler);
        }


        $em->flush();


        $resp = array();

        $resp['rta'] =  "ok";
        $resp['detail'] = "Reserva registrada correctamente";


        return $this->json($resp);
    }
}
