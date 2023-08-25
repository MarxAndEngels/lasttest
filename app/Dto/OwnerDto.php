<?php

declare(strict_types=1);

namespace App\Dto;

use App\Complex\Dto\Dto;

class OwnerDto extends Dto
{
  public int $number;
  public string $title;
}
