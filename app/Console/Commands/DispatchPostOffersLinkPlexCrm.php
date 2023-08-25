<?php
declare(strict_types=1);

namespace App\Console\Commands;

use App\Jobs\PostOffersLinkPlexCrmJob;
use Illuminate\Console\Command;

class DispatchPostOffersLinkPlexCrm extends Command
{
  protected $signature = 'job:postOffersLink';

  protected $description = 'Dispatch job';

  public function handle(): int
  {
    $siteId = $this->ask('Put site id');
    $allOffers = $this->confirm('Send link to all offers?');
    try {
      PostOffersLinkPlexCrmJob::dispatch($siteId ? (int)$siteId : null, $allOffers);
      return parent::SUCCESS;
    }catch (\Exception $exception){
      return parent::FAILURE;
    }
  }
}
