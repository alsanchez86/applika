<?php

namespace Klika\CatalogoBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

//Entity
use Klika\CatalogoBundle\Entity\Categoria;
use Klika\CatalogoBundle\Entity\Articulo;

class CatalogoController extends Controller {

    private function repo ($entidad) {

        return $this->getDoctrine ()->getRepository ('KlikaCatalogoBundle:' . $entidad);
    }

    //articulo.categoria -> falta tener en cuenta el orden de las categorias activas a la hora de coger la primera de cada articulo
    //mirar el metodo articulo->getCategoria ();
    public function inicioAction ($limit) {

		$articulos = $this->repo ('Articulo')->listarFlag (Articulo::INICIO, $limit);
        //if (! $articulos) throw $this->createNotFoundException ('No se ha encontrado ningún artículo.');

        return $this->render ('KlikaCatalogoBundle:Catalogo:inicio.html.twig', array ('articulos' => $articulos));
    }

    //OK
    public function categoriaAction ($categoria_id, $limit, $pagina) {

        $categoria = $this->repo ('Categoria')->unico ($categoria_id);
        if (! $categoria) throw $this->createNotFoundException ('No se ha encontrado ningún artículo.');

        $articulos = $this->repo ('Articulo')->listarPagina ($categoria_id, $limit, $pagina);
        if (! $articulos) throw $this->createNotFoundException ('No se ha encontrado ningún artículo.');

        return $this->render ('KlikaCatalogoBundle:Catalogo:categoria.html.twig', array ('categoria' => $categoria, 'articulos' => $articulos, 'limit' => $limit, 'pagina' => $pagina));
    }

    //OK
    public function paginacionAction ($categoria, $limit, $pagina) {

        $npaginas = $this->repo ('Articulo')->npaginas ($categoria->getId (), $limit);

        return $this->render ('KlikaCatalogoBundle:Catalogo:paginacion.html.twig', array ('categoria' => $categoria, 'npaginas' => $npaginas, 'pagina' => $pagina));
    }

    //OK
    public function articuloAction ($categoria_id, $articulo_id) {

        $articulo = $this->repo ('Articulo')->unicoCategoria ($categoria_id, $articulo_id);
        if (! $articulo) throw $this->createNotFoundException ('No se ha encontrado ningún artículo.');

        $categoria = $this->repo ('Categoria')->unico ($categoria_id);
        if (! $categoria) throw $this->createNotFoundException ('No se ha encontrado ningún artículo.');

        return $this->render ('KlikaCatalogoBundle:Catalogo:articulo.html.twig', array ('categoria' => $categoria, 'articulo' => $articulo));
    }

    //OK
    public function relacionadosAction ($articulo_id) {

        $relacionados = $this->repo ('Articulo')->relacionados ($articulo_id);

        return $this->render ('KlikaCatalogoBundle:Catalogo:relacionados.html.twig', array ('relacionados' => $relacionados));
    }

    //OK
    public function migaAction ($categoria, $articulo = false) {

        $repo   = $this->repo ('Categoria');
        $miga   = $repo->getPath ($categoria);

        return $this->render ('KlikaCatalogoBundle:Catalogo:miga.html.twig', array ('miga' => $miga, 'articulo' => $articulo));
    }

    public function navegacionAction ($categoria, $articulo_id) {

        $categoria_id = $categoria->getId ();

        $siguiente  = $this->repo ('Articulo')->navegacion ($categoria_id, $articulo_id, true);
        $anterior   = $this->repo ('Articulo')->navegacion ($categoria_id, $articulo_id, false);

        return $this->render ('KlikaCatalogoBundle:Catalogo:navegacion.html.twig', array ('categoria' => $categoria, 'siguiente' => $siguiente, 'anterior' => $anterior));
    }

    //Falta el orden de las categorias
    public function menuCatalogoAction () {

        $repo       = $this->repo ('Categoria');
        $query      = $repo->listarArray ();
        $options    = array (
                        'decorate' => true,
                        'rootOpen' => function ($tree) {

                            if (! $tree [0]['lvl']) return '<ul id="menu-catalogo">';
                                                    return '<ul style="display: none">';
                        },
                        'rootClose' => '</ul>',
                        'childOpen' => function ($child) {

                            if (! $child ['lvl'])   return sprintf ('<li id="categoria-%d" class="categoria">', $child ['id']);
                                                    return sprintf ('<li id="categoria-%s">', $child ['id']);
                        },
                        'childClose'    => '</li>',
                        'nodeDecorator' => function ($node) {

                            return sprintf ('<a href="%s">%s</a>',
                                $this->generateUrl ('categoria', array ('categoria_id' => $node ['id'], 'categoria' => $node ['slug'])),
                                $node ['nombre']);
                    });

        $menu = $repo->buildTree ($query, $options);

        return $this->render ('KlikaCatalogoBundle:Catalogo:menu-catalogo.html.twig', array ('menu' => $menu));
    }

    //Falta el orden de las categorias
    public function menuCatalogoFooterAction () {

        $repo       = $this->repo ('Categoria');
        $query      = $repo->listarArray ();
        $options    = array (
                        'decorate'  => true,
                        'rootOpen'  => '',
                        'rootClose' => '',
                        'childOpen'  => function ($child) {

                            if (! $child ['lvl'])   return '<div class="col-xs-12 col-sm-4 col-md-3 col-lg-3"><ul><li>';
                                                    return '<ul><li>';
                        },
                        'childClose' => function ($child) {

                            if (! $child ['lvl'])   return '</li></ul></div>';
                                                    return '</li></ul>';
                        },
                        'nodeDecorator' => function ($node) {

                            return sprintf ('<a href="%s">%s</a>',
                                $this->generateUrl ('categoria', array ('categoria_id' => $node ['id'], 'categoria' => $node ['slug'])),
                                $node ['nombre']);
                    });

        $menu = $repo->buildTree ($query, $options);

        return new Response ($menu);
    }

    //menu-lateral-cesta
    public function menuCestaAction () {

        return $this->render ('KlikaCatalogoBundle:Catalogo:menu-cesta.html.twig');
    }
}
