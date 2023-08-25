<?php

declare(strict_types=1);

namespace App\ModelFilters;

use App\Constants\Attributes\AttributeName;
use App\Constants\TableConstants;
use App\Dto\Filter\FolderFilterDto;
use App\Dto\Filter\GearboxFilterDto;
use App\Dto\Filter\GenerationFilterDto;
use App\Dto\Filter\MarkFilterDto;
use App\Dto\Filter\TitleIdSlugDto;
use App\Helpers\CacheTags;
use App\Helpers\Modifiers;
use App\Models\BodyType;
use App\Models\DriveType;
use App\Models\EngineType;
use App\Models\Folder;
use App\Models\Gearbox;
use App\Models\Generation;
use App\Models\Mark;
use App\Models\Offer;
use App\Models\Owner;
use App\QueryBuilders\ModelCountableFilter;
use App\QueryBuilders\OfferQueryBuilder;
use App\Services\Filter\GetChosenFieldsService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

final class OfferCountableFilter extends ModelCountableFilter
{
  protected Offer $offerQueryGet;
  protected array $excepts = [
    'mark' => ['mark_id', 'mark_slug','mark_slug_array','folder_id', 'folder_slug', 'folder_slug_array', 'generation_slug', 'generation_slug_array', 'gearbox_id_array', 'engine_type_id_array', 'drive_type_id_array', 'body_type_id_array', 'price_from', 'price_to', 'year_from', 'year_to', 'run_from', 'run_to'],
    'folder' => ['folder_id','folder_slug','folder_slug_array', 'generation_slug', 'generation_slug_array', 'gearbox_id_array', 'engine_type_id_array', 'drive_type_id_array', 'body_type_id_array', 'price_from', 'price_to', 'year_from', 'year_to', 'run_from', 'run_to'],
    'generation' => ['generation_slug', 'generation_slug_array', 'generation_id', 'price_from', 'price_to', 'gearbox_id_array', 'engine_type_id_array', 'drive_type_id_array', 'body_type_id_array', 'price_from', 'price_to', 'year_from', 'year_to', 'run_from', 'run_to'],
    'gearbox' => ['gearbox_id_array'],
    'engineType' => ['engine_type_id_array'],
    'driveType' => ['drive_type_id_array'],
    'bodyType' => ['body_type_id_array'],
    'owner' => ['owner_id_array'],
    'year' => ['year_from', 'year_to'],
    'price' => ['price_from', 'price_to'],
    'run' => ['run_from', 'run_to']
  ];
  protected array $dependencies = [
    'folder' => ['mark_id', 'mark_slug','mark_slug_array'],
    'generation' => ['folder_slug', 'folder_id', 'folder_slug_array']
  ];

  public function build(array $countable): array
  {
    $output = collect($countable)
      ->filter(fn(string $name) => $this->isResolvable($name))
      ->mapWithKeys(fn(string $name) => [$name => call_user_func([$this, $name])])
      ->all();
    if (isset($output['chosen'])){
      return (new GetChosenFieldsService($this->input, $output))->getChosenFields();
    }else{
      return $output;
    }
  }

  public function mark(): array
  {
    $site_id = $this->input['site_id']['id'];
    $category = $this->input['category'] ?? '';
    $args = [
      'site_id' => $site_id,
      'category' => $category
    ];
    $cacheKey = CacheTags::getCacheKey('OfferCountableFilter.marks', $args);

    return Cache::tags([$site_id, 'filters', 'OfferCountableFilter.marks'])->rememberForever($cacheKey, fn() => $this->getMarks());
//    return CacheTags::rememberForever([$site_id, 'filters', 'OfferCountableFilter.marks'], $cacheKey, $this->getMarks());
//    $offerQuery = Offer::query();
//    return $getQuery
//      ->groupBy('mark_id')
//      ->select(['mark_id', $mark->qualifyColumn(AttributeName::ID), $mark->qualifyColumn(AttributeName::TITLE), $mark->qualifyColumn(AttributeName::SLUG)])
//      ->join('marks', fn(JoinClause $joinClause) => $joinClause->on($mark->qualifyColumn('id'), '=', $offerQuery->qualifyColumn('mark_id')))
//      ->orderBy('marks.title')
//      ->get()
//      ->map(fn(\stdClass $mark) => new MarkFilterDto([
//        'title' => $mark->title,
//        'id' => $mark->id,
//        'slug' => $mark->slug,
//      ]))
//      ->all();

  }

