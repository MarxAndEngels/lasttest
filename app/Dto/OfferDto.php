<?php
declare(strict_types=1);

namespace App\Dto;

use Spatie\DataTransferObject\DataTransferObject;

class OfferDto extends DataTransferObject
{
  public int $id;
  public string $external_unique_id;
  public string $name;
  public ?string $vin;
  public ?string $video;
  public int $year;
  public int $engine_power;
  public float $engine_volume;
  public int $run;
  public array $images;
  public ?array $equipments;
  public ?array $equipment_groups;
  public ?array $specifications;
  public int $offer_category_id;
  public int $offer_type_id;
  public int $offer_section_id;
  public int $offer_state_id;
  public ?int $offer_commercial_type_id;
  public int $mark_id;
  public int $folder_id;
  public int $generation_id;
  public int $modification_id;
  public int $complectation_id;
  public int $gearbox_id;
  public int $drive_type_id;
  public int $engine_type_id;
  public int $body_type_id;
  public int $color_id;
  public int $wheel_id;
  public int $owner_id;
  public int $dealer_id;

}
