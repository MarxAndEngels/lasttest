<?php

declare(strict_types=1);

namespace App\Http\Controllers;

ini_set('memory_limit', '-1');

use App\Services\FeedService;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class FeedController extends Controller
{
  protected FeedService $feedService;

  public function __construct(FeedService $feedService)
  {
    $this->feedService = $feedService;
  }

  public function feedYandexXml(string $slug): Response
  {
    return response($this->feedService->createFeedYandexXml($slug))
      ->header('Content-Type', 'application/xml');
  }

  public function feedGoogleXml(string $slug): Response
  {
    return response($this->feedService->createFeedGoogleXml($slug))
      ->header('Content-Type', 'application/xml');

  }

  public function feedYandexXmlFilter(string $siteSlug, string $filterSlug): Response
  {
    return response($this->feedService->createFeedYandexXmlFilter($siteSlug, $filterSlug))
      ->header('Content-Type', 'application/xml');

  }

  public function feedFileXmlFilter(string $webHook, string $format, string $siteSlug, string $filterSlug): BinaryFileResponse|Response
  {
    $feedFilter = $this->feedService->getFeedFilter($siteSlug, $filterSlug);
    if ($feedFilter['feedFilter']->generate_file) {
      $filePath = "feeds/{$webHook}/{$format}/{$siteSlug}/{$filterSlug}.xml";
      //Возвратим файл
      if (Storage::disk('public')->exists($filePath)) {
        return response()->file(Storage::disk('public')->path($filePath));
      }
    }
    abort(404);
  }
  public function feedGoogleXmlFilter(string $siteSlug, string $filterSlug): Response
  {
    return response($this->feedService->createFeedGoogleXmlFilter($siteSlug, $filterSlug))
      ->header('Content-Type', 'application/xml');
  }

  public function feedYandexYmlCatalogFilter(string $siteSlug, string $filterSlug): Response
  {
    return response($this->feedService->createFeedYandexYmlCatalogFilter($siteSlug, $filterSlug))
      ->header('Content-Type', 'application/xml');
  }

  public function feedYandexYmlFilter(string $siteSlug, string $filterSlug): Response
  {
    return response($this->feedService->createFeedYandexYmlFilter($siteSlug, $filterSlug))
      ->header('Content-Type', 'application/xml');
  }

  public function feedVkXmlFilter(string $siteSlug, string $filterSlug): Response
  {
    return response($this->feedService->createFeedVkXmlFilter($siteSlug, $filterSlug))
      ->header('Content-Type', 'application/xml');
  }
}
