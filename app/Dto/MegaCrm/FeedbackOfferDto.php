<?php

declare(strict_types=1);

namespace App\Dto\MegaCrm;

use App\Complex\Dto\Dto;
use App\Dto\Filter\TitleIdSlugDto;
use App\Dto\NameDto;
use App\Dto\NameSlugDto;
use App\Dto\PlexCrm\NameTitleDto;

class FeedbackOfferDto extends Dto
{
  public int $id;
  public int $feedback_id;
  public int $external_id;
  public int $dealer_id;
  public string $offer_title;
  public int $year;
  public int $engine_power;
  public int $run;
  public float $price;
  public float $price_old;
  public TitleIdSlugDto $mark;
  public TitleIdSlugDto $folder;
  public ?NameSlugDto $generation;
  public ?NameDto $modification;
  public ?NameTitleDto $gearbox;

}