  protected function getMarks(): array
  {
    $mark = Mark::query();
    $query = $this->applyDependenciesQuery('mark');
    $getQuery = $query->getQuery();
    return $mark
      ->select([AttributeName::ID, AttributeName::TITLE, AttributeName::SLUG])
      ->whereHas('offers', fn(OfferQueryBuilder $offerQuery) => $offerQuery
        ->setQuery($getQuery)
        ->whereColumn($offerQuery->qualifyColumn(AttributeName::MARK_ID), $mark->qualifyColumn(AttributeName::ID))
      )
      ->orderBy(AttributeName::TITLE)
      ->get()
      ->map(fn(Mark $mark) => new MarkFilterDto([
        'title' => $mark->title,
        'id' => $mark->id,
        'slug' => $mark->slug
      ]))
      ->all();
  }
  public function folder(): array
  {
//    $markIdArray = [];
//    $markId  = Arr::get($this->input, 'mark_id', null);
//    $markSlug  = Arr::get($this->input, 'mark_slug', null);
//    $inputMarkSlugArray = Arr::get($this->input, 'mark_slug_array', null);
//    if($markSlug){
//      $inputMarkSlugArray = [$markSlug];
//    }
//    if($markId){
//      $markIdArray =[$markId];
//    }
//    if($inputMarkSlugArray){
//      $markQuery = Mark::query();
//      $markIdArray = $markQuery
//        ->select($markQuery->qualifyColumn(AttributeName::ID))
//        ->whereIn($markQuery->qualifyColumn(AttributeName::SLUG), $inputMarkSlugArray)
//        ->get()
//        ->pluck('id')
//        ->toArray();
//    }
//    if(!$markIdArray) {
//      return [];
//    }


    $query = $this->applyDependenciesQuery(__FUNCTION__);
    $offerQuery = Offer::query();

    return
      $offerQuery->setQuery($query->getQuery())
      ->select(AttributeName::FOLDER_ID)->groupBy(AttributeName::FOLDER_ID)
      ->with('folder:id,title,slug,mark_id')
      ->get()
      ->map(fn(Offer $offer) => new FolderFilterDto([
        'title' => $offer->folder->title,
        'id' => $offer->folder->id,
        'slug' => $offer->folder->slug,
        'mark_id' => $offer->folder->mark_id
      ]))
      ->all();



    $folderQuery = Folder::query();
//    $offerQuery = Offer::query();
//    return $offerQuery
//      ->setQuery($query->getQuery())
//      ->select([$folder->qualifyColumn(AttributeName::ID), $folder->qualifyColumn(AttributeName::TITLE), $folder->qualifyColumn(AttributeName::SLUG), $folder->qualifyColumn(AttributeName::MARK_ID)])
//      ->join('folders', fn(JoinClause $joinClause) => $joinClause->on($folder->qualifyColumn('id'), '=', $offerQuery->qualifyColumn(AttributeName::FOLDER_ID)))
//      ->get()
//      ->map(fn(Offer $folder) => new FolderFilterDto([
//        'title' => $folder->title,
//        'id' => $folder->id,
//        'slug' => $folder->slug,
//        'mark_id' => $folder->mark_id,
//        'generations' => new GenerationFilterCollectionDto(collect($generations)->filter(fn($generation) => $generation->folder_id == $folder->id)->all()),
//      ]))
//      ->all();
    return $folderQuery
      ->select([AttributeName::ID, AttributeName::TITLE, AttributeName::SLUG, AttributeName::MARK_ID])
      ->whereHas('offers', fn(OfferQueryBuilder $offerQuery) => $offerQuery
        ->setQuery($query->getQuery())
        ->whereColumn($offerQuery->qualifyColumn('folder_id'), $folderQuery->qualifyColumn('id'))
      )
      ->orderBy('title')
      ->get()
      ->map(fn(Folder $folder) => new FolderFilterDto([
        'title' => $folder->title,
        'id' => $folder->id,
        'slug' => $folder->slug,
        'mark_id' => $folder->mark_id
      ]))
      ->all();
  }
  public function generation(): array
  {
    #Проверка если выбрано несколько моделей
    $folderSlugArray = Arr::get($this->input, 'folder_slug_array', null);
    if($folderSlugArray && count($folderSlugArray) > 1){
      return [];
    }

    $query = $this->applyDependenciesQuery(__FUNCTION__);
    $offerQuery = Offer::query();
    return $offerQuery->setQuery($query->getQuery())
      ->select(AttributeName::GENERATION_ID)->groupBy(AttributeName::GENERATION_ID)
      ->with('generation:id,name,year_begin,year_end,slug')
      ->get()
      ->map(fn(Offer $offer) => new GenerationFilterDto([
        'title' => $offer->generation->name .' ['.$offer->generation->year_begin.' - '.($offer->generation->year_end ?? 'н.в.').']',
        'id' => $offer->generation->id,
        'slug' => $offer->generation->slug
      ]))
      ->all();


    $folderQuery = Folder::query();
    $folderId = Arr::get($this->input, 'folder_id', null);
    #Проверка если выбрано несколько моделей
    $folderSlugArray = Arr::get($this->input, 'folder_slug_array', null);
    if($folderSlugArray && count($folderSlugArray) > 1){
      return [];
    }
    $folderSlug = Arr::get($this->input, 'folder_slug', null);
    if (!$folderId){
      if(!$folderSlug && isset($folderSlugArray[0]) && $folderSlugArray){
        $inputFolderSlug = $folderSlugArray[0];
      }elseif ($folderSlug){
        $inputFolderSlug = $folderSlug;
      }
      $folderId = $folderQuery
        ->select($folderQuery->qualifyColumn(AttributeName::ID))
        ->where($folderQuery->qualifyColumn(AttributeName::SLUG), '=', $inputFolderSlug)
        ->first()
        ->id;
    }
    if(!$folderId){
      return [];
    }
    $generationQuery = Generation::query();


    $query = $this->applyDependenciesQuery(__FUNCTION__);
    return
      $generationQuery
      ->whereHas('offers', fn(OfferQueryBuilder $offerQuery) => $offerQuery
        ->setQuery($query->getQuery())
        ->whereColumn($offerQuery->qualifyColumn(AttributeName::GENERATION_ID), $generationQuery->qualifyColumn('id'))
      )
        ->where(AttributeName::FOLDER_ID, '=', $folderId)
      ->orderBy(AttributeName::YEAR_BEGIN)
      ->get()
        ->map(fn(Generation $generation) => new GenerationFilterDto([
          'title' => $generation->name .' ['.$generation->year_begin.' - '.($generation->year_end ?? 'н.в.').']',
          'id' => $generation->id,
          'slug' => $generation->slug
        ]))
        ->all();

//    $query = $this->applyDependenciesQuery(__FUNCTION__);
//    $getQuery = $query->getQuery();
//    $offerQuery = Offer::query();
//    return $offerQuery
//      ->setQuery($getQuery)
//      ->select($generation->qualifyColumn('*'))
//      ->join('generations', fn(JoinClause $joinClause) => $joinClause->on($generation->qualifyColumn('id'), '=', $offerQuery->qualifyColumn('generation_id')))
//      ->groupBy($generation->qualifyColumn(AttributeName::ID))
//      ->orderBy($generation->qualifyColumn(AttributeName::YEAR_BEGIN))
//      ->get()
//      ->map(fn(Offer $generation) => new GenerationFilterDto([
//        'title' => $generation->name .' ['.$generation->year_begin.' - '.($generation->year_end ?? 'н.в.').']',
//        'id' => $generation->id,
//        'slug' => $generation->slug
//      ]))
//      ->all();
  }
  public function gearbox(): array
  {
    return $this->guide(__FUNCTION__, AttributeName::GEARBOX_ID)
      ->map(fn(Offer $offer) => new GearboxFilterDto([
        'title' => $offer->gearbox->title,
        'title_short' => $offer->gearbox->title_short,
        'title_short_rus' => $offer->gearbox->title_short_rus,
        'id' => $offer->gearbox->id,
        'slug' => $offer->gearbox->name,
      ]))
      ->all();
  }
  public function engineType(): array
  {
    return $this->guide(__FUNCTION__, AttributeName::ENGINE_TYPE_ID)
      ->map(fn(Offer $offer) => new TitleIdSlugDto([
        'title' => $offer->engineType->title,
        'id' => $offer->engineType->id,
        'slug' => $offer->engineType->name,
      ]))
      ->all();
  }
  public function driveType(): array
  {
    return $this->guide(__FUNCTION__, AttributeName::DRIVE_TYPE_ID)
      ->map(fn(Offer $offer) => new TitleIdSlugDto([
        'title' => $offer->driveType->title,
        'id' => $offer->driveType->id,
        'slug' => $offer->driveType->name,
      ]))
      ->all();
  }
  public function bodyType(): array
  {
    return $this->guide(__FUNCTION__,AttributeName::BODY_TYPE_ID)
      ->map(fn(Offer $offer) => new TitleIdSlugDto([
        'title' => $offer->bodyType->title,
        'id' => $offer->bodyType->id,
        'slug' => $offer->bodyType->name,
      ]))
      ->all();
  }
  public function owner(): array
  {
    return $this->guide(__FUNCTION__, AttributeName::OWNER_ID, 'number')
      ->map(fn(Offer $offer) => new TitleIdSlugDto([
        'title' => $offer->owner->title,
        'id' => $offer->owner->id,
        'slug' => $offer->owner->name,
      ]))
      ->all();
  }
  public function enginePower(): array
  {
    return $this->nums(__FUNCTION__, AttributeName::ENGINE_POWER);
  }
  public function year(): array
  {
    return $this->nums(__FUNCTION__, AttributeName::YEAR);
  }
  public function run(): array
  {
    $runArray = $this->nums(__FUNCTION__, AttributeName::RUN);
    return [
      $runArray[0] ?? null,
      isset($runArray[1]) ? Modifiers::roundUp($runArray[1], 5000) : null
    ];
  }
  public function price(): array
  {
    $priceArray = $this->nums(__FUNCTION__, TableConstants::OFFER_SITE.'.'.AttributeName::PRICE);

    return [
      isset($priceArray[0]) ? Modifiers::roundDown($priceArray[0], 100000) : null ,
      isset($priceArray[1]) ? Modifiers::roundUp($priceArray[1], 100000) : null
    ];
  }

