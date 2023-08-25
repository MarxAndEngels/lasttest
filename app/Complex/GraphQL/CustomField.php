<?php
declare(strict_types=1);

namespace App\Complex\GraphQL;

use Rebing\GraphQL\Support\Field;

abstract class CustomField extends Field
{

  protected string $description;

  public static function make(string $name)
  {
    return new static($name);
  }

  public function __construct(string $name = null)
  {
    if (isset($name)) {
      $this->attributes['name'] = $name;
    }
    $this->attributes['description'] = $this->description;
  }

  public function alias(string $alias) : self
  {
    $this->attributes['alias'] = $alias;
    return $this;
  }

  public function getName() : string
  {
    return $this->attributes['name'];
  }

  protected function getProperty(): string
  {
    return $this->attributes['alias'] ?? $this->attributes['name'];
  }

}
