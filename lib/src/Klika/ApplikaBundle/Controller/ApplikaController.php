<?php

namespace Klika\ApplikaBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\SecurityContext;

//Entity
use Klika\ApplikaBundle\Entity\Modulo;

class ApplikaController extends Controller {

	private function repo ($entidad) {

    return $this->getDoctrine ()->getRepository ('KlikaApplikaBundle:' . $entidad);
  }

  public function showAction ($plantilla) {

    return $this->render ('KlikaApplikaBundle:Applika:' . $plantilla . '.html.twig');
  }

  //login
  public function loginAction() {

    $request = $this->getRequest();
    $session = $request->getSession();

    //obtiene el error de inicio de sesión si lo hay
    if ($request->attributes->has (SecurityContext::AUTHENTICATION_ERROR)) $error = $request->attributes->get (SecurityContext::AUTHENTICATION_ERROR);
    else {

        $error = $session->get (SecurityContext::AUTHENTICATION_ERROR);
        $session->remove (SecurityContext::AUTHENTICATION_ERROR);
    }

    return $this->render(
      'KlikaApplikaBundle:Applika:login.html.twig',
      array(
          'last_username' => $session->get (SecurityContext::LAST_USERNAME),
          'error'         => $error,
      )
    );
  }

  //carga el primer modulo para la ruta /applika/
  public function primeroAction () {

    $repo   = $this->repo ('Modulo');
    $modulo = $repo->primerModulo ();

    //se hace una redireccion a applika_modulo
    return $this->redirect ($this->generateUrl (
                                  'applika_modulo',
                                  array (
                                    'modulo_id' => $modulo->getId (),
                                    'modulo'    => $modulo->getSlug ()
                                  )
                                ), 301);
  }

  //menu (tree)
  public function menuAction () {

    $repo       = $this->repo ('Modulo');
    $query      = $repo->listarArray ();
    $options    = array (
                    'decorate'  => true,
                    'rootClose' => '<ul>',
                    'rootClose' => '</ul>',
                    'childOpen'  => function ($child) {

                      if (! $child ['lvl']) return '<li class="modulo">';
                                            return '<li>';
                    },
                    'childClose'    => '</li>',
                    'nodeDecorator' => function ($node) {

                      if (! $node ['lvl']) {

                        $repo   = $this->repo ('Modulo');
                        $nodo   = $repo->unico ($node ['id']);
                        $hijos  = $repo->children ($nodo);

                        if (count ($hijos)) {

                          $nodo = $repo->unico ($hijos [0]->getId ());
                          $url  = $this->generateUrl ('applika_modulo', array ('modulo_id' => $nodo->getId (), 'modulo' => $nodo->getSlug ()));

                          return sprintf ('<a id="modulo-%s" href="%s">%s</a>', $node ['id'], $url, $node ['nombre']);
                        }
                      }

                      $url = $this->generateUrl ('applika_modulo', array ('modulo_id' => $node ['id'], 'modulo' => $node ['slug']));
                      return sprintf ('<a id="modulo-%s" href="%s">%s</a>', $node ['id'], $url, $node ['nombre']);
    });

    $menu = $repo->buildTree ($query, $options);
    return new Response ($menu);
  }

  //miga de pan
  public function migaAction ($modulo, $ficha = false, $anadir = false) {

    $repo       = $this->repo ('Modulo');
    $miga       = $repo->getPath ($modulo);
    $root       = $miga [0];
    $rootHijos  = $repo->children ($root);

    if (count ($rootHijos)) $root = $repo->unico ($rootHijos [0]->getId ());

    return $this->render ('KlikaApplikaBundle:Applika:miga.html.twig', array ('miga' => $miga, 'root' => $root, 'ficha' => $ficha, 'anadir' => $anadir));
  }

  //paginación
  public function paginacionAction ($modulo, $limit, $pagina) {

    $npaginas = $this->repo ('Modulo')->npaginas ($modulo, $limit);

    return $this->render ('KlikaApplikaBundle:Applika:paginacion.html.twig', array ('modulo' => $modulo, 'npaginas' => $npaginas, 'pagina' => $pagina));
  }

  //navegación
  public function navegacionAction ($modulo, $ficha_id) {

    $repo = $this->repo ('Modulo');

    $siguiente  = $repo->navegacion ($modulo, $ficha_id, true);
    $anterior   = $repo->navegacion ($modulo, $ficha_id, false);

    return $this->render ('KlikaApplikaBundle:Applika:navegacion.html.twig', array ('modulo' => $modulo, 'siguiente' => $siguiente, 'anterior' => $anterior));
  }

  //modulo
  public function moduloAction ($modulo_id, $limit, $pagina) {

    $repo   = $this->repo ('Modulo');
    $modulo = $repo->unico ($modulo_id);

    if (! $modulo) throw $this->createNotFoundException ('No se ha encontrado ningún resultado.');

    //ningún padre del módulo puede estar desactivado
    /*foreach ($repo->getPath ($modulo) as $valor) if ($valor->isDesactivado ()) throw $this->createNotFoundException ('No se ha encontrado ningún resultado.');*/
    //porque en vez de hacer esto, no desactivamos automaticamente los submodulos cuando desactivamos un modulo con un lifecycle callback??

    if ($modulo->getTipo () == 2) return $this->arbol   ($repo, $modulo);
                                  return $this->rejilla ($repo, $modulo, $limit, $pagina);
  }

