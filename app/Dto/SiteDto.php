<?php

declare(strict_types=1);

namespace App\Dto;

use App\Complex\Dto\Dto;
use Carbon\Carbon;

class SiteDto extends Dto
{
  public int $external_id;
  public ?int $parent_site_id;
  public string $category_url;
  public ?string $slug;
  public ?string $title;
  public string $url;
  public ?DealerDto $dealer;
  public int $is_disabled;
  public ?string $api_date_from;
  public ?string $api_date_last;
}
