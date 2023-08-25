<?php
declare(strict_types=1);

namespace App\Dto;
use Spatie\DataTransferObject\DataTransferObject;

class OfferSiteDto extends DataTransferObject
{
  public int $offer_id;
  public int $site_id;
  public int $price;
  public int $price_old;
  public bool $is_active;
}
