<?php

namespace App\GraphQL\Types\Content;


use App\Complex\GraphQL\Field;
use App\Complex\GraphQL\Type as GraphQLType;
use App\Constants\Attributes\AttributeName;
use App\Constants\MediaConstants;
use App\GraphQL\Types\Images\StationImageItemType;
use App\Models\Station;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use function collect;

final class StationType extends GraphQLType
{
  protected string $name = 'Station';

  public function fields(): array
  {
    return [
      Field::make(AttributeName::ID)
        ->type(Type::nonNull(Type::int())),

      Field::make(AttributeName::TITLE)
        ->type(Type::string()),

      Field::make(AttributeName::PRICE)
        ->type(Type::string()),

      Field::make(AttributeName::IMAGE)
        ->isNotSelectable()
        ->type(GraphQL::type(StationImageItemType::class))
        ->resolve(function (Station $station) {

          $collectionName = MediaConstants::MEDIA_STATIONS;
          $media = $station->getFirstMedia($collectionName);
          if(!$media){
            return [];
          }

          $outputImages = collect(MediaConstants::CONVERSION_COLLECTION[$collectionName])->mapWithKeys(fn($item) => [
            $item => $media->getUrl($item)
          ])->all();
          $outputImagesWebp = collect(MediaConstants::CONVERSION_COLLECTION_WEBP[$collectionName])->mapWithKeys(fn($item) => [
            $item => $media->getUrl($item)
          ])->all();
          $outputImageSrc = [MediaConstants::CONVERSION_SRC => $media->getUrl()];

          return array_merge($outputImageSrc, $outputImages, $outputImagesWebp);
        })

    ];
  }
}
