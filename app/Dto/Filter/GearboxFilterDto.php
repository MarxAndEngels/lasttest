<?php

declare(strict_types=1);

namespace App\Dto\Filter;

use App\Complex\Dto\Dto;

class GearboxFilterDto extends Dto
{
  public string $title;
  public string $title_short;
  public string $title_short_rus;
  public string $slug;
  public int $id;
}
