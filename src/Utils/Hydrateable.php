<?php

namespace App\Utils;

class Hydrateable
{
  /**
   * @param array<int,self> $data
   */
  public function __construct(array $data = [])
  {
    foreach ($data as $key => $value) {
      $this->$key = $value;
    }
  }
}