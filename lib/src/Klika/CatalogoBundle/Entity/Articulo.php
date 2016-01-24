<?php

namespace Klika\CatalogoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Klika\ApplikaBundle\Clases\ResizeImage;

class Articulo {

CONST DIR           = '/contenidos/articulos/';
CONST DESACTIVAR    = 1;
CONST INICIO        = 2;

    public function __construct () {

        $this->categorias   = new ArrayCollection ();
        $this->articulos    = new ArrayCollection ();
        $this->relacionados = new ArrayCollection ();
        $this->files        = array ();
        $this->removeFiles  = array ();
    }

    //salvar
    public function salvar ($data) {

        $this->setBuscar ($data->getNombre ());

        if ($data->getTexto ())       $this->setTextoText (strip_tags ($data->getTexto ()));
        if ($data->getRemoveFiles ()) $this->removeFiles ();
        if ($data->getFiles ())       $this->uploadFiles ();
    }

    public function eliminar () {

        $path = $this->getPath ();

        if (count ($path)) {

            $files = '';
            foreach ($path as $file) $files .= ',' . $file ['name'];

            $this->setRemoveFiles ($files);
            $this->removeFiles ();
        }
    }

    //operaciones con $flag
    public function isVisible () {

        return ($this->getFlag () & 1) == 0;
    }

    public function isDesactivado () {

        return ($this->getFlag () & self::DESACTIVAR) == self::DESACTIVAR;
    }

    public function isInicio () {

        return ($this->getFlag () & self::INICIO) == self::INICIO;
    }

    //propiedad que devuelve la primera categoria del articulo ordenado por el campo orden
    //esta propiedad la he creado yo manualmente
    /*private $categoria;

    public function setCategoria (array $categorias) {

        foreach ($categorias as $categoria) {


        }

        $categoria->setArticulo ($this);
        $this->categorias [] = $categoria;
    }

    public function getCategoria () {

        return 9;

        $categorias = $this->getCategorias ();
        $array      = array ();

        //montar el array con las categorias visibles
        foreach ($categorias as $categoria) if ($categoria->isVisible ()) $array [] = $categoria;

        //ordenar el array por el campo orden

        //extraer primer elemento del array
        return array_values ($array)[0];
    }*/
    public function getCategoria () {

        $categorias = $this->getCategorias ();
        $array      = array ();

        //montar el array con las categorias visibles
        foreach ($categorias as $categoria) if ($categoria->isVisible ()) $array [] = $categoria;

        //ordenar el array por el campo orden

        //extraer primer elemento del array
        return array_values ($array)[0];
    }


    /////

    //RELACION CON ENTIDAD "Categoria" (muchos a muchos - bidireccional) //articulos_categorias
    private $categorias;

    public function getCategorias () {

        return $this->categorias;
    }

    /**
     * Add categorias
     *
     * @param \Klika\CatalogoBundle\Entity\Categoria $categoria
     * @return Articulo
     */
    public function addCategoria (\Klika\CatalogoBundle\Entity\Categoria $categoria) {

        $categoria->addArticulo ($this);
        $this->categorias[] = $categoria;

        return $this;
    }

    /**
     * Remove categorias
     *
     * @param \Klika\CatalogoBundle\Entity\Categoria $categorias
     */
    public function removeCategoria (\Klika\CatalogoBundle\Entity\Categoria $categoria) {

        $this->categorias->removeElement($categoria);
    }



    //RELACION CON ENTIDAD "Articulo" (muchos a muchos - self-referencing) //articulos_relacionados
    //desde un articulo relacionado, consulta los articulos a los que está relacionado
    private $articulos;

    public function getArticulos () {

        return $this->articulos;
    }

    /**
     * Add articulos
     *
     * @param \Klika\CatalogoBundle\Entity\Articulo $articulo
     * @return Articulo
     */
    public function addArticulo (\Klika\CatalogoBundle\Entity\Articulo $articulo) {

        //$articulo->addRelacionado ($this); //provocaria un bucle sin fin
        $this->articulos [] = $articulo;
        return $this;
    }

    /**
     * Remove articulos
     *
     * @param \Klika\CatalogoBundle\Entity\Articulo $articulo
     */
    public function removeArticulo (\Klika\CatalogoBundle\Entity\Articulo $articulo) {

        $this->articulos->removeElement ($articulo);
    }

    //Relacionados
    private $relacionados;

    public function getRelacionados () {

        return $this->relacionados;
    }

