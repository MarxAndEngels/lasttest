<?php

namespace App\Http\Controllers;

use App\Services\SitemapService;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
  public function sitemap(string $siteSlug, SitemapService $sitemapService): Response
  {
    return response($sitemapService->createSitemap($siteSlug))
      ->header('Content-Type', 'application/xml');
  }
}
