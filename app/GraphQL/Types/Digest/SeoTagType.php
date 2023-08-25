<?php

namespace App\GraphQL\Types\Digest;


use App\Complex\GraphQL\Field;
use App\Complex\GraphQL\Type as GraphQLType;
use App\Constants\Attributes\AttributeName;
use App\GraphQL\Types\Content\SiteTextType;
use Rebing\GraphQL\Support\Facades\GraphQL;
use GraphQL\Type\Definition\Type;

final class SeoTagType extends GraphQLType
{
  protected string $name = 'SeoTag';

  public function fields(): array
  {
    return [
      Field::make(AttributeName::PAGE_TITLE)
        ->type(Type::string())
        ->description('The title of the seo tags'),
      Field::make(AttributeName::TITLE)
        ->type(Type::string())
        ->description('The title of the seo tags'),

      Field::make(AttributeName::DESCRIPTION)
        ->type(Type::string())
        ->description('The description of the seo tags'),
      Field::make(AttributeName::CRUMBS)
        ->type(Type::listOf(GraphQL::type(SeoTagCrumbsType::class))),

      Field::make(AttributeName::SITE_TEXT)
        ->isNotSelectable()
        ->type(GraphQL::type(SiteTextType::class))
    ];
  }
}
