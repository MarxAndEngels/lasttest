<?php

declare(strict_types=1);

namespace App\Dto\Request;

use App\Complex\Dto\Dto;
use App\Dto\OfferDto;
use App\Models\Offer;

class AutotekaRequestDto extends Dto
{
  public int $offer_external_id;

  public function getOfferForAutoteka(): ?array
  {
    $offerQuery = Offer::query();
    $offer = $offerQuery->whereExternalId($this->offer_external_id)->with('mark', 'folder', 'bodyType', 'color', 'owner', 'modification')->first();
    if(!$offer){
      return null;
    }
    return $offer->toArray();
  }
}
