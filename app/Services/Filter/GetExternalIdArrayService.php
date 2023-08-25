<?php
declare(strict_types=1);

namespace App\Services\Filter;

use Illuminate\Support\Collection;

class GetExternalIdArrayService
{
  public function __construct(
    public string $fileName
  ){}

  public function getExternalIdArray(): ?array
  {
    $filePath = storage_path("/report_csv/{$this->fileName}");
    if (!file_exists($filePath)){
      return null;
    }
    $file = fopen($filePath, 'r');
    $rows = Collection::make();
    $i = 0;
    while ($row = fgetcsv($file)) {
      if ($i != 0 && $i != 1){
        if (isset($row[0])){
          $rows->push((int)$row[0]);
        }
      }
      $i++;
    }
    fclose($file);
    return $rows->toArray();
  }
}
