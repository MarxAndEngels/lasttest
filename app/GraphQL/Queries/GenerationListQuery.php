<?php

namespace App\GraphQL\Queries;

use App\Constants\Attributes\AttributeName;
use App\Constants\TableConstants;
use App\GraphQL\Types\TechnicalTypes\GenerationType;
use App\Models\Generation;
use App\Models\Site;
use App\QueryBuilders\OfferQueryBuilder;
use Closure;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Query;


/**
 * Class FoldersQuery
 * @package App\GraphQL\Queries
 */
class GenerationListQuery extends Query
{

  protected $attributes = [
    'name' => 'generations',
    'description' => 'Список поколений',
  ];

  public function type(): Type
  {
    return Type::listOf(GraphQL::type(GenerationType::class));
  }

  public function args(): array
  {
    return [
      AttributeName::MARK_SLUG => ['name' => AttributeName::MARK_SLUG, 'type' => Type::string()],
      AttributeName::FOLDER_SLUG => ['name' => AttributeName::FOLDER_SLUG, 'type' => Type::string()],
      AttributeName::FOLDER_ID => ['name' => AttributeName::FOLDER_ID, 'type' => Type::int()],
      AttributeName::SITE_ID => ['name' => AttributeName::SITE_ID, 'type' => Type::int(), 'rules' => ['required']],
      AttributeName::CATEGORY => ['name' => AttributeName::CATEGORY, 'type' => Type::string()]
    ];
  }

  public function resolve($root, array $args, $context, ResolveInfo $info, Closure $getSelectFields)
  {
    $fields = $getSelectFields();
    $select = $fields->getSelect();
    $generationQuery = Generation::query()->select($select);
    if(isset($args[AttributeName::FOLDER_ID])){
      $generationQuery->whereFolderId($args[AttributeName::FOLDER_ID]);
    }
    if (isset($args[AttributeName::MARK_SLUG])){
      $generationQuery->whereMarkSlug($args[AttributeName::MARK_SLUG]);
    }
    if(isset($args[AttributeName::FOLDER_SLUG])){
      $generationQuery->whereFolderSlug($args[AttributeName::FOLDER_SLUG]);
    }
    $siteId = $args[AttributeName::SITE_ID];
    $currentSiteQuery = Site::query()->select(AttributeName::ID, AttributeName::FILTER, AttributeName::CATEGORY_ASSOCIATION);
    $currentSiteModel = $currentSiteQuery->whereId($siteId)->first()->toArray();
    $filter = $currentSiteModel[AttributeName::FILTER];

    $siteModel = $currentSiteQuery->getParentId($siteId)->first();

    if (isset($args[AttributeName::CATEGORY]) && $args[AttributeName::CATEGORY]) {
      $filter[AttributeName::CATEGORY] = $this->getCategoryFromValue($args[AttributeName::CATEGORY], $currentSiteModel[AttributeName::CATEGORY_ASSOCIATION]);
    }

    $generationQuery->withCount(
      [
        TableConstants::OFFERS => fn(OfferQueryBuilder $builder) =>
        $builder->withPriceForSite($siteModel->id, $filter)
      ]
    )->whereHas(TableConstants::OFFERS, fn (OfferQueryBuilder $builder) => $builder
      ->withPriceForSite($siteModel->id, $filter)
    )
      ->orderBy($generationQuery->qualifyColumn(AttributeName::YEAR_BEGIN));
    return $generationQuery->get();

  }
  private function getCategoryFromValue(string $category, array $categoryAssociation): string
  {
    return array_search($category, $categoryAssociation, true);
  }
}
