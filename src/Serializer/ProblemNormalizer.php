<?php

namespace App\Serializer;

use App\Exception\FormException;
use Symfony\Component\ErrorHandler\Exception\FlattenException;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use App\Helper\HttpResponseHelper;

class ProblemNormalizer implements NormalizerInterface
{
  public function normalize($object, $format = null, array $context = []): array
  {
    $errors = $exception->getErrors();
    foreach ($errors as $error) {
      $data[$error->getOrigin()->getName()][] = $error->getMessage();
    }

    return HttpResponseHelper::error($object->getMessage(), $errors, $object->getStatusCode());
  }

  public function supportsNormalization($data, $format = null, array $context = []): bool
  {
    return $data instanceof FormException;
  }
}
