<?php

namespace App\Controller\Api;

use App\Entity\Story;
use App\Entity\Contact;
use App\Repository\StoryRepository;
use App\Repository\ContactRepository;
use App\Form\Type\StoryFormType;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use Psr\Log\LoggerInterface;
use App\Form\Model\StoryDto;
use Monolog\Logger;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\Translation\TranslatorInterface;


class StoryController extends AbstractFOSRestController{

    /**
     * @var StoryRepository
     */
    private $storyRepository;
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

    public function __construct(StoryRepository $storyRepository, 
                                EntityManagerInterface $em,
                                TranslatorInterface $translator, 
                                LoggerInterface $logger)
    {
        $this->storyRepository = $storyRepository;
        $this->em = $em;
        $this->logger = $logger;
        $this->translator = $translator;
    }

    /**
     * @Rest\Get(path="/story")
     * @Rest\View(serializerGroups={"story"}, serializerEnableMaxDepthChecks=true)
     */
     public function all(){

      $stories = $this->storyRepository->findAll();

      return View::create($this->storyRepository->findAll(), Response::HTTP_OK);
     }

     /**
     * @Rest\Get(path="/story/{id}")
     * @Rest\View(serializerGroups={"story"}, serializerEnableMaxDepthChecks=true)
     */
    public function getByIdAction(string $id){
      $story = $this->storyRepository->find($id);
      $statusCode = $story ? Response::HTTP_OK : Response::HTTP_BAD_REQUEST;
      $data = $story ?? $this->translator->trans('Story.notFound',[],'story');
      return View::create($data, $statusCode);
     }

    /**
     * @Rest\Get(path="/story/contact/{id}")
     * @Rest\View(serializerGroups={"story"}, serializerEnableMaxDepthChecks=true)
     */
    public function getByContactIdAction(string $id,ContactRepository $contactRepository){
      $data = $this->translator->trans('Contact.notFound',[],'contact');
      $statusCode = Response::HTTP_BAD_REQUEST;
      $contact = $contactRepository->find($id);

      if($contact){
        $stories = $contact->getStories();
        $statusCode = $stories ? Response::HTTP_OK : Response::HTTP_BAD_REQUEST;
        $data = $stories ?? $this->translator->trans('Story.notFound',[],'story');
      }
      return View::create($data, $statusCode);
      
     }

    /**
     * @Rest\Post(path="/story")
     * @Rest\View(serializerGroups={"story"}, serializerEnableMaxDepthChecks=true)
     */
    public function createAction(Request $request, ContactRepository $contactRepository){
        $storyDto = new StoryDto();
        $form = $this->createForm(StoryFormType::class, $storyDto);

        try{
          $form->handleRequest($request);
          if($form->isValid() && $form->isSubmitted()){
            $contactId = $request->request->get('contactId');
            $contact = $contactRepository->find($contactId);
            if(!$contact)
                return View::create('Bad request!', Response::HTTP_BAD_REQUEST);   
            
            $story = new Story();
            $story->setTitle($storyDto->title);
            $story->setDescription($storyDto->description);
            $story->setContact($contact);
            $this->em->persist($story);
            $this->em->flush();
            return $story;
          }
          return $form;
        }
        catch(\Exception $e) {
          return View::create('Bad request!', Response::HTTP_BAD_REQUEST);  
        }
     }

    /**
     * @Rest\Post(path="/story/{id}",requirements={"id"="\d+"})
     * @Rest\View(serializerGroups={"story"}, serializerEnableMaxDepthChecks=true)
     */
    public function updateAction(string $id, Request $request, ContactRepository $contactRepository){
      $story = $this->storyRepository->find($id);

      if(!$story)
          return View::create($this->translator->trans('Story.notFound',[],'story'), Response::HTTP_BAD_REQUEST); 

      $storyDto = new StoryDto();
      $form = $this->createForm(StoryFormType::class, $storyDto);
     
      try{
        $form->handleRequest($request);
        if($form->isValid() && $form->isSubmitted()){
          $contactId = $request->request->get('contactId');
          $contact = $contactRepository->find($contactId);
          if(!$contact)
              return View::create('Bad request!', Response::HTTP_BAD_REQUEST);   
          
          $story->setTitle($storyDto->title);
          $story->setDescription($storyDto->description);
          $story->setContact($contact);
          $this->em->flush();
          return $story;
        }
        return $form;
      }
      catch(\Exception $e) {
        return View::create('Bad request!', Response::HTTP_BAD_REQUEST);  
      }
   }

   /**
     * @Rest\Delete(path="/story/{id}")
     * @Rest\View(serializerGroups={"story"}, serializerEnableMaxDepthChecks=true)
     */
    public function deleteAction(string $id){
      $story = $this->storyRepository->find($id);
      $statusCode = Response::HTTP_BAD_REQUEST;
      if($story){
        $this->em->remove($story);
        $this->em->flush();
        $statusCode = Response::HTTP_OK;
      }
      return View::create(null,$statusCode);
   }

    /**
     * @Rest\Post(path="/story/search/{contactId}",requirements={"contactId"="\d+"})
     * @Rest\View(serializerGroups={"story"}, serializerEnableMaxDepthChecks=true)
     */
    public function search(string $contactId,Request $request){
      $term = $request->request->get('term');
      $stories = $this->storyRepository->findByTerm($contactId,$term);
      $statusCode =  Response::HTTP_OK;
      $data = $stories ?? $this->translator->trans('Story.notFound',[],'story');
      return View::create($data, $statusCode);
     }
     
}