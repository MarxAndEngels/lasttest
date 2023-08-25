<?php

declare(strict_types=1);

namespace App\Dto\PlexCrm;

use App\Complex\Dto\Dto;

class NameTitleTitleShortDto extends Dto
{
  public string $name;
  public ?string $title;
  public ?string $title_short;
}