  public function chosen(): array
  {
    return [];
  }

  private function guide(string $name, string $column, string $orderBy = AttributeName::ID): Collection
  {
    $query = $this->applyDependenciesQuery($name);
    $offerQuery = Offer::query();

    return $offerQuery->setQuery($query->getQuery())->select($column)->groupBy($column)->with($name)->get();

    return $guideQuery
      ->whereHas('offers', fn(OfferQueryBuilder $offerQuery) => $offerQuery
        ->setQuery($query->getQuery())
        ->whereColumn($offerQuery->qualifyColumn($column), $guideQuery->qualifyColumn('id'))
      )
      ->orderBy($orderBy)
      ->get();
  }

  private function nums(string $name, string $columnName): array
  {
    $query = $this->applyDependenciesQuery($name);
    $num = $query
      ->select([
        DB::raw("min({$query->qualifyColumn($columnName)}) AS min"),
        DB::raw("max({$query->qualifyColumn($columnName)}) AS max"),
      ])
      ->first();

    return [Arr::get($num, 'min'), Arr::get($num, 'max')];
  }

  private function applyDependenciesQuery(string $name): OfferQueryBuilder
  {
    $input = $this->input;
    $ignored = $this->getExceptsDescendants($name);
    $ignored[] = 'sort';
    return $this->query(Arr::except($input, $ignored));
  }

  private function query(array $input): OfferQueryBuilder
  {
    $builder = $this->queryBuilder;
    return $builder($input);
  }
}
