<?php

declare(strict_types=1);

namespace App\Dto\Filter;

use Spatie\DataTransferObject\DataTransferObjectCollection;

class FolderFilterCollectionDto extends DataTransferObjectCollection
{

  public function current():FolderFilterDto
  {
    return parent::current();
  }



}
