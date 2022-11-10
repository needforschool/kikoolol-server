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
}