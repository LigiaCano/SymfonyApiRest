<?php

namespace PadelBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations\RequestParam;     
use FOS\RestBundle\Request\ParamFetcher;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Response;

use PadelBundle\Entity\Users;

/**
 * Users controller.
 *
 */
class UsersController extends FOSRestController 
{
     /**
     * Collection get action
     *
     * @return Users
     */
    public function getUserAction($usersId)
    {
       $users = $this->getDoctrine()->getRepository('PadelBundle:Users')->find($usersId);
       
        if(!$users){
            return new View('Users Not Found', Response::HTTP_NOT_FOUND);
        }
        
        return $users;
    }
    /**
     * Collection get action
     * 
     *
     * @return Users

     */
    public function getUsersAction()
    {
        $em = $this->getDoctrine()->getManager();

        $users = $em->getRepository('PadelBundle:Users')->findAll();
        
        return $users;  
    }
    
    /**
     * Deletes a Users entity.
     * @var integer $usersId Id of the entity
     * @return View
     */
    public function deleteUsersAction($usersId)
    {
        $em = $this->getDoctrine()->getManager();
        
        $users = $em->getRepository('PadelBundle:Users')->find($usersId);

        if (!$users) {
            return new View('Users Not Found', Response::HTTP_NOT_FOUND);
        }

        $em->remove($users);
        $em->flush();
        
        return $this->view(null, Response::HTTP_NO_CONTENT);
    }    
    /**
    * Creates a new Users entity.
    * 
    * @param ParamFetcher $paramFetcher Paramfetcher
    * 
    * @RequestParam(name="username", nullable=false, strict=true, description="username.")
    * @RequestParam(name="email", nullable=false, strict=true, description="email.")
    * @RequestParam(name="enabled", nullable=false, strict=true, description="enabled.")
    * @RequestParam(name="salt", nullable=false, strict=true, description="salt.")
    * @RequestParam(name="password", nullable=false, strict=true, description="password.")
    * @RequestParam(name="locked", nullable=false, strict=true, description="lastLogin.")
    * @RequestParam(name="expired", nullable=false, strict=true, description="expired.")
    * @RequestParam(name="roles", nullable=false, strict=false, description="roles.")
    * @RequestParam(name="credentialsExpired", nullable=false, strict=true, description="credentialsExpired.")
    * @RequestParam(name="group", nullable=true, strict=true, description="group.")
    */
    public function postUsersAction(ParamFetcher $paramFetcher)
    {
        $em = $this->getDoctrine()->getManager();
        
        $users = new Users();
        
        $users->setUsername($paramFetcher->get('username'));
        $users->setUsernameCanonical($paramFetcher->get('username'));
        $users->setEmail($paramFetcher->get('email'));
        $users->setEmailCanonical($paramFetcher->get('email'));
        $users->setEnabled($paramFetcher->get('enabled'));
        $users->setSalt($paramFetcher->get('salt'));
        $users->setPassword($paramFetcher->get('password'));
        $users->setLocked($paramFetcher->get('locked'));
        $users->setExpired($paramFetcher->get('expired'));
        $users->setRoles($paramFetcher->get('roles'));
        $users->setCredentialsExpired($paramFetcher->get('credentialsExpired'));
        if($paramFetcher->get('group')){
             $group = $em->getRepository('PadelBundle:Groups')->find($paramFetcher->get('group'));
             if ($group){
                $users->addGroup($group);
             }
        }
        
        $em->persist($users);
        $em->flush();

        return new View($users, Response::HTTP_CREATED);      
    }


    /**
    * Edits an existing Users entity.
    * @param ParamFetcher $paramFetcher Paramfetcher
    * 
    * @RequestParam(name="username", nullable=true, strict=true, description="username.")
    * @RequestParam(name="email", nullable=true, strict=true, description="email.")
    * @RequestParam(name="enabled", nullable=true, strict=true, description="enabled.")
    * @RequestParam(name="password", nullable=true, strict=true, description="password.")
    *
    */
    public function putUsersAction(ParamFetcher $paramFetcher, $usersId)
    {
        $em = $this->getDoctrine()->getManager();

        $users = $em->getRepository('PadelBundle:Users')->find($usersId);
        
         if (!$users) {
            return new View('Users Not Found', Response::HTTP_NOT_FOUND);
        }
        
        if($paramFetcher->get('username')){
             $users->setUsername($paramFetcher->get('username'));
        }
        if($paramFetcher->get('email')){
             $users->setEmail($paramFetcher->get('email'));
        }
        if($paramFetcher->get('enabled')){
             $users->setEnabled($paramFetcher->get('enabled'));
        }
        if($paramFetcher->get('password')){
             $users->setPassword($paramFetcher->get('password'));
        }
        
        $em->flush();
        
        return new View($users, Response::HTTP_OK);
    }

}
