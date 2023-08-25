<?php

declare(strict_types=1);

namespace App\Dto;

use App\Complex\Dto\Dto;

class NameSlugDto extends Dto
{
  public ?string $name;
  public string $slug;
}
