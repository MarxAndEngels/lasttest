<?php

declare(strict_types=1);

namespace App\Dto;

use App\Complex\Dto\Dto;

class TitleSlugDto extends Dto
{
  public ?string $title;
  public string $slug;
}
