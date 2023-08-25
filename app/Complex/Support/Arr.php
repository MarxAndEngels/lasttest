<?php
declare(strict_types=1);

namespace App\Complex\Support;

use \Illuminate\Support\Arr as baseArr;

class Arr extends baseArr
{
  public static function getAny($array, $keys): bool
  {
    if (is_null($keys)) {
      return false;
    }

    $keys = (array)$keys;

    if (!$array) {
      return false;
    }

    if ($keys === []) {
      return false;
    }

    foreach ($keys as $key) {
      if (static::get($array, $key)) {
        return true;
      }
    }

    return false;
  }
}
