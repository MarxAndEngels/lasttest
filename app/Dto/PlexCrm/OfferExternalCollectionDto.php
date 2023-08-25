<?php

namespace App\Dto\PlexCrm;

use Spatie\DataTransferObject\DataTransferObjectCollection;

class OfferExternalCollectionDto extends DataTransferObjectCollection
{
  public function current():OfferExternalDto
  {
    return parent::current();
  }

  public static function create(array $data): self
  {
    return new static(OfferExternalDto::arrayOf($data));
  }
  public static function getOffers(array $data):array
  {
    $offerExternalDto = new static(OfferExternalDto::arrayOf($data));
    return collect($offerExternalDto)->map(fn(OfferExternalDto $dto) => $dto->getOffer())->all();
  }
}
