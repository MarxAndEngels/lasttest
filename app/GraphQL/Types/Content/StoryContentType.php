<?php

namespace App\GraphQL\Types\Content;


use App\Complex\GraphQL\Field;
use App\Complex\GraphQL\Type as GraphQLType;
use App\Constants\Attributes\AttributeName;
use App\Constants\MediaConstants;
use App\GraphQL\Types\Images\StoryContentImageItemType;
use App\Models\StoryContent;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use function collect;

final class StoryContentType extends GraphQLType
{
  protected string $name = 'StoryContent';

  public function fields(): array
  {
    return [
      Field::make(AttributeName::ID)
        ->type(Type::nonNull(Type::int())),

      Field::make(AttributeName::TITLE)
        ->type(Type::string()),

      Field::make(AttributeName::BODY)
        ->type(Type::string()),

      Field::make(AttributeName::BUTTON_TITLE)
        ->type(Type::string()),

      Field::make(AttributeName::BUTTON_LINK)
        ->type(Type::string()),

      Field::make(AttributeName::BUTTON_COLOR)
        ->type(Type::string()),

      Field::make(AttributeName::IMAGE)
        ->isNotSelectable()
        ->type(GraphQL::type(StoryContentImageItemType::class))
        ->resolve(function (StoryContent $story) {
          $collectionName = MediaConstants::MEDIA_STORY_CONTENTS;
          $media = $story->getFirstMedia($collectionName);
          if(!$media){
            return [];
          }

          $outputImages = collect(MediaConstants::CONVERSION_COLLECTION[$collectionName])->mapWithKeys(fn($item) => [
            $item => $media->getUrl($item) ?? 'test'
          ])->all();
          $outputImagesWebp = collect(MediaConstants::CONVERSION_COLLECTION_WEBP[$collectionName])->mapWithKeys(fn($item) => [
            $item => $media->getUrl($item) ?? 'test'
          ])->all();
          $outputImageSrc = [MediaConstants::CONVERSION_SRC => $media->getUrl()];

          return array_merge($outputImageSrc, $outputImages, $outputImagesWebp);
        })
    ];
  }
}
