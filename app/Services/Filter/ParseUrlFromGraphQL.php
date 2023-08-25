<?php
declare(strict_types=1);

namespace App\Services\Filter;

use App\Constants\Attributes\AttributeName;
use App\Helpers\CacheTags;
use App\Models\Bank;
use App\Models\BodyType;
use App\Models\Dealer;
use App\Models\EngineType;
use App\Models\Folder;
use App\Models\Generation;
use App\Models\Mark;
use App\Models\Offer;
use App\Models\Site;
use Illuminate\Support\Facades\Cache;
use function collect;

class ParseUrlFromGraphQL
{
  protected ?string $url;
  protected ?int $siteId;
  protected ?array $filter;
  protected array $categoryAssociation;
  protected string $slug;
  protected ?array $models;
  protected ?array $routePages;
  protected bool $generationUrl;
  protected bool $bankPages;
  protected bool $dealerPages;
  public function __construct(?string $url = null, ?int $siteId = null)
  {
    if (!$url && !$siteId) {
      return;
    }

    $cacheKeyFilter = CacheTags::getCacheKey("{$siteId}.{$url}", null, 'api_route.filter');
    $cacheKeyModels = CacheTags::getCacheKey("{$siteId}.{$url}", null, 'api_route.models');
    $cacheTags = [$siteId, 'apiRoute'];

    if (!Cache::tags($cacheTags)->has($cacheKeyFilter) && !Cache::tags($cacheTags)->has($cacheKeyModels)) {
      $siteCurrentId = Site::query()->getParentId($siteId)->first()->id;
      $site = Site::selectForSeoTag()->whereId($siteId)->first()->toArray();
      $this->url = $url;
      $this->generationUrl = (bool)$site[AttributeName::GENERATION_URL] ?? false;
      $this->filter = [];
      $this->siteId = $siteCurrentId;
      $this->slug = '([a-z0-9\-]*)';
      $this->models = null;
      $this->categoryAssociation = $site[AttributeName::CATEGORY_ASSOCIATION];
      $this->routePages = $site[AttributeName::ROUTE_PAGES]['pages'] ?? null;
      $this->bankPages = (bool)$site[AttributeName::BANK_PAGES] ?? false;
      $this->dealerPages = (bool)$site[AttributeName::DEALER_PAGES] ?? false;
      $this->handle();
      Cache::tags($cacheTags)->forever($cacheKeyFilter, $this->filter);
      Cache::tags($cacheTags)->forever($cacheKeyModels, $this->models);
//      CacheTags::forever($cacheTags, $cacheKeyFilter, $this->filter);
//      CacheTags::forever($cacheTags, $cacheKeyModels, $this->models);
    } else {
//      $this->filter = CacheTags::get($cacheKeyFilter);
//      $this->models = CacheTags::get($cacheKeyModels);
      $this->filter = Cache::tags($cacheTags)->get($cacheKeyFilter);
      $this->models = Cache::tags($cacheTags)->get($cacheKeyModels);
    }
  }

  public function getModels(): ?array
  {
    return $this->models;
  }

  public function getFilter(): ?array
  {
    return $this->filter;
  }

