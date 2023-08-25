<?php

namespace App\Vendor\MediaLibrary;

use Spatie\MediaLibrary\Support\PathGenerator\PathGenerator as VendorPathGenerator;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Str;
class PathGeneratorCustom implements VendorPathGenerator
{
  /*
     * Get the path for the given media, relative to the root storage path.
     */
  public function getPath(Media $media): string
  {
    return $this->getBasePath($media);
  }

  /*
     * Get the path for conversions of the given media, relative to the root storage path.
     */
  public function getPathForConversions(Media $media): string
  {
    return $this->getBasePath($media);
  }

  /*
     * Get the path for responsive images of the given media, relative to the root storage path.
     */
  public function getPathForResponsiveImages(Media $media): string
  {
    return $this->getBasePath($media);
  }

  /*
     * Get a unique base path for the given media.
     */
  protected function getBasePath(Media $media): string
  {
    return $this->generateDirectory($media->collection_name, "{$media->model_type}{$media->model_id}", $media->getKey());
  }

  private function getHash(string $string): string
  {
    $salt = 'media';
    return sha1("{$string}{$salt}");
  }

  private function generateDirectory(string $collectionName, string $path, string $key): string
  {
    $hash = $this->getHash("{$collectionName}/{$path}{$key}");
    return $collectionName . '/'
            . Str::substr($hash, 0, 4) . '/'
            . Str::substr($hash, 4, 9) . '/';
  }
}
