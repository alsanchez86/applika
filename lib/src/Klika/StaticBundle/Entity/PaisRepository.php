<?php

namespace Klika\StaticBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * PaisRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class PaisRepository extends EntityRepository {

	public function listarArray ($limit = null) {

        //lista todos los paises activos
        // = array

        $em     = $this->getEntityManager ();
        $query  = $em
                    ->createQuery ('
                        SELECT p.id, p.nombre
                        FROM KlikaStaticBundle:Pais p
                        WHERE BIT_AND (p.flag, 1) = 0
                        ORDER BY p.nombre ASC')
                    ->setMaxResults ($limit);

        return $query->getArrayResult ();
    }
}