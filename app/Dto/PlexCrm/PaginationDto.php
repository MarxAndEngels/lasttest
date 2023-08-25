<?php

namespace App\Dto\PlexCrm;

use App\Complex\Dto\Dto;

class PaginationDto extends Dto
{
  public int $currentPage;
  public int $perPage;
  public ?bool $hasMore;
  public ?int $totalItems;
  public ?int $totalPages;
}