  protected function handle()
  {
    $catalogCategories = implode('|', $this->categoryAssociation);

    if ($this->routePages) {
      collect($this->routePages)->each(function ($slug) {
        ApiSiteRouterService::route("/({$slug})", function ($slug) {
          $this->models = [
            'page' => $slug
          ];
        });
      });
    }
    if ($this->bankPages) {
      ApiSiteRouterService::route("/(credit)/{$this->slug}", function ($firstSlug, $slug) {
        $bank = $this->getBank($slug);
        if ($bank && isset($bank['models'])) {
          $this->models = $bank['models'];
        }
      });
    }

    if ($this->dealerPages) {
      ApiSiteRouterService::route("/(contact)/{$this->slug}", function ($firstSlug, $slug) {
        $dealer = $this->getDealer($slug);
        if ($dealer && isset($dealer['models'])) {
          $this->models = $dealer['models'];
        }
      });
    }

    ApiSiteRouterService::route("/({$catalogCategories})", function ($category) {
      $category = $this->getCategoryFromValue($category);
      $this->filter = $category;
      $this->models = $category;
    });
    ApiSiteRouterService::route("/({$catalogCategories})/{$this->slug}", function ($category, $secondSlug) {
      $category = $this->getCategoryFromValue($category);
      $checkSecondSlug = $this->checkSecondSlug($secondSlug);

      $filter = $checkSecondSlug['filter'] ?? [];
      $models = $checkSecondSlug['models'] ?? [];

      $this->filter = $filter ? array_merge($category, $filter) : null;
      $this->models = $models ? array_merge($category, $models) : null;
    });

    ApiSiteRouterService::route("/({$catalogCategories})/{$this->slug}/{$this->slug}", function ($category, $secondSlug, $thirdSlug) {
      $category = $this->getCategoryFromValue($category);
      $checkThirdSlug = $this->checkThirdSlug($secondSlug, $thirdSlug);

      $filter = $checkThirdSlug['filter'] ?? null;
      $models = $checkThirdSlug['models'] ?? null;

      $this->filter = $filter ? array_merge($category, $filter) : null;
      $this->models = $models ? array_merge($category, $models) : null;
    });
    ApiSiteRouterService::route("/({$catalogCategories})/{$this->slug}/{$this->slug}/{$this->slug}", function ($category, $secondSlug, $thirdSlug, $fourthSlug) {
      $category = $this->getCategoryFromValue($category);
      $checkFourthElement = $this->checkFourthElement($secondSlug, $thirdSlug, $fourthSlug, !$this->generationUrl);

      $filter = $checkFourthElement['filter'] ?? [];
      $models = $checkFourthElement['models'] ?? [];

      $this->filter = $filter ? array_merge($category, $filter) : null;
      $this->models = $models ? array_merge($category, $models) : null;
    });

    if ($this->generationUrl) {
      ApiSiteRouterService::route("/({$catalogCategories})/{$this->slug}/{$this->slug}/{$this->slug}/{$this->slug}", function ($category, $secondSlug, $thirdSlug, $fourthSlug, $fiveSlug) {
        $category = $this->getCategoryFromValue($category);
        $checkFourthElement = $this->checkFourthElement($secondSlug, $thirdSlug, $fiveSlug, true);

        $filter = $checkFourthElement['filter'] ?? [];
        $models = $checkFourthElement['models'] ?? [];

        $this->filter = $filter ? array_merge($category, $filter) : null;
        $this->models = $models ? array_merge($category, $models) : null;
      });
    }

    ApiSiteRouterService::execute($this->url);
    ApiSiteRouterService::notFound(fn($url) => $this->filter = []);
  }


  protected function getCategoryFromValue(string $category): array
  {
    return ['category' => array_search($category, $this->categoryAssociation, true)];
  }

  protected function getBank(string $slug): ?array
  {
    $bankQuery = Bank::query();
    $bankModel = $bankQuery->whereSlug($slug)->whereActive()->first();
    return $bankModel ? ['models' => [$bankModel::class => $bankModel]] : null;
  }

  protected function getDealer(string $slug): ?array
  {
    $dealerQuery = Dealer::query();
    $dealerModel = $dealerQuery->where(AttributeName::SLUG, '=', $slug)->first();
    return $dealerModel ? ['models' => [$dealerModel::class => $dealerModel]] : null;
  }

  protected function checkSecondSlug(string $slug): ?array
  {
    if (!$slug){
      return null;
    }
    $checkMark = $this->checkMark($slug);
    if ($checkMark) {
      return $checkMark;
    }
    $set = new GetSetService($slug);
    $checkSet = $set->getSetFilter() + $set->getSetModel();
    if ($checkSet) {
      return $checkSet;
    }
    return null;
  }

  protected function checkThirdSlug(string $secondSlug, string $thirdSlug): ?array
  {
    $checkMark = $this->checkMark($secondSlug);
    if (!$checkMark) {
      return null;
    }
    # Определение модели
    $checkFolder = $this->checkFolder($secondSlug, $thirdSlug);
    if ($checkFolder) {
      return array_merge_recursive($checkMark, $checkFolder);
    }
    # Определение года
    $checkYear = $this->checkYear($thirdSlug);
    if ($checkYear) {
      return array_merge_recursive($checkMark, $checkYear);
    }
    # Определение двигателя
    $checkEngineType = $this->checkEngineType($thirdSlug);
    if ($checkEngineType) {
      return array_merge_recursive($checkMark, $checkEngineType);
    }
    # Определение кузова
    $checkBodyType = $this->checkBodyType($thirdSlug);
    if ($checkBodyType) {
      return array_merge_recursive($checkMark, $checkBodyType);
    }
    return null;
  }

