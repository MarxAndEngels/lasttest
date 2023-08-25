<?php

declare(strict_types=1);

namespace App\Dto\YandexYmlFeed;

use App\Complex\Dto\Dto;

class FolderYmlDto extends Dto
{
  public string $title;
  public string $slug;
  public int $id;
  public MarkYmlDto $mark;
  public ?string $image;
}
