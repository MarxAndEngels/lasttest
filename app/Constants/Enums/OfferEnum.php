<?php

namespace App\Constants\Enums;

class OfferEnum
{

  public const CARS = 'CARS';
  public const COMMERCIAL = 'COMMERCIAL';
  public const USED = 'USED';
  public const NEW = 'NEW';

  public const EUROPE = 'EUROPE';

  public const C = 'С';
  public const P = 'Р';
  public const M = 'М';

  public const CATEGORY_ENUM = [self::CARS, self::COMMERCIAL, self::EUROPE];
  public const SECTION_ENUM = [self::USED, self::NEW];
  public const TYPE_ENUM = [self::C, self::P, self::M];


}
