<?php

namespace PadelBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations\RequestParam;     
use FOS\RestBundle\Request\ParamFetcher;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Response;

use PadelBundle\Entity\Reservations;
use PadelBundle\Form\ReservationsType;

/**
 * Reservations controller.
 *
 */
class ReservationsController extends FOSRestController
{
    /**
     *
     * @return Groups
     */
    public function getReservationAction($reservationsId)
    {
        $reservations = $this->getDoctrine()->getRepository('PadelBundle:Reservations')->find($reservationsId);
        
        if (!$reservations) {
            return new View('Reservations Not Found', Response::HTTP_NOT_FOUND);
        }
        
        return  $reservations;
    }
    /**
     * 
     * @return Reservations
     */
    public function getReservationsAction()
    {
        $em = $this->getDoctrine()->getManager();

        $reservations = $em->getRepository('PadelBundle:Reservations')->findAll();

        return $reservations;
    }
    
    /**
     * Deletes a Reservations entity.
     *
     */
    public function deleteReservationsAction($reservationsId)
    {
       
        $em = $this->getDoctrine()->getManager();
        $reservations = $em->getRepository('PadelBundle:Reservations')->find($reservationsId);

        if (!$reservations) {
             return new View('Reservations Not Found', Response::HTTP_NOT_FOUND);
        }

        $em->remove($reservations);
        $em->flush();
        
        return $this->view(null, Response::HTTP_NO_CONTENT);
    }
    
    /**
    * Creates a new Reservations entity.
    * @param ParamFetcher $paramFetcher Paramfetcher
    * 
    * @RequestParam(name="datetime", nullable=false, strict=true, description="datetime.")
    * @RequestParam(name="court", nullable=false, strict=true, description="court.")
    * @RequestParam(name="user", nullable=false, strict=true, description="user.")
    */
    public function postReservationsAction(ParamFetcher $paramFetcher)
    {
        $em = $this->getDoctrine()->getManager();
        
        $reservations = new Reservations();
        
        $datetime = \DateTime::createFromFormat('d/m/Y', $paramFetcher->get('datetime'));
        $court = $em->getRepository('PadelBundle:Courts')->find($paramFetcher->get('court'));
        $user = $em->getRepository('PadelBundle:Users')->find($paramFetcher->get('user'));
        
        if (!($user && $court && $datetime)) {
             return new View('User or Court Not Found', Response::HTTP_NOT_FOUND);
         }
        $reservations->setDatetime($datetime);
        $reservations->setCourt($court);
        $reservations->setUser($user);
        
        
        $em->persist($reservations);
        $em->flush();
        
        return new View($reservations, Response::HTTP_CREATED);    
    }

   /**
    * 
    * @param ParamFetcher $paramFetcher Paramfetcher
    * 
    * @RequestParam(name="datetime", nullable=true, strict=true, description="datetime.")
    * @RequestParam(name="court", nullable=true, strict=true, description="court.")
    * @RequestParam(name="user", nullable=true, strict=true, description="user.")
    */
    public function putReservationsAction(ParamFetcher $paramFetcher, $reservationsId)
    {
        $em = $this->getDoctrine()->getManager();

        $reservations = $em->getRepository('PadelBundle:Reservations')->find($reservationsId);

        if (!$reservations) {
            return new View('Reservations Not Found', Response::HTTP_NOT_FOUND);
        }
        
        if($paramFetcher->get('datetime')){
             $datetime = \DateTime::createFromFormat('d/m/Y', $paramFetcher->get('datetime'));
             if ($datetime){
                $reservations->setDatetime($datetime);
             }
        }
        if($paramFetcher->get('court')){
            $court = $em->getRepository('PadelBundle:Courts')->find($paramFetcher->get('court'));
            if ($court){
                $reservations->setCourt($court);
            }
        }
        if($paramFetcher->get('user')){
            $user = $em->getRepository('PadelBundle:Users')->find($paramFetcher->get('user'));
            if ($user){
                $reservations->setUser($user);
            }
        }
       
        $em->flush();

        return new View($reservations, Response::HTTP_OK);
    }
}
