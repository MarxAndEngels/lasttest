<?php

declare(strict_types=1);

namespace App\Dto\Request;

use App\Complex\Dto\Dto;
use Carbon\CarbonInterface;

class FeedbackRequestMegaCrmDto extends Dto
{
  public string $token;
  public CarbonInterface $last_request_date;
  public string $siteSlug;
}
