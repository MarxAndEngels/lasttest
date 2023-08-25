<?php

declare(strict_types=1);

namespace App\Services;

use App\Constants\Attributes\AttributeName;
use App\Models\Bank;
use App\Models\BodyType;
use App\Models\Dealer;
use App\Models\DriveType;
use App\Models\EngineType;
use App\Models\Folder;
use App\Models\Generation;
use App\Models\Mark;
use App\Models\Offer;
use App\Models\SeoTag;
use App\Models\Set;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Blade;

class GetSeoTagsService
{
  protected ?array $seoTags;
  protected ?array $models;
  protected ?int $siteId;
  //Приоритетность
  protected array $availableKeys = [
    Offer::class => 'offer',
    Set::class => 'set',
    Generation::class => 'generation',
    'yearFrom' => 'yearFrom',
    DriveType::class => 'driveType',
    BodyType::class => 'bodyType',
    EngineType::class => 'engineType',
    Folder::class => 'folder',
    Mark::class => 'mark',
    'category' => 'category',
    Bank::class => 'bank',
    Dealer::class => 'dealer',
    'page' => 'page'
  ];
  protected Collection $availableKeysCollection;
  protected ?array $seoTemplate;
  protected ?string $category;
  protected ?array $added = null;

  public function __construct(?int $siteId = null, ?array $models = null)
  {
    if(!$siteId && $models){
      return;
    }
    $this->siteId = $siteId;
    $this->models = $models;
    $this->category = null;
    $this->availableKeysCollection = collect($this->availableKeys);
    $this->seoTemplate = $this->getSeoTemplate($this->siteId);
    $this->handle();
  }

  public function getSeoTags(): ?array
  {
    return $this->seoTags;
  }

  protected function handle()
  {
    $this->category = $this->models['category'] ?? null;
    $functionName = $this->availableKeysCollection
      ->first(fn($value, $key) => isset($this->models[$key]) && (($this->models[$key] instanceof $key) || ($this->models[$key])));
    if (!$functionName) {
      $this->seoTags = null;
      return;
    }
    $key = $this->availableKeysCollection->search($functionName);
    $value = $this->models[$key];
    if (!method_exists($this, (string)$functionName)) {
      $this->seoTags = null;
      return;
    }
    $this->seoTags = call_user_func([$this, $functionName], $value);
  }

  protected function getSeoTemplate(int $siteId): ?array
  {
    $seoTag = SeoTag::select(AttributeName::SEO_TAG)->where(AttributeName::SITE_ID, '=', $siteId)->first()->toArray();
    return $seoTag[AttributeName::SEO_TAG] ?? null;
  }

  protected function getSeoTemplateElement(string $key): ?array
  {
    if (!$this->seoTemplate) {
      return null;
    }
    return $this->category ? $this->seoTemplate[$this->category][$key] ?? $this->seoTemplate[$key] ?? null : $this->seoTemplate[$key] ?? null;
  }

  protected function page(string $page): ?array
  {
    $pageTemplate = $this->getSeoTemplateElement($page);
    if (!$pageTemplate) {
      return null;
    }
    return $this->render($pageTemplate, __FUNCTION__, $page);
  }

  protected function category(string $category): ?array
  {
    $categoryTemplate = $this->getSeoTemplateElement(__FUNCTION__);
    if (!$categoryTemplate) {
      return null;
    }
    return $this->render($categoryTemplate, __FUNCTION__, $category);
  }

  protected function set(Set $set): ?array
  {
    $setTemplate = $this->getSeoTemplateElement(__FUNCTION__);
    if (!$setTemplate) {
      return null;
    }
    return $this->render($setTemplate, __FUNCTION__, $set->toArray());
  }

  protected function mark(Mark $mark): ?array
  {
    $markTemplate = $this->getSeoTemplateElement(__FUNCTION__);
    if (!$markTemplate) {
      return null;
    }
    return $this->render($markTemplate, __FUNCTION__, $mark->toArray());
  }

