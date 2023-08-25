<?php

namespace App\GraphQL\Types\Images;


use App\Complex\GraphQL\Field;
use App\Complex\GraphQL\Type as GraphQLType;
use App\Constants\MediaConstants;
use GraphQL\Type\Definition\Type;

final class ArticleImageItemType extends GraphQLType
{
  protected string $name = 'ArticleImageItem';

  public function fields(): array
  {
    $mediaCollection = collect(MediaConstants::CONVERSION_COLLECTION[MediaConstants::MEDIA_ARTICLES]);
    $mediaCollectionWebp = collect(MediaConstants::CONVERSION_COLLECTION_WEBP[MediaConstants::MEDIA_ARTICLES]);

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
