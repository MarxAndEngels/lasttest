<?php

namespace App\GraphQL\Types\Images;


use App\Complex\GraphQL\Field;
use App\Complex\GraphQL\Type as GraphQLType;
use App\Constants\MediaConstants;
use GraphQL\Type\Definition\Type;

final class ArticleImagePreviewItemType extends GraphQLType
{
  protected string $name = 'ArticleImagePreview';

  public function fields(): array
  {
    $mediaCollection = collect(MediaConstants::CONVERSION_COLLECTION[MediaConstants::MEDIA_ARTICLE_PREVIEWS]);
    $mediaCollectionWebp = collect(MediaConstants::CONVERSION_COLLECTION_WEBP[MediaConstants::MEDIA_ARTICLE_PREVIEWS]);

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
