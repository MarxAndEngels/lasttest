<?php

namespace App\Jobs;

use App\Constants\Attributes\AttributeName;
use App\Constants\Enums\OfferEnum;
use App\Dto\Feeds\OfferFeedDto;
use App\Models\Offer;
use App\Models\TelegramChannelSite;
use App\Services\ApiClient\TelegramClient;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Blade;

class PostOfferTelegramChannelJob implements ShouldQueue
{
  protected int $hour;

  use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

  /**
   * Create a new job instance.
   *
   * @return void
   */
  public function __construct()
  {
    $this->hour = (int)Carbon::now()->format('H');
  }

  /**
   * Execute the job.
   *
   * @return void
   */
  public function handle()
  {
    if ($this->hour >= 10 && $this->hour <= 19) {
      $tgChannelQuery = TelegramChannelSite::query();
      $tgChannels = $tgChannelQuery->where(AttributeName::IS_ACTIVE, '=', 1)->with('site')->get();
      $tgChannels->each(function (TelegramChannelSite $tgChannel) {
        $tgClient = new TelegramClient($tgChannel->tg_api_key, $tgChannel->tg_chat_id);

        $siteUrl = $tgChannel->site->url;
        $siteId = $tgChannel->site->id;
        $categoryUrl = $tgChannel->site->category_url;
        $urlWithGeneration = $tgChannel->site->generation_url;
        $categoryAssociation = $tgChannel->site->category_association;
        $filter = $tgChannel->filter;
        $filter[AttributeName::SITE_ID] = [
          'id' => $siteId,
          'onlyActive' => true
        ];

        $offer = Offer::query()
          ->filter($filter)
          ->selectForTelegramFeed()
          ->inRandomOrder()
          ->first()
          ->toArray();

        $offerFeedDto = (new OfferFeedDto($offer))->getOfferArrayForTelegramChannel($siteUrl, $categoryUrl, $urlWithGeneration, $categoryAssociation);

        $message = Blade::render($tgChannel->body, ['offer' => $offerFeedDto]);

        $media = collect($offerFeedDto['images']);

        $tgClient->telegramSendMediaGroup([
          'media' => $media
            ->map(fn($item, $key) => $key == 0 ? [
              'type' => 'photo',
              'media' => $item,
              'caption' => $message,
              'parse_mode' => 'HTML',
            ] : [
              'type' => 'photo',
              'media' => $item
            ])->values()->toJson()
        ]);
        $tgChannel->send_at = Carbon::now();
        $tgChannel->save();
      });
    }

  }
}
