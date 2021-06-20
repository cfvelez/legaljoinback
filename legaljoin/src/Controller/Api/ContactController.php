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
use Monolog\Logger;
use PhpParser\Node\Stmt\TryCatch;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\Translation\TranslatorInterface;

class ContactController extends AbstractFOSRestController{

    /**
     * @var contactRepository
     */
    private $contactRepository;
    /**
     * @var EntityManagerInterface
     */
    private $em;

     /**
     * @var TranslatorInterface
     */

    private $translator;

     /**
     * @var LoggerInterface
     */

    private $logger;

    public function __construct(ContactRepository $contactRepository, 
                                EntityManagerInterface $em,
                                TranslatorInterface $translator, LoggerInterface $logger)
    {
        $this->contactRepository = $contactRepository;
        $this->em = $em;
        $this->logger = $logger;
        $this->translator = $translator;
    }

    /**
     * @Rest\Get(path="/contact")
     * @Rest\View(serializerGroups={"contact"}, serializerEnableMaxDepthChecks=true)
     */
     public function getAction(){
      return View::create($this->contactRepository->findAll(), Response::HTTP_OK);
     }

     /**
     * @Rest\Get(path="/contact/{id}")
     * @Rest\View(serializerGroups={"contact"}, serializerEnableMaxDepthChecks=true)
     */
    public function getByIdAction(string $id){
      $contact = $this->contactRepository->find($id);
      $statusCode = $contact ? Response::HTTP_OK : Response::HTTP_BAD_REQUEST;
      $data = $contact ?? $this->translator->trans('Contact.notFound',[],'contact');
      return View::create($data, $statusCode);
     }

     /**
     * @Rest\Post(path="/contact")
     * @Rest\View(serializerGroups={"contact"}, serializerEnableMaxDepthChecks=true)
     */
    public function createAction(Request $request){
        $contactDto = new ContactDto();
        $form = $this->createForm(ContactFormType::class, $contactDto);
       
        try{
          $form->handleRequest($request);
          if($form->isValid() && $form->isSubmitted()){
            $contact = new Contact();
            $contact->setName($contactDto->name);
            $contact->setLastname($contactDto->lastname);
            $this->em->persist($contact);
            $this->em->flush();
            return $contact;
          }
          return $form;
        }
        catch(\Exception $e) {
          return View::create('Bad request!', Response::HTTP_BAD_REQUEST);  
        }
     }

     /**
     * @Rest\Post(path="/contact/{id}")
     * @Rest\View(serializerGroups={"contact"}, serializerEnableMaxDepthChecks=true)
     */
    public function updateAction(string $id, Request $request){
      $contact = $this->contactRepository->find($id);
      $contactDto = new ContactDto();
      $form = $this->createForm(ContactFormType::class, $contactDto);
      try{
        $form->handleRequest($request);
        if($contact && $form->isValid() && $form->isSubmitted()){
          $contact->setName($contactDto->name);
          $contact->setLastname($contactDto->lastname);
          $this->em->flush();
          return $contact;
        }
        return $form;
      }
      catch(\Exception $e) {
        return View::create('Bad request!', Response::HTTP_BAD_REQUEST);  
      }
   }

     /**
     * @Rest\Delete(path="/contact/{id}")
     * @Rest\View(serializerGroups={"contact"}, serializerEnableMaxDepthChecks=true)
     */
    public function deleteAction(string $id){
      $contact = $this->contactRepository->find($id);
      $statusCode = Response::HTTP_BAD_REQUEST;
      if($contact){
        $this->em->remove($contact);
        $this->em->flush();
        $statusCode = Response::HTTP_OK;
      }
      return View::create(null,$statusCode);
   }
    
}