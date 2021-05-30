<?php

namespace App\Serializer;

use App\Entity\Resource;
use Symfony\Component\HttpFoundation\UrlHelper;
use Psr\Log\LoggerInterface;
use Symfony\Component\Serializer\Normalizer\ContextAwareNormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class ResourceNormalizer implements ContextAwareNormalizerInterface
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

    public function normalize($resource, $format = null, array $context = [])
    {
        $data = $this->normalizer->normalize($resource, $format, $context);
        if (!empty($resource->getName())) {
            $data['name'] = $this->urlHelper->getAbsoluteUrl('/storage/default/' . $resource->getName());
        }
        return $data;
    }

    public function supportsNormalization($data, $format = null, array $context = [])
    {
        return $data instanceof Resource;
    }
}