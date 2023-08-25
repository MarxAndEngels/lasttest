<?php

namespace App\GraphQL\Types\Images;


use App\Complex\GraphQL\Field;
use App\Complex\GraphQL\Type as GraphQLType;
use App\Constants\MediaConstants;
use GraphQL\Type\Definition\Type;

final class BankImageCarItemType extends GraphQLType
{
  protected string $name = 'BankImageCar';

  public function fields(): array
  {
    $mediaCollection = collect(MediaConstants::CONVERSION_COLLECTION[MediaConstants::MEDIA_BANKS_CAR]);
    $mediaCollectionWebp = collect(MediaConstants::CONVERSION_COLLECTION_WEBP[MediaConstants::MEDIA_BANKS_CAR]);

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
