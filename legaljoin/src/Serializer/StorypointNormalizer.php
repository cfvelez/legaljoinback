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
        
        if (!empty($storypoint->getStory())) {
            $story = $storypoint->getStory();
            $data['story'] = array('id' => $story->getId(), 'title' => $story->getTitle(), 'description' => $story->getLastname());
        }
        return $data;
    }

    public function supportsNormalization($data, $format = null, array $context = [])
    {
        return $data instanceof Storypoint;
    }
}