<?php

declare(strict_types=1);

namespace App\Dto;

use App\Complex\Dto\Dto;

class DealerDto extends Dto
{
  public int $id;
  public int $external_id;
  public string $slug;
  public ?string $description;
}
