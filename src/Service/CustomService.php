<?php

namespace App\Service;

date_default_timezone_set('America/Buenos_Aires');

use App\Entity\Alquiler;
use App\Entity\Cancha;
use App\Entity\Grupo;
use App\Entity\Persona;
use App\Entity\Reserva;
use DateTime;
use Doctrine\Persistence\ManagerRegistry;

class CustomService
{

    private $doctrine;
    private $estadosArr = ['ASIGNADO', 'CANCELADO', 'CONSUMIDO'];
    private $em;

    public function __construct(ManagerRegistry $doctrine)
    {

        $this->doctrine = $doctrine;
        $this->em = $this->doctrine->getManager();
    }


    public function reservaFromObject(Reserva $reserva)
    {

        // dd($reserva);

        $canchaNombre = $this->em->getRepository(Cancha::class)->findOneById($reserva->getCanchaId())->getNombre();


        $grupo = [];
        $titularReservaObj = null;

        if ($reserva->getPersonaId() != null && $reserva->getPersonaId() != 0) {
            // es clase
            
            $titularReservaObj = $this->getPersonaByPersonaId($reserva->getPersonaId());


            $grupoPersonasId = $this->em->getRepository(Grupo::class)->findPersonasGrupoIdByReservaId($reserva->getId());

            if (count($grupoPersonasId) > 0) {

                foreach ($grupoPersonasId as $itemGrupo) {

                    $personaObj = $this->getPersonaByPersonaId($itemGrupo->getPersonaId());
                    array_push($grupo, $personaObj);
                }
            }
        } else { //es alquiler

            $titularReservaObj = $this->getClienteByReservaId($reserva->getId());

        }


        $reservaObj = array(
            "reservaId" => $reserva->getId(),
            "canchaId" => $reserva->getCanchaid(),
            "canchaNombre" => $canchaNombre,
            "fecha" => $this->getFormattedDate($reserva->getFecha()),
            "horaIni" => $this->getFormattedTime($reserva->getHoraIni()),
            "horaFin" => $this->getFormattedTime($reserva->getHoraFin()),
            "profesorId" => $reserva->getPersonaId(),
            "titular" => $titularReservaObj,
            "replica" => $reserva->isReplica(),
            "estado" => $this->estadosArr[$reserva->getEstadoId()],
            "tipo" => $reserva->getPersonaId() != null ? 'CLASE' : 'ALQUILER',
            "grupo" => $grupo

        );

        return $reservaObj;
    }

    public function getFormattedTime(DateTime $time)
    {

        return $time->format('H:i');
    }

    public function getFormattedDate(DateTime $fecha)
    {
        // return $fecha->format('d-m-Y');
        return $fecha->format('Y-m-d');
    }

    public function getPersonaByPersonaId($personaId)
    {

        $persona = $this->em->getRepository(Persona::class)->findOneById($personaId);
        // dd($persona);
        $personaObj = array(
            "nombre" => $persona->getNombre(),
            // "apellido" => $persona->getApellido(),
            "telefono" => $persona->getTelefono(),
            //"fechanac" => $this->getFormattedDate($persona->getFechaNac()),
            "esalumno" => $persona->isEsAlumno(),
            "visible" => $persona->isVisible(),
        );

        return $personaObj;
    }

    public function getClienteByReservaId($reservaId){

        $cliente = $this->em->getRepository(Alquiler::class)->findAlquilerByReservaId($reservaId);
        if($cliente != null){
        $clienteObj = array(
            "nombre" => $cliente->getNombre(),
            // "apellido" => $cliente->getApellido(),
            "telefono" => $cliente->getTelefono(),
        );
        // dd($cliente, $clienteObj);

        return $clienteObj;}
    }

    public function getLastReservaId(){
        $id = $this->em->getRepository(Reserva::class)->getLastRecord();
        if ($id == null){
            return 0;
        }
        return $id[0]->getId();
    }

    public function formatearAlumno($alumno){

        $fechaNac = $alumno->getFechaNac()? $this->getFormattedDate($alumno->getFechaNac()):'';

        $alumnoFormateado = array(
            "id"    => $alumno->getId(),
            "nombre"    => $alumno->getNombre(),
            "telefono"  => $alumno->getTelefono(),
            "fechanac"  => $fechaNac,
            "saldo"     => 0,
        );

        return $alumnoFormateado;

    }


}
