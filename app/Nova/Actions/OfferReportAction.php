<?php
declare(strict_types=1);

namespace App\Nova\Actions;

use App\Constants\Attributes\AttributeName;
use App\Dto\Feeds\OfferFeedDto;
use App\Models\Offer;
use App\Models\User;
use App\QueryBuilders\FeedbackQueryBuilder;
use Carbon\Carbon;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Notifications\NovaNotification;
use Laravel\Nova\URL;

class OfferReportAction extends Action implements ShouldQueue
{
  use Batchable, InteractsWithQueue, Queueable;

  public $name = "Export offers CSV";
  public User $user;

  public int $timeout = 3600;

  public function __construct(?User $user)
  {
    $this->user = $user ?? auth()->user();
  }


  public function handle(ActionFields $fields, Collection $models): void
  {
    $offerQuery = Offer::query();
    $csvFields = ['ID', 'Домен', 'Автосалон', 'Марка', 'Модель', 'Объем двигателя', 'Мощность двигателя', 'КПП', 'Год', 'Цена', 'Количество заявок', 'Опубликован', 'Тип', 'Ссылка'];
    $dateFrom = Carbon::create($fields->date_from)->startOfDay();
    $dateTo = Carbon::create($fields->date_to)->endOfDay();
    $site = $models->first();
    $filter[AttributeName::SITE_ID] = [
      'id' => $site->id,
      'onlyActive' => false
    ];

    $sitesIdArray = $site->childrenSites->map(fn($childrenSite) => $childrenSite->id);
    $sitesIdArray[] = $site->id;
    $fileName = "export-offers-{$site->title}-from-{$dateFrom->toDateString()}-to-{$dateTo->toDateString()}.csv";
    $file = Storage::path("public/tmp_files/{$fileName}");
    try {
      $fd = fopen($file, 'w+');
    }catch (\Exception $exception){
      $this->markAsFailed($site, $exception->getMessage());
    }
    fputs($fd, chr(0xEF) . chr(0xBB) . chr(0xBF)); // BOM
    fputcsv($fd, $csvFields, ';');
    $offerQuery
      ->selectForOfferReport()
      ->with('dealer:id,slug,title', 'generation:id,slug')
      ->filter($filter)
      ->withCount(['feedbackOffers AS count' => fn(Builder $builder) => $builder
        ->whereHas('feedback', fn(FeedbackQueryBuilder $feedbackQueryBuilder) => $feedbackQueryBuilder
          ->whereIn($feedbackQueryBuilder->qualifyColumn(AttributeName::SITE_ID), $sitesIdArray)
          ->when($dateFrom && $dateTo, fn(FeedbackQueryBuilder $feedbackQueryBuilder) =>
          $feedbackQueryBuilder->whereBetween($feedbackQueryBuilder->qualifyColumn(AttributeName::CREATED_AT), [$dateFrom, $dateTo]))
        )
      ])
      ->with(['feedbackOffers.feedback' => fn($query) => $query
        ->when($dateFrom && $dateTo, fn(FeedbackQueryBuilder $feedbackQueryBuilder) =>
                    $feedbackQueryBuilder->whereBetween($feedbackQueryBuilder->qualifyColumn(AttributeName::CREATED_AT), [$dateFrom, $dateTo]))
        ->whereIn($query->qualifyColumn(AttributeName::SITE_ID), $sitesIdArray)
      ])
      ->chunk(1000, fn($offers) => $offers->each(fn(Offer $offer) =>
      fputcsv($fd, (new OfferFeedDto($offer->toArray()))->getOfferArrayForReportCsv($site->url, $site->category_url, $site->generation_url, $site->category_association), ';')
      ));
    $this->user->notify(
      NovaNotification::make()
        ->message("Отчет готов для сайта {$site->title}")
        ->action('Скачать', URL::remote(url("/tmp_files/{$fileName}")))
        ->icon('download')
        ->type('info')
    );
    $this->markAsFinished($site);

  }

  public function fields(NovaRequest $request): array
  {
    return [
      Date::make(__('From'), 'date_from')->default(fn() => Carbon::now()->subMonth())->required(),
      Date::make(__('To'), 'date_to')->default(fn() => Carbon::now())->required(),
    ];
  }
}