  protected function checkFourthElement(string $secondSlug, string $thirdSlug, string $fourthSlug, bool $checkingOffer = false): ?array
  {
    # Определение объявления
    if ($checkingOffer) {
      $checkOffer = $this->checkOffer($secondSlug, $thirdSlug, $fourthSlug);
      if ($checkOffer) {
        return $checkOffer;
      }
    }
    $checkMark = $this->checkMark($secondSlug);
    if (!$checkMark) {
      return null;
    }
    $checkFolder = $this->checkFolder($secondSlug, $thirdSlug);
    if (!$checkFolder) {
      return null;
    }
    $output = array_merge_recursive($checkMark, $checkFolder);

    # Определение поколения
    $checkGeneration = $this->checkGeneration($thirdSlug, $fourthSlug);
    if ($checkGeneration) {
      return array_merge_recursive($output, $checkGeneration);
    }
    # Определение года
    $checkYear = $this->checkYear($fourthSlug);
    if ($checkYear) {
      return array_merge_recursive($output, $checkYear);
    }
    # Определение двигателя
    $checkEngineType = $this->checkEngineType($fourthSlug);
    if ($checkEngineType) {
      return array_merge_recursive($output, $checkEngineType);
    }
    # Определение кузова
    $checkBodyType = $this->checkBodyType($fourthSlug);
    if ($checkBodyType) {
      return array_merge_recursive($output, $checkBodyType);
    }
    return null;
  }

  protected function checkMark(string $slug): ?array
  {
    $markQuery = Mark::query();
    $markModel = $markQuery->where(AttributeName::SLUG, '=', $slug)
      ->with('siteText', fn($item) => $item->where(AttributeName::SITE_ID, '=', $this->siteId))
      ->first();
    return $markModel ? ['filter' => ['mark_slug_array' => [$slug]], 'models' => [$markModel::class => $markModel]] : null;
  }

  protected function checkFolder(string $markSlug, string $slug): ?array
  {
    $folderQuery = Folder::query();
    $folderModel = $folderQuery->where(AttributeName::SLUG, '=', $slug)
      ->whereRelation('mark', 'slug', '=', $markSlug)->with('mark')
      ->with('siteText', fn($item) => $item->where(AttributeName::SITE_ID, '=', $this->siteId))
      ->first();
    return $folderModel ? ['filter' => ['folder_slug_array' => [$slug]], 'models' => [$folderModel::class => $folderModel]] : null;
  }

// TODO Добавить проверку по SITE-ID и поколение для определенных сайтов и категории
  protected function checkOffer(string $secondSlug, string $thirdSlug, $fourthSlug): ?array
  {
    if (!is_numeric($fourthSlug)) {
      return null;
    }
    $offerQuery = Offer::query();
    $offerModel = $offerQuery->selectForSeoTags()
      ->whereExternalId((int)$fourthSlug)
      ->whereMarkSlug($secondSlug)
      ->whereFolderSlug($thirdSlug)
      ->withPriceForSite($this->siteId, [], false)
      ->first();
    return $offerModel ? [
      'filter' => [
        'mark_slug' => $secondSlug,
        'folder_slug' => $thirdSlug,
        'external_id' => $fourthSlug
      ],
      'models' => [
        $offerModel::class => $offerModel
      ]
    ] : null;
  }


// TODO Добавить проверку на года в списке авто
  private function checkYear(string $year): ?array
  {
    if (!is_numeric($year)) {
      return null;
    }
    $year = (int)$year;
    if (($year >= 1900) && ($year <= date('Y'))) {
      return ['filter' => ['year_from' => $year], 'models' => ['yearFrom' => $year]];
    }
    return null;
  }

  protected function checkEngineType(string $name): ?array
  {
    $engineTypeQuery = EngineType::query();
    $engineType = $engineTypeQuery->where(AttributeName::NAME, '=', $name)->first();
    return $engineType ? ['filter' => ['engine_type_id_array' => [$engineType->id]], 'models' => [$engineType::class => $engineType]] : null;
  }

  protected function checkBodyType(string $name): ?array
  {
    $bodyTypeQuery = BodyType::query();
    $bodyType = $bodyTypeQuery->where(AttributeName::NAME, '=', $name)->first();

    return $bodyType ? ['filter' => ['body_type_id_array' => [$bodyType->id]], 'models' => [$bodyType::class => $bodyType]] : null;
  }

  protected function checkGeneration(string $folderSlug, string $slug): ?array
  {
    $generationQuery = Generation::query();
    $generation = $generationQuery->whereSlug($slug)->whereFolderSlug($folderSlug)->first();

    return $generation ? ['filter' => ['generation_slug_array' => [$generation->slug]], 'models' => [$generation::class => $generation]] : null;
  }


}
