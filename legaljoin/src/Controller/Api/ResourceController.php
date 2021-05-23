<?php

namespace App\Controller\Api;

use App\Entity\Resource;
use App\Repository\ResourceRepository;
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
                                LoggerInterface $logger)
    {
        $this->ResourceRepository = $resourceRepository;
        $this->em = $em;
        $this->logger = $logger;
        $this->translator = $translator;
    }

    /**
     * @Rest\Get(path="/resource")
     * @Rest\View(serializerGroups={"resource"}, serializerEnableMaxDepthChecks=true)
     */
     public function test(){
      return View::create($this->ResourceRepository->findAll(), Response::HTTP_OK);
     }

     /**
     * @Rest\Post(path="/resource")
     * @Rest\View(serializerGroups={"resource"}, serializerEnableMaxDepthChecks=true)
     */
    public function upload(Request $request){
        $requestDto = new ResourceDto();
        $form = $this->createForm(ResourceFormType::class, $requestDto);
       
        try{
          $form->handleRequest($request);
          if($form->isValid() && $form->isSubmitted()){
            $resource = new Resource();
            $resource->setName($requestDto->name);
            $resource->setTitle($requestDto->title);
            $resource->setOwnerId($requestDto->ownner_id);
            $resource->setType($requestDto->type);
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
}