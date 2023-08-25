<?php

declare(strict_types=1);

namespace App\Dto\PlexCrm;

use App\Complex\Dto\Dto;

class NameTitleDto extends Dto
{
  public string $name;
  public ?string $title;
}
