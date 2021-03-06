<?php

namespace Klika\ApplikaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

class Modulo {

CONST DESACTIVAR    = 1;
CONST LIMIT         = 6;

    public function __construct () {

        $this->articulos = new ArrayCollection ();
    }

    //operaciones con $flag
    public function isVisible () {

        return ($this->getFlag () & 1) == 0;
    }

    public function isDesactivado () {

        return ($this->getFlag () & self::DESACTIVAR) == self::DESACTIVAR;
    }

    //[TREE] RELACION CON ENTIDAD Modulo (uno a muchos - self-referencing)
    /**
     * @var integer
     */
    private $lft;

    /**
     * @var integer
     */
    private $rgt;

    /**
     * @var integer
     */
    private $root;

    /**
     * @var integer
     */
    private $lvl;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $children;

    /**
     * @var \Klika\ApplikaBundle\Entity\Modulo
     */
    private $parent;

    /**
     * Set lft
     *
     * @param integer $lft
     * @return Modulo
     */
    public function setLft($lft)
    {
        $this->lft = $lft;

        return $this;
    }

    /**
     * Get lft
     *
     * @return integer
     */
    public function getLft()
    {
        return $this->lft;
    }

    /**
     * Set rgt
     *
     * @param integer $rgt
     * @return Modulo
     */
    public function setRgt($rgt)
    {
        $this->rgt = $rgt;

        return $this;
    }

    /**
     * Get rgt
     *
     * @return integer
     */
    public function getRgt()
    {
        return $this->rgt;
    }

    /**
     * Set root
     *
     * @param integer $root
     * @return Modulo
     */
    public function setRoot($root)
    {
        $this->root = $root;

        return $this;
    }

    /**
     * Get root
     *
     * @return integer
     */
    public function getRoot()
    {
        return $this->root;
    }

    /**
     * Set lvl
     *
     * @param integer $lvl
     * @return Modulo
     */
    public function setLvl($lvl)
    {
        $this->lvl = $lvl;

        return $this;
    }

    /**
     * Get lvl
     *
     * @return integer
     */
    public function getLvl()
    {
        return $this->lvl;
    }

    /**
     * Add children
     *
     * @param \Klika\ApplikaBundle\Entity\Modulo $children
     * @return Modulo
     */
    public function addChild(\Klika\ApplikaBundle\Entity\Modulo $children)
    {
        $this->children[] = $children;

        return $this;
    }

    /**
     * Remove children
     *
     * @param \Klika\ApplikaBundle\Entity\Modulo $children
     */
    public function removeChild(\Klika\ApplikaBundle\Entity\Modulo $children)
    {
        $this->children->removeElement($children);
    }

    /**
     * Get children
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * Set parent
     *
     * @param \Klika\ApplikaBundle\Entity\Modulo $parent
     * @return Modulo
     */
    public function setParent(\Klika\ApplikaBundle\Entity\Modulo $parent = null)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Get parent
     *
     * @return \Klika\ApplikaBundle\Entity\Modulo
     */
    public function getParent()
    {
        return $this->parent;
    }

    /* CAPTADORES Y DEFINIDORES *///////////////////////////////////////////////////////////////
    /**
     * @var integer
     */
    private $id;

    /**
     * @var integer
     */
    private $flag;

    /**
     * @var integer
     */
    private $orden;

    /**
     * @var string
     */
    private $nombre;

    /**
     * @var string
     */
    private $slug;

    /**
     * @var string
     */
    private $bundle;

    /**
     * @var string
     */
    private $entidad;

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
     * @return Modulo
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
     * Set orden
     *
     * @param integer $orden
     * @return Modulo
     */
    public function setOrden($orden)
    {
        $this->orden = $orden;

        return $this;
    }

    /**
     * Get orden
     *
     * @return integer
     */
    public function getOrden()
    {
        return $this->orden;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     * @return Modulo
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
     * Set slug
     *
     * @param string $slug
     * @return Modulo
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

    /**
     * Set bundle
     *
     * @param string $bundle
     * @return Modulo
     */
    public function setBundle($bundle)
    {
        $this->bundle = $bundle;

        return $this;
    }

    /**
     * Get bundle
     *
     * @return string
     */
    public function getBundle()
    {
        return $this->bundle;
    }

    /**
     * Set entidad
     *
     * @param string $entidad
     * @return Modulo
     */
    public function setEntidad($entidad)
    {
        $this->entidad = $entidad;

        return $this;
    }

    /**
     * Get entidad
     *
     * @return string
     */
    public function getEntidad()
    {
        return $this->entidad;
    }

    /**
     * @var integer
     */
    private $tipo;


    /**
     * Set tipo
     *
     * @param integer $tipo
     * @return Modulo
     */
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;

        return $this;
    }

    /**
     * Get tipo
     *
     * @return integer
     */
    public function getTipo()
    {
        return $this->tipo;
    }
}
