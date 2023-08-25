<?php

declare(strict_types=1);

namespace App\GraphQL\Types\FilterFieldTypes;


final class OwnerFieldType extends TitleIdSlugType
{
  protected string $name = 'OwnerField';
}
