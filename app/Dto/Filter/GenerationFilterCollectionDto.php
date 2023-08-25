<?php

declare(strict_types=1);

namespace App\Dto\Filter;

use Spatie\DataTransferObject\DataTransferObjectCollection;

class GenerationFilterCollectionDto extends DataTransferObjectCollection
{
  public function current():GenerationFilterDto
  {
    return parent::current();
  }
}
