<?php

namespace App\GraphQL\Types;


use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;

/**
 * Class OrderType
 * @package App\GraphQL\Types
 */
class FeedbackType extends GraphQLType {

  /**
   * @var array
   */
  protected $attributes = [
    'name' => 'Feedback',
    'description' => 'Order object',
    'model' => FeedbackType::class,
  ];

  /**
   * @return array
   */
  public function fields(): array
  {
    return [
      'id' => [
        'type' => Type::int(),
        'description' => 'The id of the Order'
      ],
    ];

  }
}
