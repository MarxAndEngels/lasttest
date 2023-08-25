<?php

declare(strict_types=1);

namespace App\Dto\PlexCrm;

use App\Complex\Dto\Dto;

class FeedbackResponseDto extends Dto
{
  public int $id;
  public string $message;
  public string $createdAt;
}
