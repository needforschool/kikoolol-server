<?php

namespace App\Helper;

class HttpResponseHelper
{
  public static function success($data = null)
  {
    return [
      'success' => true,
      'data' => $data,
    ];
  }

  public static function error($message = null, $code = 400)
  {
    return [
      'success' => false,
      'message' => $message,
      'code' => $code,
    ];
  }

  public static function notFound($message = null)
  {
    return self::error($message, 404);
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