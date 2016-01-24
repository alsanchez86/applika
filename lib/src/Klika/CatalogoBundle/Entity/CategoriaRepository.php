<?php

namespace Klika\CatalogoBundle\Entity;

use Gedmo\Tree\Entity\Repository\NestedTreeRepository;

class CategoriaRepository extends NestedTreeRepository {

    public function unico ($id) {

        //devuelve una categoria por su id
        //comprueba que está activa
        // = objeto

        $em    = $this->getEntityManager ();
        $query = $em
                    ->createQuery ('
                        SELECT c
                        FROM KlikaCatalogoBundle:Categoria c
                        WHERE c.id = :id AND BIT_AND (c.flag, 1) = 0')
                    ->setParameters (array ('id' => $id));

        try                                         { return $query->getSingleResult();}
        catch (\Doctrine\ORM\NoResultException $e)  { return null; }
    }

    public function listarArray ($limit = null) {

        //lista todas las categorias activas
        // = array

        $em     = $this->getEntityManager ();
        $query  = $em
                    ->createQuery ('
                        SELECT c
                        FROM KlikaCatalogoBundle:Categoria c
                        WHERE BIT_AND (c.flag, 1) = 0
                        ORDER BY c.root ASC, c.lft ASC, c.id ASC')
                    ->setMaxResults ($limit);

        return $query->getArrayResult ();
    }

    public function form ($ficha, $controller) {

        return  $controller
                ->createFormBuilder ($ficha)
                ->add ('flag', 'hidden', array ('required' => false, 'attr' => array ('value' => 0)))
                ->add ('nombre')
                ->add ('texto')
                ->getForm();
    }
}