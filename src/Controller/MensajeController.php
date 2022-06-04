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
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\RedirectResponse;

header('Access-Control-Allow-Origin: *');

class MensajeController extends AbstractFOSRestController
{
    /**
     * @Rest\Get("api/mensaje/{id}/{email}", name="mensaje")
     * @Rest\View(serializerGroups={"mensaje"}, serializerEnableMaxDepthChecks=true)
     */
    public function mensaje(Security $security, EntityManagerInterface $em,$id,$email){
        $dql = "select a from App\Entity\User a where a.email=:id";
        $query = $em->createQuery($dql);
        $query->setParameter('id',$email);
        $user = $query->getResult();
        $sala = $em->find(Sala::class,$id);
        $contenidos = $sala->getMensajes();
        $respuesta = [];
        for($j = 0;$j < count($contenidos);$j++){
            $cosas = [
                'id' => $contenidos[$j]->getId(),
                'emailemisor' => $contenidos[$j]->getIdemisor()->getEmail(),
                'texto' => $contenidos[$j]->getTexto(),
                'fecha' => $contenidos[$j]->getFechaenvio()
            ];
            $respuesta[]=$cosas;
        }
        return $respuesta;
    }
    /**
     * @Rest\Post("api/crearsala", name="crearsala")
     * @Rest\View(serializerGroups={"mensaje"}, serializerEnableMaxDepthChecks=true)
     */
    public function crearsala(Security $security, EntityManagerInterface $em,Request $request){
        $idreceptor = $request->request->get('receptor');
        $emailemisor = $request->request->get('email');
        $dql = "select a from App\Entity\User a where a.email=:id";
        $query = $em->createQuery($dql);
        $query->setParameter('id',$emailemisor);
        $user1 = $query->getResult();

        $salas = $user1[0]->getSalas();
        $salacreada = -1;
        for($j = 0; $j<count($salas);$j++){
            if($salas[$j]->getIdpersona1()->getId()==$idreceptor){
                $salacreada = 10;
            }
            if($salas[$j]->getIdpersona2()->getId()==$idreceptor){
                $salacreada = 10;
            }
        }
        if($user1[0]->getId()==$idreceptor){
            $salacreada = 10;
        }
        if($salacreada == -1){
            $sala = new Sala();
            $user2 = $em->find(User::class,$idreceptor);
            $sala->setIdpersona1($user1[0]);
            $sala->setIdpersona2($user2);
            $em->persist($sala);
            $em->flush();
            // $user2->addSala($sala);
            // $em->persist($user2);
            // $em->flush();
        }
        $redireccion = new RedirectResponse ('/');
        $redireccion->setTargetUrl('http://localhost:4200/');
        return $redireccion;
    }
    /**
     * @Rest\Post("api/enviar", name="enviar")
     * @Rest\View(serializerGroups={"mensaje"}, serializerEnableMaxDepthChecks=true)
     */
    public function enviar(Security $security, EntityManagerInterface $em, Request $request){
        $email = $request->request->get('emailviejo');
        $dql = "select a from App\Entity\User a where a.email=:id";
        $query = $em->createQuery($dql);
        $query->setParameter('id',$email);
        $user = $query->getResult();
        $idsala = $request->request->get('sala');
        $mensaje = new Mensaje();
        $sala = $em->find(Sala::class,$idsala);
        $mensaje->setTexto($request->request->get('texto'));
        $mensaje->setIdsala($sala);
        $fechaHoy = new \DateTime("now");
        $mensaje->setFechaenvio($fechaHoy);
        $mensaje->setLeido(2);
        $mensaje->setIdemisor($user[0]);
        $em->persist($mensaje);
        $em->flush();
        $redireccion = new RedirectResponse ('/');
        $redireccion->setTargetUrl('http://localhost:4200/');
        return $redireccion;
    }
    
}