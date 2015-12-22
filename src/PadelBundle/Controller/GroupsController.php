<?php

namespace PadelBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations\RequestParam;     
use FOS\RestBundle\Request\ParamFetcher;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Response;

use PadelBundle\Entity\Groups;


class GroupsController extends FOSRestController 
{
    /**
     *
     * @return Groups
     */
    public function getGroupAction($groupsId)
    {
        $groups = $this->getDoctrine()->getRepository('PadelBundle:Groups')->find($groupsId);
        
        if (!$groups) {
            return new View('Groups Not Found', Response::HTTP_NOT_FOUND);
        }
        
        return  $groups;
    }

    /**
     * Lists all Groups entities.
     * @return Groups
     */
    public function getGroupsAction()
    {
        $em = $this->getDoctrine()->getManager();

        $groups = $em->getRepository('PadelBundle:Groups')->findAll();

        return $groups;
    }
    
    /**
     * Deletes a Groups entity.
     * @var integer $groupsId Id of the entity
     * @return View
     */
    public function deleteGroupsAction($groupsId)
    {
        $em = $this->getDoctrine()->getManager();
        $groups = $em->getRepository('PadelBundle:Groups')->find($groupsId);

        if (!$groups) {
            return new View('Groups Not Found', Response::HTTP_NOT_FOUND);
        }

        $em->remove($groups);
        $em->flush();

        return $this->view(null, Response::HTTP_NO_CONTENT);
    }
    
    /**
    * @param ParamFetcher $paramFetcher Paramfetcher
    * 
    * @RequestParam(name="name", nullable=false, strict=true, description="name.")
    * @RequestParam(name="roles", nullable=false, strict=true, description="roles.")
    */
    public function postGroupAction(ParamFetcher $paramFetcher)
    {
        $em = $this->getDoctrine()->getManager();
        $groups = new Groups();
        $groups->setName($paramFetcher->get('name'));
        $groups->setRoles($paramFetcher->get('roles'));
        
        $em->persist($groups);
        $em->flush();
        return new View($groups, Response::HTTP_CREATED);    
    }
    
    public function postGroupUserAction($groupId, $userId) {
        $em = $this->getDoctrine()->getManager();
        $group = $em->getRepository('PadelBundle:Groups')->find($groupId);
        $user = $em->getRepository('PadelBundle:Users')->find($userId);
        if (!($group && $user)){
            return new View('Group or User Not Found', Response::HTTP_NOT_FOUND);
        }
        $group->addUser($user);
        $em->flush();
        return new View($group, Response::HTTP_CREATED); 
    }
    
    /**
    * @param ParamFetcher $paramFetcher Paramfetcher
    * 
    * @RequestParam(name="name", nullable=true, strict=true, description="name.")
    * @RequestParam(name="roles", nullable=true, strict=true, description="roles.")
    */
    public function putGroupsAction(ParamFetcher $paramFetcher, $groupsId)
    {
        $em = $this->getDoctrine()->getManager();

        $groups = $em->getRepository('PadelBundle:Groups')->find($groupsId);

        if (!$groups) {
            return new View('Groups Not Found', Response::HTTP_NOT_FOUND);
        }   
        if($paramFetcher->get('name')){
             $groups->setName($paramFetcher->get('name'));
        }
        if($paramFetcher->get('roles')){
             $groups->setRoles($paramFetcher->get('roles'));
        }
        $em->flush();
        
        return new View($groups, Response::HTTP_OK);
    }
   
}
