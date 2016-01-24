<?php

namespace Klika\StaticBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Contacto
 */
class Contacto {

CONST DESACTIVAR = 1;

    //salvar
    public function salvar ($data) {}
    public function eliminar () {}

    //operaciones con $flag
    public function isVisible () {

        return ($this->getFlag () & 1) == 0;
    }

    public function isDesactivado () {

        return ($this->getFlag () & self::DESACTIVAR) == self::DESACTIVAR;
    }
    //

    /**
     * @var integer
     */
    private $id;

    /**
     * @var integer
     */
    private $flag;

    /**
     * @var \DateTime
     */
    private $fecha;

    /**
     * @var string
     */
    private $nombre;

    /**
     * @var string
     */
    private $apellidos;

    /**
     * @var string
     */
    private $cifdni;

    /**
     * @var string
     */
    private $direccion;

    /**
     * @var string
     */
    private $cp;

    /**
     * @var string
     */
    private $poblacion;

    /**
     * @var string
     */
    private $provincia;

    /**
     * @var integer
     */
    private $pais;

    /**
     * @var string
     */
    private $telefono;

    /**
     * @var string
     */
    private $email;

    /**
     * @var string
     */
    private $consulta;

    /**
     * @var string
     */
    private $slug;


    ////////////
    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set flag
     *
     * @param integer $flag
     * @return Contacto
     */
    public function setFlag($flag)
    {
        $this->flag = $flag;

        return $this;
    }

    /**
     * Get flag
     *
     * @return integer
     */
    public function getFlag()
    {
        return $this->flag;
    }

    /**
     * Set fecha
     *
     * @param \DateTime $fecha
     * @return Contacto
     */
    public function setFecha($fecha)
    {
        $this->fecha = $fecha;

        return $this;
    }

    /**
     * Get fecha
     *
     * @return \DateTime
     */
    public function getFecha()
    {
        return $this->fecha;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     * @return Contacto
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Get nombre
     *
     * @return string
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * Set apellidos
     *
     * @param string $apellidos
     * @return Contacto
     */
    public function setApellidos($apellidos)
    {
        $this->apellidos = $apellidos;

        return $this;
    }

    /**
     * Get apellidos
     *
     * @return string
     */
    public function getApellidos()
    {
        return $this->apellidos;
    }

    /**
     * Set cifdni
     *
     * @param string $cifdni
     * @return Contacto
     */
    public function setCifdni($cifdni)
    {
        $this->cifdni = $cifdni;

        return $this;
    }

    /**
     * Get cifdni
     *
     * @return string
     */
    public function getCifdni()
    {
        return $this->cifdni;
    }

    /**
     * Set direccion
     *
     * @param string $direccion
     * @return Contacto
     */
    public function setDireccion($direccion)
    {
        $this->direccion = $direccion;

        return $this;
    }

    /**
     * Get direccion
     *
     * @return string
     */
    public function getDireccion()
    {
        return $this->direccion;
    }

    /**
     * Set cp
     *
     * @param string $cp
     * @return Contacto
     */
    public function setCp($cp)
    {
        $this->cp = $cp;

        return $this;
    }

    /**
     * Get cp
     *
     * @return string
     */
    public function getCp()
    {
        return $this->cp;
    }

    /**
     * Set poblacion
     *
     * @param string $poblacion
     * @return Contacto
     */
    public function setPoblacion($poblacion)
    {
        $this->poblacion = $poblacion;

        return $this;
    }

    /**
     * Get poblacion
     *
     * @return string
     */
    public function getPoblacion()
    {
        return $this->poblacion;
    }

    /**
     * Set provincia
     *
     * @param string $provincia
     * @return Contacto
     */
    public function setProvincia($provincia)
    {
        $this->provincia = $provincia;

        return $this;
    }

    /**
     * Get provincia
     *
     * @return string
     */
    public function getProvincia()
    {
        return $this->provincia;
    }

    /**
     * Set pais
     *
     * @param integer $pais
     * @return Contacto
     */
    public function setPais($pais)
    {
        $this->pais = $pais;

        return $this;
    }

    /**
     * Get pais
     *
     * @return integer
     */
    public function getPais()
    {
        return $this->pais;
    }

    /**
     * Set telefono
     *
     * @param string $telefono
     * @return Contacto
     */
    public function setTelefono($telefono)
    {
        $this->telefono = $telefono;

        return $this;
    }

    /**
     * Get telefono
     *
     * @return string
     */
    public function getTelefono()
    {
        return $this->telefono;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return Contacto
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set consulta
     *
     * @param string $consulta
     * @return Contacto
     */
    public function setConsulta($consulta)
    {
        $this->consulta = $consulta;

        return $this;
    }

    /**
     * Get consulta
     *
     * @return string
     */
    public function getConsulta()
    {
        return $this->consulta;
    }

    /**
     * Set slug
     *
     * @param string $slug
     * @return Contacto
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

}
