<?php

namespace App\Jobs;


use App\Constants\Attributes\AttributeName;
use App\Models\FeedFilter;
use App\Services\FeedService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;

class CreateFeedJob implements ShouldQueue
{
  use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

  protected FeedService $feedService;

  /**
   * Create a new job instance.
   *
   * @return void
   */
  public function __construct()
  {
    $this->onQueue('heavy');
    $this->feedService = new FeedService();
  }

  /**
   * Execute the job.
   *
   * @return void
   */
  public function handle(): void
  {
    $feedFilterQuery = FeedFilter::query();
    $feedFilterQuery->where(AttributeName::GENERATE_FILE, '=', true)
      ->with('site')
      ->get()
      ->each(function (FeedFilter $feedFilter) {
      $siteSlug = $feedFilter->site->slug;
      $filterSlug = $feedFilter->name;
      if ($feedFilter->feed_yandex_xml) {
        Storage::disk('public')->put("feeds/yandex/xml/{$siteSlug}/{$filterSlug}.xml", $this->feedService->createFeedYandexXmlFilter($siteSlug, $filterSlug));
      }
      if ($feedFilter->feed_yandex_yml) {
        Storage::disk('public')->put("feeds/yandex/yml/{$siteSlug}/{$filterSlug}.xml", $this->feedService->createFeedYandexYmlCatalogFilter($siteSlug, $filterSlug));
      }
      if ($feedFilter->feed_vk_xml) {
        Storage::disk('public')->put("feeds/vk/xml/{$siteSlug}/{$filterSlug}.xml", $this->feedService->createFeedVkXmlFilter($siteSlug, $filterSlug));
      }
      $feedFilter->generate_file_at = Carbon::now();
      $feedFilter->save();
    });

  }
}
