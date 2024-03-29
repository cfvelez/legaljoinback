<?php

namespace App\Serializer;

use App\Entity\Storypoint;
use Psr\Log\LoggerInterface;
use Symfony\Component\PropertyInfo\Extractor\ReflectionExtractor;
use Symfony\Component\Serializer\Normalizer\ContextAwareNormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;


class StorypointNormalizer implements ContextAwareNormalizerInterface
{

    public function __construct(
        ObjectNormalizer $normalizer,
        LoggerInterface $logger
    ) {
        $this->normalizer = $normalizer;
        $this->logger = $logger;
    }

    public function normalize($storypoint, $format = null, array $context = [])
    {   
        $data = $this->normalizer->normalize($storypoint, $format, $context, new ReflectionExtractor());
        $data["appointment_time"] = $storypoint->getAppointmentTime() ? $storypoint->getAppointmentTime()->format('Y-m-d H:i:s') : null;
        
        $story = $storypoint->getStory();
        if (!empty($story)) {
            $data['story'] = array('id' => $story->getId(), 'title' => $story->getTitle() , 'description' => $story->getDescription());
        }
        return $data;
    }

    public function supportsNormalization($data, $format = null, array $context = [])
    {
        return $data instanceof Storypoint;
    }
}