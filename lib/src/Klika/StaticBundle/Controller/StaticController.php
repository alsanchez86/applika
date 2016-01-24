<?php

namespace Klika\StaticBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

//Entidades importadas
use Klika\StaticBundle\Entity\Contacto;

class StaticController extends Controller {

  private function repo ($entidad) {

    return $this->getDoctrine ()->getRepository ('KlikaStaticBundle:' . $entidad);
  }

  public function showAction ($plantilla) {

    return $this->render ('KlikaStaticBundle:Static:' . $plantilla . '.html.twig');
  }

  public function contactoAction (Request $request) {

    $contacto   = new Contacto ();
    $form       = $this->repo ('contacto')->form ($contacto, $this);
    $view       = $form->createView ();
    $contactoOK = false;

    if ($request->isMethod ('POST')) {

      $form->bind ($request);

      if ($form->isValid ()) {

        $contacto->setFlag (0);
        $contacto->setFecha (new \DateTime(date("Y-m-d")));

        //guardamos el contacto en la BD
        $em = $this->getDoctrine()->getManager();
        $em->persist ($contacto);
        $em->flush();

        //contactoOK (mostramos el modal)
        $contactoOK = true;

        //enviamos el email
        $message = \Swift_Message::newInstance()

        ->setSubject  ('xxx')
        ->setFrom     ($contacto->getNombre ())
        ->setTo       ('xxx')
        ->setBody (
            $this->renderView(
                'KlikaStaticBundle:Static:contacto.html.twig',
                array ('form' => $view, 'contactoOk' => $contactoOK)
            )
        );

        $this->get ('mailer')->send($message);
      }
    }

    return $this->render ('KlikaStaticBundle:Static:contacto.html.twig', array ('form' => $view, 'contactoOk' => $contactoOK));
  }
}