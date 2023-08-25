<?php

declare(strict_types=1);

namespace App\GraphQL\Fields;

use App\Complex\GraphQL\CustomField;
use Carbon\Carbon;
use Jenssegers\Date\Date;
use GraphQL\Type\Definition\Type;

class FormatDate extends CustomField
{
  protected string $description = 'Format output date';
  public function type(): Type
  {
    return Type::string();
  }
  public function args(): array
  {
    return [
      'format' => [
        'type' => Type::string(),
        'defaultValue' => 'Y-m-d H:i:s',
      ],
      'relative' => [
        'type' => Type::boolean(),
        'defaultValue' => false,
      ],
      'sub_day' => [
        'type' => Type::boolean(),
        'defaultValue' => false
      ]
    ];
  }

  protected function resolve($root, array $args) : ?string
  {
    if($args['sub_day']) {
      $subDay = Date::now()->subDay();
      return $subDay->format($args['format']);
    }
    if(is_array($root)){
      $date = $root[$this->getProperty()];
    }else{
      $date = $root->{$this->getProperty()};
    }
    $date = new Date($date);
    if (!$date instanceof Date) {
      return null;
    }
    if ($args['relative']) {
      return $date->diffForHumans();
    }
    return $date->format($args['format']);
  }
}
