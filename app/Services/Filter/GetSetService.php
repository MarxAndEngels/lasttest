<?php
declare(strict_types=1);

namespace App\Services\Filter;

use App\Constants\Attributes\AttributeName;
use App\Helpers\CacheTags;
use App\Models\Set;
use Illuminate\Support\Facades\Cache;

class GetSetService
{
  protected string $slug;
  protected ?array $setFilter;
  protected ?array $setModel;

  public function __construct(string $slug = '')
  {
    $this->slug = $slug;
    if ($this->slug) {
      $this->handle();
    }
  }

  protected function handle(): void
  {
    $cacheKeySetFilter = CacheTags::getCacheKey($this->slug, null, 'setFilter');
    $cacheKeySetModel = CacheTags::getCacheKey($this->slug, null, 'setModel');
    $cacheTags = ['set'];

    if (!Cache::tags($cacheTags)->has($cacheKeySetFilter) && !Cache::tags($cacheTags)->has($cacheKeySetModel)) {
      $setQuery = Set::query();
      $setModel = $setQuery->where(AttributeName::SLUG, '=', $this->slug)->first();
      $this->setFilter = $setModel ? ['filter' => $setModel->filter] : null;
      $this->setModel = $setModel ? ['models' => [$setModel::class => $setModel]] : null;
      Cache::tags($cacheTags)->forever($cacheKeySetFilter, $this->setFilter);
      Cache::tags($cacheTags)->forever($cacheKeySetModel, $this->setModel);
//      CacheTags::forever($cacheTags, $cacheKeySetFilter, $this->setFilter);
//      CacheTags::forever($cacheTags, $cacheKeySetModel, $this->setModel);
    }else{
      $this->setFilter = Cache::tags($cacheTags)->get($cacheKeySetFilter);
      $this->setModel = Cache::tags($cacheTags)->get($cacheKeySetModel);
//      $this->setFilter = CacheTags::get($cacheKeySetFilter);
//      $this->setModel = CacheTags::get($cacheKeySetModel);
    }
  }

  public function getSetFilter(): ?array
  {
    return $this->setFilter;
  }

  public function getSetModel(): ?array
  {
    return $this->setModel;
  }
}
