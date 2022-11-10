<?php

namespace App\Helper;

class HttpResponseHelper
{
  public static function success($data = null, $message = null, $errors = null)
  {
    return [
      'message' => $message,
      'data' => $data,
      'errors' => $errors,
    ];
  }

  public static function error($message = null, $errors = null)
  {
    return [
      'message' => $message,
      'data' => null,
      'errors' => $errors,
    ];
  }

  public static function formatErrorFromResponse($response)
  {
    $errors = [];
    foreach ($response->getErrors() as $error) {
      $errors[] = $error->getMessage();
    }

    print_r($errors);

    return $errors;
  }
}