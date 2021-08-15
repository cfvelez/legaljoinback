<?php

namespace App\Controller\Api;

use App\Entity\Resource;
use App\Entity\Storypoint;
use App\Repository\ResourceRepository;
use App\Repository\StorypointRepository;
use App\Form\Type\ResourceFormType;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use Psr\Log\LoggerInterface;
use App\Form\Model\ResourceDto;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\Translation\TranslatorInterface;
use App\Service\FileUploader;

class ResourceController extends AbstractFOSRestController{

    /**
     * @var resourceRepository
     */
    private $resourceRepository;
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

    public function __construct(ResourceRepository $resourceRepository, 
                                EntityManagerInterface $em,
                                TranslatorInterface $translator, 
                                LoggerInterface $logger,
                                FileUploader $fileUploader)
    {
        $this->ResourceRepository = $resourceRepository;
        $this->em = $em;
        $this->logger = $logger;
        $this->translator = $translator;
        $this->fileUploader = $fileUploader;
    }

    /**
     * @Rest\Get(path="/resource")
     * @Rest\View(serializerGroups={"resource"}, serializerEnableMaxDepthChecks=true)
     */
     public function all(){
      return View::create($this->ResourceRepository->findAll(), Response::HTTP_OK);
     }

     /**
     * @Rest\Post(path="/resource")
     * @Rest\View(serializerGroups={"resource"}, serializerEnableMaxDepthChecks=true)
     */
    public function upload(Request $request, StorypointRepository $storypointRepository){
        $resourcetDto = new ResourceDto();
        $form = $this->createForm(ResourceFormType::class, $resourcetDto);
       
        try{
          $form->handleRequest($request);
          if($form->isValid() && $form->isSubmitted()){
            $resource = new Resource();
            $resource->setTitle($resourcetDto->title);
            $filename = $this->fileUploader->uploadBase64File($resourcetDto->base64File);
            $resource->setName($filename);
            $storypoint = $this->storypointRepository->find($resourcetDto->storypoint_id);
            if($storypoint)
              $resource->setStorypoint($storypoint);
            $resource->setOwnerId(1);
            $resource->setType(1);
            $resource->setDeleted(0);
            $this->em->persist($resource);
            $this->em->flush();
            return $resource;
          }
          return $form;
        }
        catch(\Exception $e) {
          return View::create('Bad request!', Response::HTTP_BAD_REQUEST);  
        }
    }

    /**
     * @Rest\Get(path="/resource/{id}")
     * @Rest\View(serializerGroups={"resource"}, serializerEnableMaxDepthChecks=true)
     */
    public function updateAction(string $id){
      $resource = $this->ResourceRepository->find($id);
      if(!$resource)
          return View::create('Recurso no encontrado', Response::HTTP_BAD_REQUEST); 

      return $resource;
    }
   
}