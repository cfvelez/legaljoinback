<?php

namespace App\Controller\Api;

use App\Form\Type\ContactFormType;
use App\Repository\ContactRepository;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use App\Form\Model\ContactDto;
use App\Entity\Contact;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\Translation\TranslatorInterface;

class ContactController extends AbstractFOSRestController{

    /**
     * @Rest\Get(path="/contact")
     * @Rest\View(serializerGroups={"contact"}, serializerEnableMaxDepthChecks=true)
     */
     public function getAction(ContactRepository $contactRepository, TranslatorInterface $translator){
      $translated = $translator->trans('Symfony.hard',['name' => 'Carlos'],'contact');
      return View::create($translated, Response::HTTP_OK);
      return ['message' => $translated , 'data' => $contactRepository->findAll()];
     }

     /**
     * @Rest\Post(path="/contact")
     * @Rest\View(serializerGroups={"contact"}, serializerEnableMaxDepthChecks=true)
     */
    public function postAction(Request $request,EntityManagerInterface $em){
        $contactDto = new ContactDto();
        $form = $this->createForm(ContactFormType::class, $contactDto);
        $form->handleRequest($request);
        if($form->isValid() && $form->isSubmitted()){
         $contact = new Contact();
         $contact->setName($contactDto->name);
         $contact->setLastname($contactDto->lastname);
         $em->persist($contact);
         $em->flush();
         return $contact;
        }
        return $form;
     }
    
}