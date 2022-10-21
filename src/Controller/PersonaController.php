<?php

namespace App\Controller;

use App\Entity\Persona;
use DateTime;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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

    /**
     * @Route("/persona", name="app_alta_persona", methods={"POST"})
     */
    public function addPersona(Request $request, ManagerRegistry $doctrine ): Response
    {

        $data = json_decode( $request->getContent());
        $nombre = $data->nombre;
        $telefono = $data->telefono;
        $esAlumno = isset($data->esalumno) && $data->esalumno == 'true'? true: false;
        $fechaNac = $esAlumno && isset($data->fechanac) ? new DateTime($data->fechanac): null;
        

        $persona = new Persona();
        $persona->setNombre($nombre);
        $persona->setTelefono($telefono);
        $persona->setFechanac($fechaNac);
        $persona->setEsalumno($esAlumno);
        $persona->setVisible(true);

        $em = $doctrine->getManager();
        $em->persist($persona);
        $em->flush();

    
      
        if ($persona->getId() > 0){

            $resp['rta'] =  "ok";
            $resp['detail'] = "Persona dada de alta exitosamente.";

        } else {
            $resp['rta'] =  "error";
            $resp['detail'] = "Se produjo un error en el alta de la persona.";
        }

        return $this->json(($resp));
    }

}