  protected function folder(Folder $folder): ?array
  {
    $folderTemplate = $this->getSeoTemplateElement(__FUNCTION__);
    if (!$folderTemplate) {
      return null;
    }
    return $this->render($folderTemplate, __FUNCTION__, $folder->toArray());
  }

  protected function offer(Offer $offer): ?array
  {
    $offerTemplate = $this->getSeoTemplateElement(__FUNCTION__);
    if (!$offerTemplate) {
      return null;
    }
    return $this->render($offerTemplate, __FUNCTION__, $offer->toArray());
  }

  protected function generation(Generation $generation): array
  {
    $this->added['title'] = $generation->name . ' [' . $generation->year_begin . ' - ' . ($generation->year_end ?? 'н.в.') . ']';
    $this->added['name'] = $generation['slug'];
    return $this->setAdded(false);
  }

  protected function engineType(EngineType $engineType): array
  {
    $this->added = $engineType->toArray();
    return $this->setAdded();
  }

  protected function bodyType(BodyType $bodyType): array
  {
    $this->added = $bodyType->toArray();
    return $this->setAdded();
  }

  protected function driveType(DriveType $driveType): array
  {
    $this->added = $driveType->toArray();
    return $this->setAdded();
  }

  protected function yearFrom(int $yearFrom): array
  {
    $this->added['title'] = "от {$yearFrom} года";
    $this->added['name'] = $yearFrom;
    return $this->setAdded();
  }

  protected function bank(Bank $bank): ?array
  {
    $bankTemplate = $this->getSeoTemplateElement(__FUNCTION__);
    if (!$bankTemplate) {
      return null;
    }
    return $this->render($bankTemplate, __FUNCTION__, $bank->toArray());
  }
  protected function dealer(Dealer $dealer): ?array
  {
    $dealerTemplate = $this->getSeoTemplateElement(__FUNCTION__);
    if (!$dealerTemplate) {
      return null;
    }
    return $this->render($dealerTemplate, __FUNCTION__, $dealer->toArray());
  }
  protected function setAdded(bool $strToLower = true): array
  {
    if(!$this->added){
      return [];
    }
    $this->added['title'] = $strToLower ?  mb_strtolower($this->added['title']) : $this->added['title'];
    if (isset($this->models[Folder::class])) {
      return $this->folder($this->models[Folder::class]);
    }
    return $this->mark($this->models[Mark::class]);
  }

  protected function render(array $seoTemplate, string $key, $data): array
  {
    $seo = [
      'page_title' => Blade::render($seoTemplate[AttributeName::PAGE_TITLE], [$key => $data, 'added' => isset($this->added) ? " {$this->added['title']}" : '']),
      'title' => Blade::render($seoTemplate[AttributeName::TITLE], [$key => $data, 'added' => isset($this->added) ? " {$this->added['title']}" : '']),
      'description' => Blade::render($seoTemplate[AttributeName::DESCRIPTION], [$key => $data, 'added' => isset($this->added) ? " {$this->added['title']}" : ''])
    ];
    if(isset($seoTemplate[AttributeName::CRUMBS])){
      $crumbsCollection = collect($seoTemplate[AttributeName::CRUMBS])->map(fn($crumb)=> [
        'title' => Blade::render($crumb[AttributeName::TITLE], [$key => $data]),
        'link' => Blade::render($crumb[AttributeName::LINK], [$key => $data])
      ]);
      if($this->added){
        $last = $crumbsCollection->last();
        $added = [
          'title' => $this->added['title'],
          'link' => "{$last['link']}/{$this->added['name']}"
        ];
        $crumbsCollection->push($added);
      }
      $seo['crumbs'] = $crumbsCollection->toArray();
    }

    if (isset($seoTemplate[AttributeName::SITE_TEXT]) && isset($data['site_text']['body'])){
      $seo['site_text']['body'] = Blade::render($seoTemplate[AttributeName::SITE_TEXT][AttributeName::BODY], ['site_text' => $data['site_text']]);
    }

    return $seo;
  }
}
