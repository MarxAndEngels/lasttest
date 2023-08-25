<?php

declare(strict_types=1);

namespace App\Dto\PlexCrm;

use App\Complex\Dto\Dto;

class GenerationDto extends Dto
{
  public ?string $title;
  public ?int $yearBegin;
  public ?int $yearEnd;
}
