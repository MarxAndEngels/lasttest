<?php

use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\PdfController;
use App\Http\Controllers\SitemapController;
use Illuminate\Support\Facades\Route;
use \App\Constants\RouteNameConstants;
use App\Http\Controllers\FeedController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function(){
  return view('welcome');
  abort(403);
})->name('home');

//Рекламные фиды
Route::get("/feeds/yandex/xml/{slug}", [FeedController::class, 'feedYandexXml'])->name(RouteNameConstants::YANDEX_XML_FEED);
Route::get("/feeds/google/xml/{slug}", [FeedController::class, 'feedGoogleXml'])->name(RouteNameConstants::GOOGLE_XML_FEED);

Route::get("/feeds/yandex/xml/{site_slug}/{filter_slug}", [FeedController::class, 'feedYandexXmlFilter'])
  ->name(RouteNameConstants::YANDEX_XML_FEED_FILTER);
Route::get("/feeds/google/xml/{site_slug}/{filter_slug}", [FeedController::class, 'feedGoogleXmlFilter'])
  ->name(RouteNameConstants::GOOGLE_XML_FEED_FILTER);
Route::get("/feeds/yandex/yml/short/{site_slug}/{filter_slug}", [FeedController::class, 'feedYandexYmlFilter'])
  ->name(RouteNameConstants::YANDEX_YML_SHORT_FEED_FILTER);
Route::get("/feeds/vk/xml/{site_slug}/{filter_slug}", [FeedController::class, 'feedVkXmlFilter'])
  ->name(RouteNameConstants::VK_XML_FEED_FILTER);

//Рекламный файловый фид

Route::get("/feeds_file/{web_hook}/{format}/{site_slug}/{filter_slug}", [FeedController::class, 'feedFileXmlFilter'])
  ->name(RouteNameConstants::XML_FEED_FILTER_FILE);

//Вебмастер фид
Route::get("/feeds/yandex/yml/{site_slug}/{filter_slug}", [FeedController::class, 'feedYandexYmlCatalogFilter'])
  ->name(RouteNameConstants::YANDEX_YML_FEED_FILTER);

//Sitemap
Route::get("/sitemap/xml/{site_slug}", [SitemapController::class, 'sitemap'])
  ->name(RouteNameConstants::SITEMAP_XML);

//PDF
Route::post("/pdf/autoteka", [PdfController::class, 'makePdfAutoteka'])
  ->name(RouteNameConstants::PDF_AUTOTEKA);