    /**
     * Add relacionados
     *
     * @param \Klika\CatalogoBundle\Entity\Articulo $relacionado
     * @return Articulo
     */
    public function addRelacionado (\Klika\CatalogoBundle\Entity\Articulo $relacionado) {

        $relacionado->addArticulo ($this);
        $this->relacionados [] = $relacionado;
        return $this;
    }

    /**
     * Remove relacionados
     *
     * @param \Klika\CatalogoBundle\Entity\Articulo $relacionado
     */
    public function removeRelacionado (\Klika\CatalogoBundle\Entity\Articulo $relacionado) {

        $this->relacionados->removeElement ($relacionado);
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
     * @var string
     */
    private $codigo;

    /**
     * @var string
     */
    private $nombre;

    /**
     * @var string
     */
    private $entradilla;

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
    private $aviso;

    /**
     * @var string
     */
    private $buscar;

    /**
     * @var string
     */
    private $slug;

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
     * @return Articulo
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
     * Set codigo
     *
     * @param string $codigo
     * @return Articulo
     */
    public function setCodigo($codigo)
    {
        $this->codigo = $codigo;

        return $this;
    }

    /**
     * Get codigo
     *
     * @return string
     */
    public function getCodigo()
    {
        return $this->codigo;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     * @return Articulo
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
     * Set entradilla
     *
     * @param string $entradilla
     * @return Articulo
     */
    public function setEntradilla($entradilla)
    {
        $this->entradilla = $entradilla;

        return $this;
    }

    /**
     * Get entradilla
     *
     * @return string
     */
    public function getEntradilla()
    {
        return $this->entradilla;
    }

    /**
     * Set texto
     *
     * @param string $texto
     * @return Articulo
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
     * @return Articulo
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
     * Set buscar
     *
     * @param string $buscar
     * @return Articulo
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
     * Set aviso
     *
     * @param string $aviso
     * @return Articulo
     */
    public function setAviso($aviso)
    {
        $this->aviso = $aviso;

        return $this;
    }

    /**
     * Get aviso
     *
     * @return string
     */
    public function getAviso()
    {
        return $this->aviso;
    }

    /**
     * Set slug
     *
     * @param string $slug
     * @return Articulo
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

    //Propiedades y métodos para la subida y el borrado de imágenes en los artículos
    /**
     * @var string
     */
    private $path;

    /**
     * Set path
     *
     * @param string $path
     * @return Articulo
     */
    public function setPath (array $path) {

        $this->path = serialize ($path);
        return $this;
    }

    /**
     * Get path
     *
     * @return string
     */
    public function getPath () {

        return unserialize ($this->path);
    }

    private $files;

    public function setFiles (array $files) {

        $this->files = $files;
        return $this;
    }

    public function getFiles () {

        return $this->files;
    }

    protected function getUploadRootDir () {

        return __DIR__ . '/../../../../../' . self::DIR;
    }

    public function uploadFiles () {

        $files  = $this->getFiles ();
        $path   = $this->path ? $this->getPath () : array ();
        $count  = $this->path ? count ($path) : 0;
        $dir    = $this->getUploadRootDir ();

        if (! realpath ($dir . '/thumbnail')) mkdir ($dir . '/thumbnail');

        foreach ($files as $file) {

            if ($file !== null) {

                $name = md5 (microtime ());
                $ext  = $file->guessExtension ();

                $path []    = array ('name' => $name, 'ext' => $ext);
                $original   = $name . '.' . $ext;

                $file->move ($dir , $original);
                $this->thumbnail ($dir, $original);
            }
        }

        $this->setPath ($path);
    }

    private function thumbnail ($dir, $original) {

        $resize = new ResizeImage ($dir . $original);

        $resize->resizeTo (400, 400, 'maxwidth');
        $resize->saveImage ($dir . '/thumbnail/' . $original);
    }

    private $removeFiles;

    public function setRemoveFiles ($removeFiles) {

        $this->removeFiles = $removeFiles;
        return $this;
    }

    public function getRemoveFiles () {

        return $this->removeFiles;
    }

    public function removeFiles () {

        if (! $this->path || ! count ($this->getPath ())) return;

        $removes = explode (',', $this->getRemoveFiles ());
        $path    = $this->getPath ();

        foreach ($removes as $remove) {

            foreach ($path as $key => $file) {

                if ($remove == $file ['name']) {

                    $archivo    = '.' . self::DIR . $file ['name'] . '.' . $file ['ext'];
                    $resize     = '.' . self::DIR . '/thumbnail/' . $file ['name'] . '.' . $file ['ext'];

                    if (file_exists ($archivo)) unlink ($archivo);
                    if (file_exists ($resize))  unlink ($resize);

                    unset ($path [$key]);
                }
            }
        }

        $this->setPath ($path);
    }
}
