<?php

declare(strict_types=1);

namespace App\Dto\Filter;

use App\Complex\Dto\Dto;

class TitleIdSlugDto extends Dto
{
  public string $title;
  public string $slug;
  public int $id;
}
