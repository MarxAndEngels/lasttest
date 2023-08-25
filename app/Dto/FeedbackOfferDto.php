<?php

declare(strict_types=1);

namespace App\Dto;

use App\Complex\Dto\Dto;
use App\Models\Dealer;

class FeedbackOfferDto extends Dto
{
  public int $id;
  public string $offer_title;
  public int $feedback_id;
  public int $external_id;
  public int $dealer_id;
  public int $mark_id;
  public int $folder_id;
  public ?int $generation_id;
  public int $gearbox_id;
  public ?int $modification_id;
  public int $year;
  public int $engine_power;
  public int $run;
  public $engine_volume;
  public float $price;
  public float $price_old;
  public ?DealerDto $dealer;
}

