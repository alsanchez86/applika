<?php

namespace Klika\StaticBundle\Entity;

use Doctrine\ORM\EntityRepository;

class ContactoRepository extends EntityRepository {

    public function form ($ficha, $controller) {

        return  $controller
                ->createFormBuilder ($ficha)
                ->add ('flag',  'hidden',   array ('required' => false, 'attr' => array ('value' => 0)))
                ->add ('fecha', 'datetime', array ('widget' => 'single_text', 'time_widget' => 'single_text', 'format' => 'yyyy-mm-dd'))
                ->add ('nombre')
                ->add ('apellidos')
                ->add ('cifdni',    null, array ('required' => false))
                ->add ('direccion', null, array ('required' => false))
                ->add ('cp',        null, array ('required' => false))
                ->add ('poblacion')
                ->add ('provincia')
                ->add ('pais')
                ->add ('telefono',  null, array ('required' => false))
                ->add ('email')
                ->add ('consulta')
                ->getForm();
    }
}