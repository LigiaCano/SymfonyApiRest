<?php

namespace PadelBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations\RequestParam;     
use FOS\RestBundle\Request\ParamFetcher;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use PadelBundle\Entity\Courts;
use PadelBundle\Form\CourtsType;

/**
 * Courts controller.
 *
 */
class CourtsController extends FOSRestController
{
     /**
     *
     * @return Courts
     */
    public function getCourtAction($courtsId)
    {
        $courts = $this->getDoctrine()->getRepository('PadelBundle:Courts')->find($courtsId);
      
        if (!$courts) {
            return new View('No existe courts', Response::HTTP_BAD_REQUEST);
        }
        
        return $courts;
    }
    /**
     * retrieve all courts
     * TODO: add pagination
     *
     * @return Courts
     */
    public function getCourtsAction()
    {
        $em = $this->getDoctrine()->getManager();

        $courts = $em->getRepository('PadelBundle:Courts')->findAll();

        return $courts;
    }
    /**
     * Delete action
     * @var integer $courtsId Id of the entity
     * @return View
     */
    public function deleteCourtsAction($courtsId)
    {
       $em = $this->getDoctrine()->getManager();

       $courts = $em->getRepository('PadelBundle:Courts')->find($courtsId);

       if (!$courts) {
            return new View('No existe courts', Response::HTTP_BAD_REQUEST);
       }
       $em->remove($courts);
       $em->flush();
       
       return $this->view(null, Response::HTTP_NO_CONTENT);
    }
    
    /**
     * Creates a new Courts entity.
     * @param ParamFetcher $paramFetcher Paramfetcher
     * 
     * @RequestParam(name="active", nullable=true, strict=true, description="active.")
     * 
     * @return View
     *
     */
    public function postCourtAction(ParamFetcher $paramFetcher)
    {
        $courts = new Courts();
        if($paramFetcher->get('active')){
             $courts->setActive($paramFetcher->get('active'));
        }
       
        $em = $this->getDoctrine()->getManager();
        $em->persist($courts);
        $em->flush();
        
        return new View($courts, Response::HTTP_CREATED);
    }
    /**
    * Put action
    * @param ParamFetcher $paramFetcher Paramfetcher
    * 
    * @RequestParam(name="active", nullable=false, strict=true, description="active.")
     * 
    * @return View
    */
    
    public function putCourtAction($courtsId, ParamFetcher $paramFetcher)
    {      
       $em = $this->getDoctrine()->getManager();

       $courts = $em->getRepository('PadelBundle:Courts')->find($courtsId);
       
        if (!$courts) {
            return new View('No existe courts', Response::HTTP_BAD_REQUEST);
        }
       
       $courts->setActive($paramFetcher->get('active'));
       $em->flush();
        
       return new View($courts, Response::HTTP_OK);
    }
    

    
     /**
     * fill $courts with the json send in request and validates it
     *
     * returns an array of errors (empty if everything is ok)
     *
     * @return array
     */
    private function treatAndValidateRequest(Courts $courts, Request $request){
        // createForm is provided by the parent class
        $form = $this->createForm(
            new CourtsType(),
            $courts,
            array(
                'method' => $request->getMethod()
            )
        );
        $form->add('submit', 'submit', array('label' => 'Create'));
        $form->handleRequest($request);

        $errors = $this->get('validator')->validate($courts);
        return $errors;
    }
}
