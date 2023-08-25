<?php

namespace App\GraphQL\Queries;

use App\Constants\Attributes\AttributeName;
use App\Constants\TableConstants;
use App\GraphQL\Types\OfferType;
use App\Models\Offer;
use App\Models\Site;
use Closure;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use App\Complex\GraphQL\Query;

final class OfferQuery extends Query
{

  protected $attributes = [
    'name' => 'offer',
    'description' => 'Объявление',
  ];

  public function type(): Type
  {
    return GraphQL::type(OfferType::class);
  }

  public function args(): array
  {
    return [
      AttributeName::SITE_ID => ['name' => AttributeName::SITE_ID, 'type' => Type::int(), 'rules' => ['required']],
      AttributeName::MARK_SLUG => ['name' => AttributeName::MARK_SLUG, 'type' => Type::string(), 'rules' => ['required']],
      AttributeName::FOLDER_SLUG => ['name' => AttributeName::FOLDER_SLUG, 'type' => Type::string(), 'rules' => ['required']],
      AttributeName::GENERATION_SLUG => ['name' => AttributeName::GENERATION_SLUG, 'type' => Type::string()],
      AttributeName::EXTERNAL_ID => ['name' => AttributeName::EXTERNAL_ID, 'type' => Type::int(), 'rules' => ['required']],
    ];
  }

  public function resolve($root, array $args, $context, ResolveInfo $info, Closure $getSelectFields)
  {
    if (!isset($args[AttributeName::SITE_ID])) {
      return null;
    }
    $fields = $getSelectFields();
    $select = $fields->getSelect();

    $offerQuery = Offer::query()
      ->select($select)
      ->addSelect(
        [
          TableConstants::OFFERS.'.'.AttributeName::EXTERNAL_UNIQUE_ID,
          TableConstants::OFFERS.'.'.AttributeName::CATEGORY_ENUM,
          TableConstants::OFFERS.'.'.AttributeName::TYPE_ENUM,
          TableConstants::OFFERS.'.'.AttributeName::COMMUNICATIONS_COUNT,
          TableConstants::OFFER_SITE.'.'.AttributeName::IS_ACTIVE,
          TableConstants::OFFER_SITE.'.'.AttributeName::PRICE,
          TableConstants::OFFER_SITE.'.'.AttributeName::PRICE_OLD,
          TableConstants::OFFER_SITE.'.'.AttributeName::DESCRIPTION,
          TableConstants::PRICE_OLD_SITES.'.'.AttributeName::LOGIC,
          TableConstants::SITES.'.'.AttributeName::CATEGORY_ASSOCIATION,

        ]
      )
      ->with($fields->getRelations());

    $args[AttributeName::SITE_ID] = [
      'id' => $args[AttributeName::SITE_ID],
      'onlyActive' => false
    ];


    $offer =  $offerQuery->filter($args)->first();
    if (!$offer){
      return $this->notFound();
    }
    // Если не активное объявление, возвращаем
    if(!$offer->is_active){
      return $offer;
    }
    $offerQuery = Offer::query()->whereExternalUniqueId($offer->external_unique_id);
    $args[AttributeName::SITE_ID]['onlyActive'] = true;
    $siteQuery = Site::query()->select(AttributeName::ID,AttributeName::FILTER);
    $siteModel = $siteQuery->getParentId($args[AttributeName::SITE_ID]['id'])->first();
    $offer->is_active = $offerQuery->withPriceForSite($siteModel->id, $siteModel->filter, $args[AttributeName::SITE_ID]['onlyActive'], false)->exists();
    return $offer;
  }

}
