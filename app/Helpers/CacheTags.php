<?php
declare(strict_types=1);

namespace App\Helpers;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class CacheTags
{

  /**
   * @param array|mixed $tags
   */
  public static function rememberForever($tags, string $cacheKey, array $data): array
  {
    return Cache::tags($tags)->rememberForever($cacheKey, fn() => $data);
  }

  public static function remember(array $tags, string $cacheKey, array $data, int $minutes = 30): array
  {
    return Cache::tags($tags)->remember($cacheKey, now()->addMinutes($minutes), fn() => $data);
  }

  /**
   * @param array|mixed $tags
   */
  public static function flushTags($tags): bool
  {
    return Cache::tags($tags)->flush();
  }

  public static function flush($cacheKey): bool
  {
    return Cache::forget($cacheKey);
  }

  /**
   * @param array|mixed $tags
   * @param mixed $data
   */
  public static function forever($tags, string $cacheKey, $data): void
  {
    Cache::tags($tags)->forever($cacheKey, $data);
  }

  /**
   * @param string $key
   * @return mixed
   */
  public static function get(string $key)
  {
    return Cache::get($key);
  }

  public static function has(string $key): bool
  {
    return Cache::has($key);
  }

  public static function hasNot(string $key): bool
  {
    return !Cache::has($key);
  }

  public static function getCacheKey(string $key, ?array $args = null, ?string $prepend = 'filters'):string
  {
    if($args){
      $key = Str::of(json_encode($args))->pipe('md5')->prepend("{$prepend}.{$key}.");
    }else{
      $key = "{$prepend}.{$key}";
    }
    return (string)$key;
  }
}
