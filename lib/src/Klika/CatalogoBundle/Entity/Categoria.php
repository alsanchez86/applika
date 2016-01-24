<?php

namespace Klika\CatalogoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

class Categoria {

CONST DESACTIVAR = 1;

    public function __construct () {

        $this->articulos = new ArrayCollection ();
    }

    //salvar
    public function salvar ($data) {

        $this->setBuscar ($data->getNombre ());
        $this->setOrden (0);

        if ($data->getTexto ()) $this->setTextoText (strip_tags ($data->getTexto ()));
    }

    public function eliminar () {}

    //operaciones con $flag
    public function isVisible () {

        return ($this->getFlag () & 1) == 0;
    }

    public function isDesactivado () {

        return ($this->getFlag () & self::DESACTIVAR) == self::DESACTIVAR;
    }


    //////////////////////////////
    public function __toString () {

        return (string) $this->getNombre ();
    }

    //////////////////////////////

    //RELACION CON ENTIDAD Articulo (muchos a muchos - bidireccional) //articulos_categorias
    private $articulos;

    /*public function setArticulo (\Klika\CatalogoBundle\Entity\Articulo $articulo) {

        $this->articulos [] = $articulo;
    }*/

    public function getArticulos () {

        return $this->articulos;
    }

    /**
     * Add articulos
     *
     * @param \Klika\CatalogoBundle\Entity\Articulo $articulos
     * @return Categoria
     */
    public function addArticulo (\Klika\CatalogoBundle\Entity\Articulo $articulo) {

        $this->articulos[] = $articulo;
        return $this;
    }

    /**
     * Remove articulos
     *
     * @param \Klika\CatalogoBundle\Entity\Articulo $articulos
     */
    public function removeArticulo (\Klika\CatalogoBundle\Entity\Articulo $articulo) {

        $this->articulos->removeElement($articulo);
    }

    //[TREE] RELACION CON ENTIDAD Categoria (uno a muchos - self-referencing) //categorias
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
     * @return Categoria
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
     * @return Categoria
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
     * @return Categoria
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
     * @return Categoria
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
     * @param \Klika\CatalogoBundle\Entity\Categoria $children
     * @return Categoria
     */
    public function addChild(\Klika\CatalogoBundle\Entity\Categoria $children)
    {
        $this->children[] = $children;

        return $this;
    }

    /**
     * Remove children
     *
     * @param \Klika\CatalogoBundle\Entity\Categoria $children
     */
    public function removeChild(\Klika\CatalogoBundle\Entity\Categoria $children)
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
     * @param \Klika\CatalogoBundle\Entity\Categoria $parent
     * @return Categoria
     */
    public function setParent(\Klika\CatalogoBundle\Entity\Categoria $parent = null)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Get parent
     *
     * @return \Klika\CatalogoBundle\Entity\Categoria
     */
    public function getParent()
    {
        return $this->parent;
    }

    //CAPTADORES y DEFINIDORES

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
    private $texto;

    /**
     * @var string
     */
    private $textoText;

    /**
     * @var string
     */
    private $slug;

    /**
     * @var string
     */
    private $buscar;



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
     * @return Categoria
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
     * @return Categoria
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
     * @return Categoria
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
     * Set texto
     *
     * @param string $texto
     * @return Categoria
     */
    public function setTexto($texto)
    {
        $this->texto = $texto;

        return $this;
    }

    /**
     * Get texto
     *
     * @return string
     */
    public function getTexto()
    {
        return $this->texto;
    }

    /**
     * Set textoText
     *
     * @param string $textoText
     * @return Categoria
     */
    public function setTextoText($textoText)
    {
        $this->textoText = $textoText;

        return $this;
    }

    /**
     * Get textoText
     *
     * @return string
     */
    public function getTextoText()
    {
        return $this->textoText;
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
     * Set buscar
     *
     * @param string $buscar
     * @return Categoria
     */
    public function setBuscar($buscar)
    {
        $this->buscar = $buscar;

        return $this;
    }

    /**
     * Get buscar
     *
     * @return string
     */
    public function getBuscar()
    {
        return $this->buscar;
    }

    /**
     * Set slug
     *
     * @param string $slug
     * @return Categoria
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

}
