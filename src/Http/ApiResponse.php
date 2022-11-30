<?php

namespace App\Http;

use Symfony\Component\HttpFoundation\JsonResponse;

use App\Helper\HttpResponseHelper;

class ApiResponse extends JsonResponse
{
  /**
   * ApiResponse constructor.
   *
   * @param string $message
   * @param mixed  $data
   * @param array  $errors
   * @param int    $status
   * @param array  $headers
   * @param bool   $json
   */
  public function __construct(string $message, $data = null, array $errors = [], int $status = 200, array $headers = [], bool $json = false)
  {
    parent::__construct($this->format($message, $data, $errors, $status), $status, $headers, $json);
  }

  /**
   * Format the API response.
   *
   * @param string $message
   * @param mixed  $data
   * @param array  $errors
   * @param int    $status
   *
   * @return array
   */
  private function format(string $message, $data = null, array $errors = [], int $status = null)
  {
    if ($data === null) {
      $data = new \ArrayObject();
    }

    $response = HttpResponseHelper::error($message, $errors, $status);

    return $response;
  }
}