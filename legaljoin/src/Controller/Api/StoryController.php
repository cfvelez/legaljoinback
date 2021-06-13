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

     
}