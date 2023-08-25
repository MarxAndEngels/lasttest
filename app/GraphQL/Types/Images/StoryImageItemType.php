<?php

namespace App\GraphQL\Types\Images;


use App\Complex\GraphQL\Field;
use App\Complex\GraphQL\Type as GraphQLType;
use App\Constants\MediaConstants;
use GraphQL\Type\Definition\Type;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

final class StoryImageItemType extends GraphQLType
{
  protected string $name = 'StoryImage';
  protected string $model = Media::class;

  public function fields(): array
  {
    $mediaCollection = collect(MediaConstants::CONVERSION_COLLECTION[MediaConstants::MEDIA_STORIES]);
    $mediaCollectionWebp = collect(MediaConstants::CONVERSION_COLLECTION_WEBP[MediaConstants::MEDIA_STORIES]);

    $fields = $mediaCollection
      ->map(fn($conversion) => Field::make($conversion)->type(Type::string())
      )->all();
    $fieldsWebp = $mediaCollectionWebp
      ->map(fn($conversion) => Field::make($conversion)->type(Type::string())
      )->all();
    $fieldSrc = [Field::make(MediaConstants::CONVERSION_SRC)->type(Type::string())];
    return array_merge($fields, $fieldsWebp, $fieldSrc);
  }
}
