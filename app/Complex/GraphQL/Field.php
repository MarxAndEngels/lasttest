<?php
declare(strict_types=1);

namespace App\Complex\GraphQL;

use GraphQL\Type\Definition\Type;
use Closure;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Database\Eloquent\Relations\Relation;
final class Field implements Arrayable
{
  private array $options;

  public static function make(string $name): self
  {
    return new self($name);
  }

  public function __construct(string $name)
  {
    $this->options['name'] = $name;
  }

  public function description(string $description): self
  {
    $this->options['description'] = $description;
    return $this;
  }

  public function deprecationReason(string $deprecationReason): self
  {
    $this->options['deprecationReason'] = $deprecationReason;
    return $this;
  }

  public function type(Type $type): self
  {
    $this->options['type'] = $type;
    return $this;
  }

  public function query(Closure $query): self
  {
    $this->options['query'] = $query;
    return $this;
  }

  public function relationQuery(Closure $query): self
  {
    return $this->query(fn(array $args, Relation $relation) => call_user_func_array(
      $query, [$args, $relation->getQuery()]
    ));
  }

  public function alias(string $alias): self
  {
    $this->options['alias'] = $alias;
    return $this;
  }

  public function rules(array $rules): self
  {
    $this->options['rules'] = $rules;
    return $this;
  }

  public function getName(): string
  {
    return $this->options['name'];
  }

  public function isNotSelectable(): self
  {
    $this->options['selectable'] = false;
    return $this;
  }

  public function isNotRelation(): self
  {
    $this->options['is_relation'] = false;
    return $this;
  }
  public function resolve(Closure $resolve): self
  {
    $this->options['resolve'] = $resolve;
    return $this;
  }
  public function args(array $args): self
  {
    $this->options['args'] = $args;
    return $this;
  }
  public function toArray(): array
  {
    return $this->options;
  }

}
