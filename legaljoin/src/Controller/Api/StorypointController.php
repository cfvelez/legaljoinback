<?php

namespace App\Controller\Api;

use App\Entity\Storypoint;
use App\Entity\Story;
use App\Repository\StorypointRepository;
use App\Repository\StoryRepository;
use App\Form\Type\StorypointFormType;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use Psr\Log\LoggerInterface;
use App\Form\Model\StorypointDto;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\Translation\TranslatorInterface;


class StorypointController extends AbstractFOSRestController{

    /**
     * @var StorypointRepository
     */
    private $storypointRepository;
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

    public function __construct(StorypointRepository $storypointRepository, 
                                EntityManagerInterface $em,
                                TranslatorInterface $translator, 
                                LoggerInterface $logger)
    {
        $this->storypointRepository = $storypointRepository;
        $this->em = $em;
        $this->logger = $logger;
        $this->translator = $translator;
    }

    /**
     * @Rest\Get(path="/storypoint")
     * @Rest\View(serializerGroups={"storypoint"}, serializerEnableMaxDepthChecks=true)
     */
     public function all(){

      $storypoints = $this->storypointRepository->findAll();

      return View::create($storypoints, Response::HTTP_OK);
     }

     /**
     * @Rest\Get(path="/storypoint/{id}")
     * @Rest\View(serializerGroups={"storypoint"}, serializerEnableMaxDepthChecks=true)
     */
    public function getByIdAction(string $id){
      $storypoint = $this->storypointRepository->find($id);
      $statusCode = $storypoint ? Response::HTTP_OK : Response::HTTP_BAD_REQUEST;
      $data = $storypoint ?? $this->translator->trans('Story.notFound',[],'story');
      return View::create($data, $statusCode);
     }

    /**
     * @Rest\Post(path="/storypoint")
     * @Rest\View(serializerGroups={"storypoint"}, serializerEnableMaxDepthChecks=true)
     */
    public function createAction(Request $request, StoryRepository $storyRepository){
        $storypointDto = new StorypointDto();
        $form = $this->createForm(StorypointFormType::class, $storypointDto);
       
        try{
          $form->handleRequest($request);
          if($form->isValid() && $form->isSubmitted()){
            $storyId = $storypointDto->storyId;
            $story = $storyRepository->find($storyId);
            if(!$story)
                return View::create('no Story found!', Response::HTTP_BAD_REQUEST);   
            
            $storypoint = new Storypoint();
            $storypoint->setName($storypointDto->name);
            $storypoint->setDescription($storypointDto->description);
            $storypoint->setAppointmentTime($storypointDto->appointmentAt);
            $storypoint->setStory($story);
            $this->em->persist($storypoint);
            $this->em->flush();
            return $storypoint;
          }
          return $form;
        }
        catch(\Exception $e) {
          return View::create($e, Response::HTTP_BAD_REQUEST);  
        }
     }

    /**
     * @Rest\Post(path="/storypoint/{id}")
     * @Rest\View(serializerGroups={"storypoint"}, serializerEnableMaxDepthChecks=true)
     */
    public function updateAction(string $id, Request $request){
      $storypoint = $this->storypointRepository->find($id);

      if(!$storypoint)
          return View::create('Storypoint no encontrado', Response::HTTP_BAD_REQUEST); 

      $storypointDto = new StorypointDto();
      $form = $this->createForm(StorypointFormType::class, $storypointDto);
      try{
        $form->handleRequest($request);
        if($form->isValid() && $form->isSubmitted()){
          $storypoint->setName($storypointDto->name);
          $storypoint->setDescription($storypointDto->description);
          $storypoint->setAppointmentTime($storypointDto->appointmentAt);
          $this->em->flush();
          return $storypoint;
        }
        return $form;
      }
      catch(\Exception $e) {
        return View::create('Bad request!', Response::HTTP_BAD_REQUEST);  
      }
   }

   /**
     * @Rest\Delete(path="/storypoint/{id}")
     * @Rest\View(serializerGroups={"storypoint"}, serializerEnableMaxDepthChecks=true)
     */
    public function deleteAction(string $id){
      $storypoint = $this->storypointRepository->find($id);
      $statusCode = Response::HTTP_BAD_REQUEST;
      if($storypoint){
        $this->em->remove($storypoint);
        $this->em->flush();
        $statusCode = Response::HTTP_OK;
      }
      return View::create(null,$statusCode);
   }

     
}