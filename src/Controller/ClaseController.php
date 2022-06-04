<?php

namespace App\Controller;

use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\User;
use App\Entity\Clase;
use App\Entity\Sala;
use App\Entity\Mensaje;
use App\Entity\Contenido;
use PhpParser\Node\Expr\Cast\Array_;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\RedirectResponse;

header('Access-Control-Allow-Origin: *');

class ClaseController extends AbstractFOSRestController
{
    /**
     * @Rest\Get("api/verclases", name="verclases")
     * @Rest\View(serializerGroups={"clase"}, serializerEnableMaxDepthChecks=true)
     */
    public function verclases(Security $security, EntityManagerInterface $em){
        $clasestodas = $em->getRepository('App\Entity\Clase')->findAll();
        $respuesta=[];
        for ($i=0; $i < count($clasestodas); $i++) { 
            $crear = [
                'id' => $clasestodas[$i]->getId(),
                'descrip' => $clasestodas[$i]->getDescripclase(),
                'titulo' => $clasestodas[$i]->getTitulo(),
                'idprofe' => $clasestodas[$i]->getIdprofe()->getEmail()
            ];
            $respuesta[] = $crear;
        }
        return $respuesta;
    }
    /**
     * @Rest\Get("api/vercontenido/{id}", name="vercontenido")
     * @Rest\View(serializerGroups={"contenido"}, serializerEnableMaxDepthChecks=true)
     */
    public function vercontenido(Security $security, EntityManagerInterface $em,$id){
        $dql = "select a from App\Entity\Clase a where a.id=:id";
        $query = $em->createQuery($dql);
        $query->setParameter('id',$id);
        $clase = $query->getResult();
        return $clase[0]->getContenidos();
    }
    /**
     * @Rest\Get("api/usuariosclase/{ide}", name="usuariosclase")
     * @Rest\View(serializerGroups={"user"}, serializerEnableMaxDepthChecks=true)
     */
    public function usuariosclase(Security $security, EntityManagerInterface $em,$ide){
        $dql = "select a from App\Entity\Clase a where a.id=:id";
        $query = $em->createQuery($dql);
        $query->setParameter('id',$ide);
        $clase = $query->getResult();
        $usuariosclase = $clase[0]->getUsers();
        // $usuariosclase->array_push($clase[0]->getIdprofe());
        // array_unshift($usuariosclase,$clase[0]->getIdprofe());
        return $usuariosclase;
    }
    /**
     * @Rest\Post("api/crearcontenido", name="crearcontenido")
     * @Rest\View(serializerGroups={"clase"}, serializerEnableMaxDepthChecks=true)
     */
    public function crearcontenido(Security $security, EntityManagerInterface $em, Request $request){
        
        $contenido = new Contenido();
        $id = $request->request->get('idclase');
        $clase = $em->find(Clase::class,$id);
        $fechaHoy = new \DateTime("now");
        $contenido->setIdclase($clase);
        $contenido->setFechapublica($fechaHoy);
        if($request->request->get('tipo') == 1){
            $contenido->setTexto($request->request->get('url'));
            $contenido->setUrlimagen("");
            $contenido->setVideo("");
        }else if($request->request->get('tipo') == 2){
            $contenido->setUrlimagen($request->request->get('url'));
            $contenido->setVideo("");
            $contenido->setTexto("");
        }else if($request->request->get('tipo') == 3){
            $contenido->setVideo($request->request->get('url'));
            $contenido->setUrlimagen("");
            $contenido->setTexto("");
        }
        $em->persist($contenido);
        $em->flush();
        $redireccion = new RedirectResponse ('/');
        $redireccion->setTargetUrl('http://localhost:4200/');
        return $redireccion;
    }
    /**
     * @Rest\Post("api/eliminarcontenido", name="eliminarcontenido")
     * @Rest\View(serializerGroups={"clase"}, serializerEnableMaxDepthChecks=true)
     */
    public function eliminarcontenido(Security $security, EntityManagerInterface $em, Request $request){
        try{
            $id = $request->request->get('id');
            $eliminar = $em->find(Contenido::class,$id);
            $em->remove($eliminar);
            $em->flush();
        }catch (\Exception $e) {
        }
            
            $redireccion = new RedirectResponse ('/');
            $redireccion->setTargetUrl('http://localhost:4200/');
            return $redireccion;
    }
    /**
     * @Rest\Get("api/buscarclase/{texto}", name="buscarclase")
     * @Rest\View(serializerGroups={"clase"}, serializerEnableMaxDepthChecks=true)
     */
    public function buscarclase(Security $security, EntityManagerInterface $em, Request $request,$texto){
        $clasestodas = $em->getRepository('App\Entity\Clase')->findAll();
        $cadena_buscada = $texto;
        $clases1 = [];
        for ($i=0; $i < count($clasestodas); $i++) { 
            $cadena_de_texto = $clasestodas[$i]->getTitulo();
            // $posicion_coincidencia = strpos($cadena_de_texto, $cadena_buscada);
            similar_text ($cadena_de_texto , $cadena_buscada,$num);
            if($num>=50){
                $crear = [
                    'id' => $clasestodas[$i]->getId(),
                    'descrip' => $clasestodas[$i]->getDescripclase(),
                    'titulo' => $clasestodas[$i]->getTitulo(),
                    'idprofe' => $clasestodas[$i]->getIdprofe()->getEmail()
                ];
                $clases1[] = $crear;
            }
        }
        return $clases1;
    }
}