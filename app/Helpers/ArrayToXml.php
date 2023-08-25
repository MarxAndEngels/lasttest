<?php
declare(strict_types=1);

namespace App\Helpers;

use Carbon\Carbon;
use Spatie\ArrayToXml\ArrayToXml as BaseArrayToXml;

class ArrayToXml
{
  public static function ArrayToYandexFeedXml(array $offers): string
  {
    return BaseArrayToXml::convert(['cars' => ['car' => $offers]], 'data', true, 'UTF-8', '1.0');
  }

  public static function ArrayToYandexFeedYml(array $data): string
  {
    return BaseArrayToXml::convert(
      $data,
      [
        'rootElementName' => 'yml_catalog',
        '_attributes' => [
          'date' => Carbon::now()->toRfc3339String(),
        ]
      ],
      true,
      'UTF-8');
  }

  public static function ArrayToGoogleFeedXml(array $data): string
  {
    return BaseArrayToXml::convert(
      $data,
      [
        'rootElementName' => 'rss',
        '_attributes' => [
          'version' => '2.0',
          'xmlns:g' => 'http://base.google.com/ns/1.0'
        ]
      ], true, 'UTF-8');
  }

  public static function ArrayToSitemapXml(array $data): string
  {
    return BaseArrayToXml::convert($data, [
      'rootElementName' => 'urlset',
      '_attributes' => ['xmlns' => "http://www.sitemaps.org/schemas/sitemap/0.9"]
    ], true, 'UTF-8', '1.0');
  }
}
