<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use App\Form\RegistroType;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Doctrine\ORM\EntityManagerInterface;
use phpDocumentor\Reflection\Location;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\RedirectResponse;
//session_start();
class InicioController extends AbstractController
{
    /**
     * @Route("/", name="inicio")
     */
    public function index(): Response
    {
        //echo $_SESSION['app_user_provider'];
        //echo $_SESSION['id'];
        //echo $_POST['_username'];
        return $this->render('inicio/index.html.twig', [
            'controller_name' => 'InicioController',
        ]);

    }
    /**
     * @Route("/alogin", name="login")
     */
    public function login(AuthenticationUtils $authenticationUtils,EntityManagerInterface $em,Request $request)
    {
        // $error = $authenticationUtils->getLastAuthenticationError();
        // $lastUsername = $authenticationUtils->getLastUsername();
        /*if($_SERVER['REQUEST_METHOD']=='POST'){
            echo "hola";
        }*/
        /*if ($request->getMethod() == 'POST') {
            $username = $request->request->get('username');
            echo $username;
            $dql = "select a from App\Entity\User a where a.email=:email";
            $query = $em->createQuery($dql);
            $query->setParameter('email',$username);
            $user = $query->getResult();
            $_SESSION['id']=$user[0]->getId();
            // Do something with the post data
        }*/
        /*if(empty($_POST['_username'])==false){
            
            //$user= $em->find("App\Entity\User",1);
            $dql = "select a from App\Entity\User a where a.email=:email";
            $query = $em->createQuery($dql);
            $query->setParameter('email',$_POST['_username']);
            $user = $query->getResult();
            $_SESSION['id']=$user[0]->getId();
            
            
        }*/
        // return $this->render('inicio/login.html.twig', [
        //     'controller_name' => 'InicioController',
        //     'error' => $error,
        //     'last_username' => $lastUsername,
        // ]);
    }
    /**
     * @Route("/registro", name="registro")
     */
    public function registro(UserPasswordHasherInterface $passwordHasher, Request $request, EntityManagerInterface $em): Response
    {
        $user = new User();
        $form = $this->createForm(RegistroType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if($form->get('tipo')->getData()==1){
                $a = ['ROLE_ALUMNO'];
            }else{
                $a = ['ROLE_PROFE'];
            }
            $user->setRoles($a);
            $hashedPassword = $passwordHasher->hashPassword(
                $user,
                $form->get('password')->getData()
            );
            if ($form->get('password')->getData() == $form->get('passwordConfirm')->getData()) {

                $user->setPassword($hashedPassword);

                try {
                    $em->persist($user);
                    $em->flush();
                } catch (\Exception $e) {
                    return new Response("Error");
                }
                $redireccion = new RedirectResponse ('/');
                $redireccion->setTargetUrl('http://localhost:4200/');
                return $redireccion;
            }
            return new Response("Contraseña no coincide");
        }
        return $this->render('inicio/register.html.twig', [
            'controller_name' => 'InicioController',
            'form' => $form->createView()
        ]);
    }
    /**
     * @Route("/logout", name="app_logout",methods={"GET"})
     */
    public function logout(): void
    {
       throw new \Exception('Don\'t forget to activate logout in security.yaml');
    }
}
