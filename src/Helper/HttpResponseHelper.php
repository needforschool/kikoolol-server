<?php

namespace App\Helper;

class HttpResponseHelper
{
  public static function success(array $data = null)
  {
    return [
      'success' => true,
      'data' => $data,
    ];
  }

  public static function error(string $message, array $errors = null, int $status = null)
  {
    $message = [
      'success' => false,
      'message' => $message,
    ];

    if($errors) {
      $message['errors'] = $errors;
    }

    if($status) {
      $message['status'] = $status;
    }

    return $message;
  }

  public static function notFound(string $message, array $errors = null)
  {
    return self::error($message, $errors, 404);
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