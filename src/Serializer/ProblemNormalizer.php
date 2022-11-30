<?php

namespace App\Serializer;

use Symfony\Component\ErrorHandler\Exception\FlattenException;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use App\Helper\HttpResponseHelper;

class ProblemNormalizer implements NormalizerInterface
{
  public function normalize($object, $format = null, array $context = []): array
  {
    return HttpResponseHelper::error($object->getMessage(), $object->getStatusCode());
  }

  public function supportsNormalization($data, $format = null, array $context = []): bool
  {
    return $data instanceof FlattenException;
  }
}
