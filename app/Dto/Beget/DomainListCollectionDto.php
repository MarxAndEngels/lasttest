<?php

namespace App\Dto\Beget;

use Carbon\Carbon;
use Carbon\CarbonInterface;
use Spatie\DataTransferObject\DataTransferObjectCollection;

class DomainListCollectionDto extends DataTransferObjectCollection
{
  public function current():DomainDto
  {
    return parent::current();
  }
  public static function create(array $data): self
  {
    return new static(DomainDto::arrayOf($data));
  }
}
