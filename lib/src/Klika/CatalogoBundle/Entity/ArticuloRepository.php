<?php

namespace Klika\CatalogoBundle\Entity;

use Doctrine\ORM\EntityRepository;

class ArticuloRepository extends EntityRepository {

    public function unicoCategoria ($categoria_id, $articulo_id) {

        //devuelve un articulo por su id
        //comprueba que el articulo está asociado a la categoria
        //comprueba que la categoria y el articulo estén activos
        // = objeto

        $em         = $this->getEntityManager ();
        $query      = $em
                        ->createQuery ('
                            SELECT a
                            FROM KlikaCatalogoBundle:Articulo a
                            JOIN a.categorias c
                            WHERE
                                c.id = :categoria_id
                                AND a.id = :articulo_id
                                AND BIT_AND (c.flag, 1) = 0
                                AND BIT_AND (a.flag, 1) = 0
                            ORDER BY a.id ASC')
                        ->setParameters (array ('categoria_id' => $categoria_id, 'articulo_id' => $articulo_id));

        try                                         { return $query->getSingleResult(); }
        catch (\Doctrine\ORM\NoResultException $e)  { return null; }
    }

    public function listarFlag ($flag, $limit = null) {

        //lista los articulos activos que ademas coinciden con una operacion flag
        // = array de objetos

        $em     = $this->getEntityManager ();
        $query  = $em
                    ->createQuery ('
                        SELECT a
                        FROM KlikaCatalogoBundle:Articulo a
                        WHERE
                            BIT_AND (a.flag, :flag) = :flag
                            AND BIT_AND (a.flag, 1) = 0
                        ORDER BY a.id ASC')
                    ->setParameters (array ('flag' => $flag))
                    ->setMaxResults ($limit);

        return $query->getResult ();
    }

    public function listarPagina ($categoria_id, $limit = null, $pagina = 1) {

        //lista todos los articulos de una categoria filtrando por el limit y la pagina (ambos definidos en routing)
        //comprueba que la categoria está activa
        //solo devuelve los articulos activos ordenados por id
        // = array de objetos

        if (! $pagina || $pagina < 1) return null;

        $primero = ($pagina - 1) * $limit;

        $em         = $this->getEntityManager ();
        $query      = $em
                        ->createQuery ('
                            SELECT a
                            FROM KlikaCatalogoBundle:Articulo a
                            JOIN a.categorias c
                            WHERE
                                c.id = :id
                                AND BIT_AND (c.flag, 1) = 0
                                AND BIT_AND (a.flag, 1) = 0
                            ORDER BY a.id ASC')
                        ->setParameters (array ('id' => $categoria_id))
                        ->setFirstResult ($primero)
                        ->setMaxResults ($limit);

        return $query->getResult ();
    }

    public function npaginas ($categoria_id, $limit = null) {

        //cuenta los articulos activos de una categoria y devuelve el número de páginas resultado de dividir por un limit indicado
        //no tiene en cuenta si la categoria está activa
        // = int

        $em         = $this->getEntityManager ();
        $query      = $em
                        ->createQuery ('
                            SELECT a
                            FROM KlikaCatalogoBundle:Articulo a
                            JOIN a.categorias c
                            WHERE
                                c.id = :id
                                AND BIT_AND (a.flag, 1) = 0
                            ORDER BY a.id ASC')
                        ->setParameters (array ('id' => $categoria_id));

        $count = count ($query->getArrayResult ());
        return ceil ($count / $limit);
    }

    public function relacionados ($articulo_id, $limit = null) {

        //lista todos los relacionados activos de un articulo
        //comprueba que el articulo está activo
        //solo devuelve los relacionados activos
        // = array de objetos

        $em         = $this->getEntityManager ();
        $query      = $em
                        ->createQuery ('
                            SELECT r
                            FROM KlikaCatalogoBundle:Articulo r
                            JOIN r.articulos a
                            WHERE
                                a.id = :id
                                AND BIT_AND (a.flag, 1) = 0
                                AND BIT_AND (r.flag, 1) = 0
                            ORDER BY r.id ASC')
                        ->setParameters (array ('id' => $articulo_id))
                        ->setMaxResults ($limit);

        return $query->getResult ();
    }

    public function navegacion ($categoria_id, $articulo_id, $siguiente) {

        //ordenados por id
        $em     = $this->getEntityManager ();
        $query  = $em
                    ->createQuery ('
                        SELECT a
                        FROM KlikaCatalogoBundle:Articulo a
                        JOIN a.categorias c
                        WHERE
                            c.id = :categoria_id
                            AND a.id ' . ($siguiente ? '>' : '<') . ' :articulo_id
                            AND BIT_AND (c.flag, 1) = 0
                            AND BIT_AND (a.flag, 1) = 0
                        ORDER BY a.id' . ($siguiente ? ' ASC' : ' DESC'))
                    ->setParameters (array ('categoria_id' => $categoria_id, 'articulo_id' => $articulo_id))
                    ->setMaxResults (1);

        try                                         { return $query->getSingleResult(); }
        catch (\Doctrine\ORM\NoResultException $e)  { return null; }
    }

    public function form ($ficha, $controller) {

        return  $controller
                ->createFormBuilder ($ficha)
                ->add ('flag', 'hidden', array ('required' => false, 'attr' => array ('value' => 0)))
                ->add ('codigo')
                ->add ('nombre')
                ->add ('categorias') //campo tipo collection
                ->add ('entradilla')
                ->add ('texto')
                ->add ('aviso')
                ->add ('files', 'file', array ('required' => false, 'data_class' => null, 'attr' => array ('multiple' => 'multiple', 'accept' => 'image/*')))
                ->add ('removeFiles', 'hidden', array ('required' => false))
                ->getForm();
    }
}