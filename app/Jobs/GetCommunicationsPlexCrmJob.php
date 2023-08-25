<?php
declare(strict_types=1);

namespace App\Jobs;

use App\Constants\Attributes\AttributeName;
use App\Dto\PlexCrm\CommunicationExternalCollectionDto;
use App\Dto\PlexCrm\CommunicationExternalDto;
use App\Dto\PlexCrm\PaginationDto;
use App\Models\Offer;
use App\Models\Site;
use App\Services\ApiClient\PlexCrmClient;
use App\Services\FileLog\FileLog;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class GetCommunicationsPlexCrmJob implements ShouldQueue
{
  use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

  public FileLog $fileLog;
  public function __construct()
  {
    $this->fileLog = new FileLog('communications_log.log');
  }
  public function handle(): void
  {
    $siteQuery = Site::query();
    $sites = $siteQuery->whereParentNull()->whereEnabled()->whereGetCommunications()->get();

    $sites->each(function ($site){
      $this->fileLog->log('', true);
      $this->fileLog->log('____');
      $this->fileLog->log("Get communications from site {$site->title}");
      $this->paginate($site);
      $this->fileLog->log("complete");
    });
  }

  protected function paginate(Site $site): void
  {
    $plexCrmClient = new PlexCrmClient();
    $currentPage = 1;
    $hasMore = true;
    #пол года назад
    $datetimeFrom = Carbon::now()->subMonths(6)->toRfc3339String();
    $filter = [
      'datetime_from' => $datetimeFrom,
      'page' => $currentPage,
      'all' => 1,
      'limit' => 100
    ];
    while ($hasMore) {
      $filter['page'] = $currentPage;
      $response = $plexCrmClient->getCommunications($filter, $site->external_id);
      if (!$response->successful()){
        $this->fileLog->log('!successful communications');
        $currentPage++;
        continue;
      }
      $communicationsExternalArray = $response->json();
      $paginationDto = new PaginationDto($communicationsExternalArray['pagination']);
      $this->fileLog->log("page: {$paginationDto->currentPage}");
      $this->fileLog->log(collect($communicationsExternalArray['items'])->pluck('id')->implode(', '));
      $this->updateCommunications($communicationsExternalArray['items']);
      $currentPage++;
      $hasMore = $paginationDto->hasMore;
    }
  }

  protected function updateCommunications(array $communicationItems): void
  {
    $communicationExternalCollectionDto = CommunicationExternalCollectionDto::create($communicationItems);
    $communicationExternalCollectionDtoCollect = collect($communicationExternalCollectionDto);
    $communicationExternalCollectionDtoCollect->each(function(CommunicationExternalDto $communicationExternalDto){
      $offer = Offer::query()->where(AttributeName::EXTERNAL_ID, '=', $communicationExternalDto->id)->first();
      if (!$offer){
        return;
      }
      $offer->update([
        AttributeName::COMMUNICATIONS_COUNT => $communicationExternalDto->communications_count,
        AttributeName::CONTACT_FORM_APPLICATIONS_COUNT => $communicationExternalDto->contact_form_applications_count,
        AttributeName::PHONE_CALLS_COUNT => $communicationExternalDto->phone_calls_count
      ]);
    });

  }
}
