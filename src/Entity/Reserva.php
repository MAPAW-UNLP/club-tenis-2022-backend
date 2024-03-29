<?php

namespace App\Entity;

use App\Repository\ReservaRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ReservaRepository::class)
 */
class Reserva
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $cancha_id;

    /**
     * @ORM\Column(type="date")
     */
    private $fecha;

    /**
     * @ORM\Column(type="time")
     */
    private $hora_ini;

    /**
     * @ORM\Column(type="time")
     */
    private $hora_fin;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $persona_id;

    /**
     * @ORM\Column(type="boolean")
     */
    private $replica;

    /**
     * @ORM\Column(type="integer")
     */
    private $estado_id;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $idTipoClase;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCanchaId(): ?int
    {
        return $this->cancha_id;
    }

    public function setCanchaId(int $cancha_id): self
    {
        $this->cancha_id = $cancha_id;

        return $this;
    }

    public function getFecha(): ?\DateTimeInterface
    {
        return $this->fecha;
    }

    public function setFecha(\DateTimeInterface $fecha): self
    {
        $this->fecha = $fecha;

        return $this;
    }

    public function getHoraIni(): ?\DateTimeInterface
    {
        return $this->hora_ini;
    }

    public function setHoraIni(\DateTimeInterface $hora_ini): self
    {
        $this->hora_ini = $hora_ini;

        return $this;
    }

    public function getHoraFin(): ?\DateTimeInterface
    {
        return $this->hora_fin;
    }

    public function setHoraFin(\DateTimeInterface $hora_fin): self
    {
        $this->hora_fin = $hora_fin;

        return $this;
    }

    public function getPersonaId(): ?int
    {
        return $this->persona_id;
    }

    public function setPersonaId(?int $persona_id): self
    {
        $this->persona_id = $persona_id;

        return $this;
    }

    public function isReplica(): ?bool
    {
        return $this->replica;
    }

    public function setReplica(bool $replica): self
    {
        $this->replica = $replica;

        return $this;
    }

    public function getEstadoId(): ?int
    {
        return $this->estado_id;
    }

    public function setEstadoId(int $estado_id): self
    {
        $this->estado_id = $estado_id;

        return $this;
    }

    public function getIdTipoClase(): ?int
    {
        return $this->idTipoClase;
    }

    public function setIdTipoClase(?int $idTipoClase): self
    {
        $this->idTipoClase = $idTipoClase;

        return $this;
    }
}
