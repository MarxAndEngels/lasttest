<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Support\Collection;

class GetWeekSaleService {
  protected Collection $weekSales;

  private array $excludeWeekSales;
  protected int $weekCount;
  protected string $currentWeekSale;

  public function __construct()
  {
    $this->weekSales = Collection::make([
      'Японская неделя',
      'Корейская неделя',
      'Американская неделя',
      'Немецкая неделя'
    ]);
    $this->weekCount = $this->weekSales->count();
    $this->handle();
  }

  public function getCurrentWeekSale(): array
  {
    return $this->currentWeekSale;
  }

  public function getExcludeWeekSales(): array
  {
    return $this->excludeWeekSales;
  }

  protected function handle(): void
  {
    $currentWeekNumber = (int)date('W');
    $this->currentWeekSale = $this->weekSales[($this->weekCount + $currentWeekNumber) % $this->weekCount];

    $this->excludeWeekSales = $this->weekSales->filter(fn(string $item) => $item != $this->currentWeekSale)->all();
  }
}