  //tipo = 1 => rejilla
  private function rejilla ($repo, $modulo, $limit, $pagina) {

    $filas = $repo->listarPagina ($modulo, $limit, $pagina);
    //if (! $filas) throw $this->createNotFoundException ('No se ha encontrado ningún resultado.');

    return $this->render ($modulo->getBundle () . ':Applika:rejilla-' . $modulo->getSlug () . '.html.twig', array ('modulo' => $modulo, 'filas' => $filas, 'limit' => $limit, 'pagina' => $pagina));
  }

  //tipo = 2 => arbol
  private function arbol ($repo, $modulo) {

    $ramas = $repo->listarArbol ($modulo);
    //if (! $ramas) throw $this->createNotFoundException ('No se ha encontrado ningún resultado.');

    $options = array (
                  'decorate' => true,
                  'rootOpen' => function ($tree) {

                    if (! $tree [0]['lvl']) return '<ul>';
                                            return '<ul>';
                  },
                  'rootClose' => '</ul>',
                  'childOpen' => function ($child) {

                    $desactivado = $child ['flag'] ? 'desactivado' : '';

                    return sprintf ('<li id="%s-%s" class="rama %s">', $child ['slug'], $child ['id'], $desactivado);
                  },
                  'childClose'    => '</li>',
                  'nodeDecorator' => function ($node) use ($modulo) {

                    return sprintf ('<a href="%s">%s</a>',
                                    $this->generateUrl ('applika_ficha_editar', array (
                                                                                'modulo_id' => $modulo->getId (),
                                                                                'modulo'    => $modulo->getSlug (),
                                                                                'ficha_id'  => $node ['id'],
                                                                                'ficha'     => $node ['slug'])
                                                                              ),
                                    $node ['nombre']);
                    });

    $arbol = $repo->buildTree ($ramas, $options);
    return $this->render ('KlikaApplikaBundle:Applika:arbol.html.twig', array ('modulo' => $modulo, 'arbol' => $arbol));
  }

  //ficha
  public function fichaAction (Request $request, $modulo_id, $ficha_id, $padre_id) {

    $repo = $this->repo ('Modulo');

    $modulo = $repo->unico ($modulo_id);
    if (! $modulo) throw $this->createNotFoundException ('No se ha encontrado ningún resultado.');

    $em = $this->getDoctrine()->getManager();

    if ($ficha_id) { //editar

      $ficha = $repo->ficha ($modulo, $ficha_id);
      if (! $ficha) throw $this->createNotFoundException ('No se ha encontrado ningún resultado.');

    }else{ //añadir

      $entidad = $em->getClassMetadata ($modulo->getBundle () . ':' . $modulo->getEntidad ());
      $ficha   = new $entidad->name;
    }

    $form = $repo->form ($modulo, $ficha, $this);

    if ($request->isMethod ('POST')) { //guardar

      $form->bind ($request);

      if ($form->isValid ()) {

        $tipo = $modulo->getTipo ();
        $data = $form->getData ();

        if (! $data->getFlag ()) $ficha->setFlag (0);

        if ($tipo == 2) {
          if ($padre_id) {

            $padre = $repo->ficha ($modulo, $padre_id);
            if (! $padre) throw $this->createNotFoundException ('Se ha producido un error.');

            $ficha->setParent ($padre);
          }
        }

        $ficha->salvar ($data);

        $em->persist ($ficha);
        $em->flush();

        //redireccion a la rejilla calculando la pagina
        $pagina = $tipo == 1 ? $repo->paginaFicha ($modulo, $ficha, modulo::LIMIT) : 1;

        return $this->redirect ($this->generateUrl (
                                  'applika_modulo',
                                  array (
                                    'modulo_id' => $modulo->getId (),
                                    'modulo'    => $modulo->getSlug (),
                                    'pagina'    => $pagina
                                  )
                                ), 301);
      }
    }

    return $this->render (
              $modulo->getBundle () . ':Applika:ficha-' .
              $modulo->getSlug ()   . '.html.twig',
              array (
                    'modulo'    => $modulo,
                    'ficha'     => $ficha,
                    'padre'     => $padre_id,
                    'form'      => $form->createView (),
                    'anadir'    => ! $ficha_id
                  ));
  }

  //eliminar fila
  public function eliminarAction (Request $request) {

    $repo     = $this->repo ('Modulo');
    $response = new Response ('KO');

    if ($request->isMethod ('POST')) {

      $modulo_id  = $request->request->get ('modulo_id');
      $ficha_id   = $request->request->get ('ficha_id');

      $modulo = $repo->unico ($modulo_id);
      if (! $modulo) return $response;

      $ficha = $repo->ficha ($modulo, $ficha_id);
      if (! $ficha) return $response;

      $ficha->eliminar ();

      //eliminar registro
      $em = $this->getDoctrine()->getManager();
      $em->remove ($ficha);
      $em->flush();

      $response = new Response ('OK');
    }

    return $response;
  }

  public function activarDesactivarAction (Request $request) {

    $repo     = $this->repo ('Modulo');
    $response = new Response ('KO');

    if ($request->isMethod ('POST')) {

      $modulo_id    = $request->request->get ('modulo_id');
      $ficha_id     = $request->request->get ('ficha_id');
      $desactivar   = $request->request->get ('desactivar');

      $modulo = $repo->unico ($modulo_id);
      if (! $modulo) return $response;

      $ficha = $repo->ficha ($modulo, $ficha_id);
      if (! $ficha) return $response;

      $desactivado = $ficha->isDesactivado ();
      if (($desactivado && $desactivar) || (! $desactivado && ! $desactivar)) return new Response ('KO');

      $flag = $desactivado ? $ficha->getFlag () - 1 : $ficha->getFlag () + 1;
      $ficha->setFlag ($flag);

      $em = $this->getDoctrine()->getManager();
      $em->flush();

      $response = new Response ('OK');
    }

    return $response;
  }
}