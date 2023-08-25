<?php
declare(strict_types=1);

namespace App\Helpers;

class Modifiers
{
  public static function declension(int $count, string $one, string $few, string $many, $number = false): string
  {
    if ($count > 20) $count %= 10;
    switch($count){
      case $count == 1:
        $text = $one;
        break;
      case ($count >= 2 && $count <= 4):
        $text = $few;
        break;
      default:
        $text = $many;
    }

    return $number ? "{$count} {$text}" : $text;
  }
  public static function lcfirst(string $str): string
  {
    return \Str::lcfirst($str);
  }
  public static function ucfirst(string $str): string
  {
    return \Str::ucfirst($str);
  }
  public static function toLower(string $string): string
  {
    return \Str::lower($string);
  }
  public static function numberFormatFloat(float $number): string
  {
    return number_format($number, 1, '.', '');
  }
  public static function numberFormatPrice(float $number): string
  {
    return number_format($number, 0, '.', ' ');
  }
  public static function roundDown(int|float $int, int $n): int
  {
    return (int)floor($int / $n) * $n;
  }
  public static function roundUp(int|float$int, int $n): int
  {
    return (int)ceil($int / $n) * $n;
  }
}
