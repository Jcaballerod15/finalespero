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
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\Security;

use function PHPUnit\Framework\isNull;

header('Access-Control-Allow-Origin: *');

class UsuarioController extends AbstractFOSRestController
{
    /**
     * @Rest\Get("api/usuario/{email}", name="usuario")
     * @Rest\View(serializerGroups={"user"}, serializerEnableMaxDepthChecks=true)
     */
    public function usuario(Security $security, EntityManagerInterface $em,$email){
        $dql = "select a from App\Entity\User a where a.email=:email";
        $query = $em->createQuery($dql);
        $query->setParameter('email',$email);
        $user = $query->getResult();
        if(isNull($user)){
            $respuesta = $user;
        }else{
            $respuesta = [
                'ok' => false,
                'usuario' => 'usuario no encontrado'
            ];
           
        }
        return $respuesta;
    }
    /**
     * @Rest\Get("api/login/{email}/{pass}", name="login")
     * @Rest\View(serializerGroups={"user"}, serializerEnableMaxDepthChecks=true)
     */
    public function login(Security $security, EntityManagerInterface $em,$email,$pass){
        $dql = "select a from App\Entity\User a where a.email=:email";
        $query = $em->createQuery($dql);
        $query->setParameter('email',$email);
        $petis = $query->getResult();
        $user = $petis[0];
        $password = $pass;
        if(password_verify($password,$user->getPassword())){
            $respuesta = [
                'ok' => true,
                // 'user' => $user,
                'rol' => $user->getRoles()[0]
            ];
        }else{
            $respuesta = [
                'ok' => false,
                'rol' => 'no existe'
            ];
        }
        return $respuesta;
    }
    /**
     * @Rest\Get("api/clasesuser/{email}", name="clasesuser")
     * @Rest\View(serializerGroups={"clase"}, serializerEnableMaxDepthChecks=true)
     */
    public function clasesuser(Security $security, EntityManagerInterface $em,$email){
        $dql = "select a from App\Entity\User a where a.email=:email";
        $query = $em->createQuery($dql);
        $query->setParameter('email',$email);
        $user = $query->getResult();
        $clases = $user[0]->getPertenece();
        $respuesta=[];
        for ($i=0; $i < count($clases); $i++) { 
            $crear = [
                'id' => $clases[$i]->getId(),
                'descrip' => $clases[$i]->getDescripclase(),
                'titulo' => $clases[$i]->getTitulo(),
                'idprofe' => $clases[$i]->getIdprofe()->getEmail()
            ];
            $respuesta[] = $crear;
        }
        return $respuesta;
    }
    /**
     * @Rest\Get("api/clasesprofe/{email}", name="clasesprofe")
     * @Rest\View(serializerGroups={"clase"}, serializerEnableMaxDepthChecks=true)
     */
    public function clasesprofe(Security $security, EntityManagerInterface $em,$email){
        $dql = "select a from App\Entity\User a where a.email=:email";
        $query = $em->createQuery($dql);
        $query->setParameter('email',$email);
        $user = $query->getResult();
        $clases = $user[0]->getClases();
        $respuesta=[];
        for ($i=0; $i < count($clases); $i++) { 
            $crear = [
                'id' => $clases[$i]->getId(),
                'descrip' => $clases[$i]->getDescripclase(),
                'titulo' => $clases[$i]->getTitulo(),
                'idprofe' => $clases[$i]->getIdprofe()->getEmail()
            ];
            $respuesta[] = $crear;
        }
        return $respuesta;
    }
    /**
     * @Rest\Get("api/salaschat/{email}", name="salaschat")
     * @Rest\View(serializerGroups={"sala"}, serializerEnableMaxDepthChecks=true)
     */
    public function salaschat(Security $security, EntityManagerInterface $em,$email){
        $dql = "select a from App\Entity\User a where a.email=:email";
        $query = $em->createQuery($dql);
        $query->setParameter('email',$email);
        $user = $query->getResult();
        $dql = "select a from App\Entity\Sala a where a.idpersona1=:id";
        $query = $em->createQuery($dql);
        $query->setParameter('id',$user[0]->getId());
        $salas1 = $query->getResult();
        $dql = "select a from App\Entity\Sala a where a.idpersona2=:id";
        $query = $em->createQuery($dql);
        $query->setParameter('id',$user[0]->getId());
        $salas2 = $query->getResult();
        $respuesta=[];
        for ($j=0; $j < count($salas1); $j++) { 
            $crear = [
                'id' => $salas1[$j]->getId(),
                'nombrereceptor' => $salas1[$j]->getIdpersona2()->getNombre(),
                'emailreceptor' => $salas1[$j]->getIdpersona2()->getEmail()
            ];
            $respuesta[] = $crear;
        }
        for ($i=0; $i < count($salas2); $i++) { 
                $crear = [
                    'id' => $salas2[$i]->getId(),
                    'nombrereceptor' => $salas2[$i]->getIdpersona1()->getNombre(),
                    'emailreceptor' => $salas2[$i]->getIdpersona1()->getEmail()
                ];
            $respuesta[] = $crear;
        }
        return $respuesta;
    }
    /**
     * @Rest\Post("api/crearclase", name="crearclase")
     * @Rest\View(serializerGroups={"clase"}, serializerEnableMaxDepthChecks=true)
     */
    public function crearclase(Security $security, EntityManagerInterface $em, Request $request){
        $email=$request->request->get('emailviejo');
        $dql = "select a from App\Entity\User a where a.email=:email";
        $query = $em->createQuery($dql);
        $query->setParameter('email',$email);
        $user = $query->getResult();
        if($user[0]->getRoles()[0]=='ROLE_PROFE'){
            $clase = new Clase();
            $clase->setDescripclase($request->request->get('descrip'));
            $clase->setTitulo($request->request->get('titulo'));
            $clase->setClaveprivada(null);
            $clase->setIdprofe($user[0]);
            $em->persist($clase);
            $em->flush();
        }
        $redireccion = new RedirectResponse ('/');
        $redireccion->setTargetUrl('http://localhost:4200/');
        return $redireccion;
    }
    /**
     * @Rest\Post("api/eliminarclase", name="eliminarclase")
     * @Rest\View(serializerGroups={"clase"}, serializerEnableMaxDepthChecks=true)
     */
    public function eliminarclase(Security $security, EntityManagerInterface $em, Request $request){
        try{
            $id = $request->request->get('idfuera');
            $eliminar = $em->find(Clase::class,$id);
            $em->remove($eliminar);
            $em->flush();
        }catch (\Exception $e) {
        }
           
            $redireccion = new RedirectResponse ('/');
            $redireccion->setTargetUrl('http://localhost:4200/');
            return $redireccion;
    }
    /**
     * @Rest\Post("api/unirseclase", name="unirseclase")
     * @Rest\View(serializerGroups={"clase"}, serializerEnableMaxDepthChecks=true)
     */
    public function unirseclase(Security $security, EntityManagerInterface $em, Request $request){
            $email=$request->request->get('email');
            $dql = "select a from App\Entity\User a where a.email=:email";
            $query = $em->createQuery($dql);
            $query->setParameter('email',$email);
            $user = $query->getResult();
            $idclase=$request->request->get('idclase');
            $dql = "select a from App\Entity\Clase a where a.id=:idclase";
            $query = $em->createQuery($dql);
            $query->setParameter('idclase',$idclase);
            $clase = $query->getResult();
            $pertence = 1;
            for ($i=0; $i < count($user[0]->getPertenece()); $i++) { 
                if($user[0]->getPertenece()[$i]->getId()==$idclase){
                    $pertence = 2;
                }
            }
            if($pertence == 1){
                $clase[0]->addUser($user[0]);
            }
            $em->persist($clase[0]);
            $em->flush();
            
        $redireccion = new RedirectResponse ('/');
        $redireccion->setTargetUrl('http://localhost:4200/');
        return $redireccion;
    }
    /**
     * @Rest\Post("api/editardatosuser", name="editardatosuser")
     * @Rest\View(serializerGroups={"user"}, serializerEnableMaxDepthChecks=true)
     */
    public function editardatosuser(Security $security, EntityManagerInterface $em, Request $request){
        $email=$request->request->get('emailviejo');
        $dql = "select a from App\Entity\User a where a.email=:email";
        $query = $em->createQuery($dql);
        $query->setParameter('email',$email);
        $user = $query->getResult();
        $user[0]->setNombre($request->request->get('nombre'));
        $em->persist($user[0]);
        $em->flush();
            $respuesta = [
                'ok' => true,
                'usaurio' => $user
            ];
        $redireccion = new RedirectResponse ('/');
        $redireccion->setTargetUrl('http://localhost:4200/');
        return $redireccion;
    }
    // public function vueltaEditUser(){

    // }
}
