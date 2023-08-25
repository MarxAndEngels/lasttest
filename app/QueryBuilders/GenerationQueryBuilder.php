<?php

declare(strict_types=1);

namespace App\QueryBuilders;

use App\Constants\Attributes\AttributeName;
use Illuminate\Database\Eloquent\Builder;

final class GenerationQueryBuilder extends Builder
{
  public function whereFolderId(int $folder_id): self
  {
    return $this->where(AttributeName::FOLDER_ID, $folder_id);
  }

  public function whereMarkSlug(string $markSlug): self
  {
    return $this->whereHas('folder.mark', fn($builder) => $builder->where($builder->qualifyColumn(AttributeName::SLUG), '=', $markSlug));
  }
  public function whereFolderSlug(string $folderSlug): self
  {
    return $this->whereRelation('folder', AttributeName::SLUG, '=', $folderSlug);
  }
  public function whereSlug(string $slug): self
  {
    return $this->where(AttributeName::SLUG, '=', $slug);
  }

}
