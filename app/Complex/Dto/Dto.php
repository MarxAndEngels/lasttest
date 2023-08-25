<?php
declare(strict_types=1);

namespace App\Complex\Dto;

use Spatie\DataTransferObject\DataTransferObject;

abstract class Dto extends DataTransferObject
{
  protected bool $ignoreMissing = true;
}
