<?php

namespace App\Serializer;

use App\Entity\Story;
use Symfony\Component\HttpFoundation\UrlHelper;
use Psr\Log\LoggerInterface;
use Symfony\Component\Serializer\Normalizer\ContextAwareNormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class StoryNormalizer implements ContextAwareNormalizerInterface
{
    private ObjectNormalizer $normalizer;
    private UrlHelper $urlHelper;

    public function __construct(
        ObjectNormalizer $normalizer,
        UrlHelper $urlHelper,
        LoggerInterface $logger
    ) {
        $this->normalizer = $normalizer;
        $this->urlHelper = $urlHelper;
        $this->logger = $logger;
    }

    public function normalize($story, $format = null, array $context = [])
    {
        $data = $this->normalizer->normalize($story, $format, $context);
        
        if (!empty($story->getContact())) {
            $data['contact'] = $story->getContact()->getId();
        }
        return $data;
    }

    public function supportsNormalization($data, $format = null, array $context = [])
    {
        return $data instanceof Story;
    }
}