<?php
declare(strict_types=1);

namespace App\Dto\Beget;

use App\Complex\Dto\Dto;
use App\Constants\Attributes\AttributeName;
use Carbon\Carbon;
use Carbon\CarbonInterface;

class DomainDto extends Dto
{
  public int $id;
  public string $fqdn;
  public string $date_add;
  public ?string $date_register;
  public ?string $date_expire;
  public ?bool $available;

}
