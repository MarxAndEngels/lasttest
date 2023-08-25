<?php

namespace App\Dto\PlexCrm;

use Spatie\DataTransferObject\DataTransferObjectCollection;

class CommunicationExternalCollectionDto extends DataTransferObjectCollection
{
  public function current():CommunicationExternalDto
  {
    return parent::current();
  }
  public static function create(array $data): self
  {
    return new static(CommunicationExternalDto::arrayOf($data));
  }
}
