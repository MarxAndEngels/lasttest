<?php

declare(strict_types=1);

namespace App\Complex\GraphQL;

use GraphQL\Type\Definition\FieldDefinition;
use Rebing\GraphQL\Support\Type as GraphQLType;

abstract class Type extends GraphQLType
{
  protected string $name;
  protected string $model;

  public function attributes(): array
  {
    $attributes = [
      'name' => $this->name,
    ];

    if (isset($this->model)) {
      $attributes['model'] = $this->model;
    }
    return $attributes;
  }

  public function getFields(): array
  {
    $fields = $this->fields();
    $allFields = [];

    foreach ($fields as $name => $field) {
      if (is_string($field)) {
        $field = app($field);
        $field->name = $name;
        $allFields[$name] = $field->toArray();
      } elseif ($field instanceof CustomField) {
        $allFields[$field->getName()] = $field->toArray();
      } elseif ($field instanceof Field) {
        $field->name = $name;
        $allFields[$name] = $field->toArray();
      } elseif ($field instanceof FieldDefinition) {
        $allFields[$field->name] = $field;
      } else {
        if ($field instanceof \App\Complex\GraphQL\Field) {
          $name = $field->getName();
          $field = $field->toArray();
        }

        $resolver = $this->getFieldResolver($name, $field);

        if ($resolver) {
          $field['resolve'] = $resolver;
        }
        $allFields[$name] = $field;
      }
    }

    return $allFields;
  }
}
