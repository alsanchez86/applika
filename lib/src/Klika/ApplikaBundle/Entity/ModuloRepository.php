<?php

namespace Klika\ApplikaBundle\Entity;

use Gedmo\Tree\Entity\Repository\NestedTreeRepository;

class ModuloRepository extends NestedTreeRepository {

	public function unico ($id) {

        //devuelve un modulo por su id
        //comprueba que está activo
        //= objeto

        $em    = $this->getEntityManager ();
        $query = $em
                    ->createQuery ('
                        SELECT m
                        FROM KlikaApplikaBundle:Modulo m
                        WHERE m.id = :id AND BIT_AND (m.flag, 1) = 0')
                    ->setParameters (array ('id' => $id));

        try                                         { return $query->getSingleResult(); }
        catch (\Doctrine\ORM\NoResultException $e)  { return null; }
    }

    public function listarArray ($limit = null) {

        //lista todas los modulos activos
        //= array

        $em     = $this->getEntityManager ();
        $query  = $em
                    ->createQuery ('
                        SELECT m
                        FROM KlikaApplikaBundle:Modulo m
                        WHERE BIT_AND (m.flag, 1) = 0
                        ORDER BY m.root ASC, m.lft ASC, m.id ASC')
                    ->setMaxResults ($limit);

        return $query->getArrayResult ();
    }

    public function primerModulo () {

        //devuelve el primer módulo activo
        //= objeto

        $em    = $this->getEntityManager ();
        $query = $em
                    ->createQuery ('
                        SELECT m
                        FROM KlikaApplikaBundle:Modulo m
                        WHERE
                            m.parent IS NOT NULL
                            AND BIT_AND (m.flag, 1) = 0'
                        )
                        ->setMaxResults (1);

        try                                         { return $query->getSingleResult(); }
        catch (\Doctrine\ORM\NoResultException $e)  { return null; }
    }

    ////////////////////////////////////////////////////////////////////////////////
    // Métodos dinámicos en funcion de los campos 'bundle' y 'entidad' del módulo //
    ////////////////////////////////////////////////////////////////////////////////
    public function listarPagina ($modulo, $limit = null, $pagina = 1) {

        //lista todas las filas filtrando por el limit y la pagina (ambos definidos en routing)
        //= array de objetos

        if (! $pagina || $pagina < 1) return null;

        $primero = ($pagina - 1) * $limit;

        $em         = $this->getEntityManager ();
        $query      = $em
                        ->createQuery ('
                            SELECT f
                            FROM ' . $modulo->getBundle () . ':' . $modulo->getEntidad () . ' f
                            ORDER BY f.id ASC')
                        ->setFirstResult ($primero)
                        ->setMaxResults ($limit);

        return $query->getResult ();
    }

    public function listarArbol ($modulo) {

        //lista el arbol completo (no hay limit ni paginación)
        //= array

        $em     = $this->getEntityManager ();
        $query  = $em
                    ->createQuery ('
                        SELECT r
                        FROM ' . $modulo->getBundle () . ':' . $modulo->getEntidad () . ' r
                        ORDER BY r.root ASC, r.lft ASC, r.id ASC');

        return $query->getArrayResult ();
    }

    public function npaginas ($modulo, $limit = null) {

        //cuenta las filas de un modulo y devuelve el número de páginas resultado de dividir por un limit indicado
        //= int

        $em         = $this->getEntityManager ();
        $query      = $em
                        ->createQuery ('
                            SELECT f
                            FROM ' . $modulo->getBundle () . ':' . $modulo->getEntidad () . ' f
                            ORDER BY f.id ASC');

        $count = count ($query->getArrayResult ());
        return ceil ($count / $limit);
    }

    public function paginaFicha ($modulo, $ficha, $limit = null) {

        //devuelve la pagina de la rejilla donde se encuentra una ficha
        //= int

        $em         = $this->getEntityManager ();
        $query      = $em
                        ->createQuery ('
                            SELECT f
                            FROM ' . $modulo->getBundle () . ':' . $modulo->getEntidad () . ' f
                            ORDER BY f.id ASC');

        $array      = $query->getArrayResult ();
        $ficha_id   = $ficha->getId ();

        foreach ($array as $clave => $valor) if ($ficha_id == $valor ['id']) return ceil (($clave + 1) / $limit);
    }

    public function ficha ($modulo, $ficha_id) {

        //devuelve una ficha por su id
        //no se comprueba si el módulo está activo (se comprueba en el controlador)
        //= objeto

        $em    = $this->getEntityManager ();
        $query = $em
                    ->createQuery ('
                        SELECT f
                        FROM ' . $modulo->getBundle () . ':' . $modulo->getEntidad () . ' f
                        WHERE f.id = :id')
                    ->setParameters (array ('id' => $ficha_id));

        try                                         { return $query->getSingleResult(); }
        catch (\Doctrine\ORM\NoResultException $e)  { return null; }
    }

    public function form ($modulo, $ficha, $controller) {

        //devuelve un formulario pasandole la ficha
        //los campos del form son definidos en el repositorio ajeno, método form ()
        //= getForm ();

        $em   = $this->getEntityManager ()->getRepository ($modulo->getBundle () . ':' . $modulo->getEntidad ());
        $form = $em->form ($ficha, $controller);

        return $form;
    }

    public function navegacion ($modulo, $ficha_id, $siguiente) {

        //ordenados por id
        $em     = $this->getEntityManager ();
        $query  = $em
                    ->createQuery ('
                        SELECT f
                        FROM ' . $modulo->getBundle () . ':' . $modulo->getEntidad () . ' f
                        WHERE f.id ' . ($siguiente ? '>' : '<') . ' :ficha_id
                        ORDER BY f.id' . ($siguiente ? ' ASC' : ' DESC'))
                    ->setParameters (array ('ficha_id' => $ficha_id))
                    ->setMaxResults (1);

        try                                         { return $query->getSingleResult(); }
        catch (\Doctrine\ORM\NoResultException $e)  { return null; }
    }
}