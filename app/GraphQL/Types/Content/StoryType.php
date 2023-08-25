<?php

namespace App\GraphQL\Types\Content;

use App\Complex\GraphQL\Field;
use App\Complex\GraphQL\Type as GraphQLType;
use App\Constants\Attributes\AttributeName;
use App\Constants\MediaConstants;
use App\GraphQL\Types\Images\StoryImageItemType;
use App\Models\Story;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use function collect;


final class StoryType extends GraphQLType
{
  protected string $name = 'Story';
  protected string $model = Story::class;

  public function fields(): array
  {
    return [
      Field::make(AttributeName::ID)
        ->type(Type::nonNull(Type::int())),

      Field::make(AttributeName::TITLE)
        ->type(Type::string()),

      Field::make('stories')
        ->type(Type::listOf(GraphQL::type(StoryContentType::class))),


      Field::make(AttributeName::IMAGE)
        ->isNotSelectable()
        ->type(GraphQL::type(StoryImageItemType::class))
        ->resolve(function (Story $story) {
          $collectionName = MediaConstants::MEDIA_STORIES;
          $media = $story->getFirstMedia($collectionName);
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
