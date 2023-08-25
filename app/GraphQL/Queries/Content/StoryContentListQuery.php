<?php

namespace App\GraphQL\Queries\Content;

use App\Constants\Attributes\AttributeName;
use App\GraphQL\Types\Content\StoryContentType;
use App\Models\StoryContent;
use Closure;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Query;


/**
 * Class FoldersQuery
 * @package App\GraphQL\Queries
 */
class StoryContentListQuery extends Query
{

  protected $attributes = [
    'name' => 'storyContents',
    'description' => 'Список содержимого историй',
  ];

  public function type(): Type
  {
    return Type::listOf(GraphQL::type(StoryContentType::class));
  }

  public function args(): array
  {
    return [
      AttributeName::SITE_ID => ['name' => AttributeName::SITE_ID, 'type' => Type::int(), 'rules' => ['required']],
      AttributeName::STORY_ID => ['name' => AttributeName::STORY_ID, 'type' => Type::int(), 'rules' => ['required']]
    ];
  }

  public function resolve($root, array $args, $context, ResolveInfo $info, Closure $getSelectFields)
  {
    $siteId = $args[AttributeName::SITE_ID];
    $storyId = $args[AttributeName::STORY_ID];

    if (!$siteId || !$storyId) {
      return null;
    }

    $fields = $getSelectFields();
    $select = $fields->getSelect();
    $storyContentQuery = StoryContent::query()
      ->select($select)
      ->with($fields->getRelations())
      ->where(AttributeName::STORY_ID, '=', $storyId)
      ->where(AttributeName::IS_ACTIVE, '=', 1)
      ->orderBy(AttributeName::ORDER_COLUMN, 'ASC');

    return $storyContentQuery->get();
  }
}
