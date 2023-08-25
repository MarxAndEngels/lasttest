<?php

declare(strict_types=1);

namespace App\Dto\Filter;

use App\Complex\Dto\Dto;

class TitleValueDto extends Dto
{
  public string $title;
  public $value;

  public function getTitle(): ?string
  {
    return $this->title;
  }

  public function getValue()
  {
    return $this->value;
  }
}
