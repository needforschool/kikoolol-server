<?php

namespace App\Util;

class Hydrateable
{
  /**
   * @param array<int,self> $data
   */
  public function __construct(array $data = [])
  {
    foreach ($data as $key => $value) {
      if (property_exists($this, $key)) $this->$key = $value;
    }
  }
}