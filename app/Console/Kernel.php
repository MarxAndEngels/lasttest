<?php

namespace App\Console;

use App\Jobs\CheckDomainStatusJob;
use App\Jobs\CreateFeedJob;
use App\Jobs\GetCommunicationsPlexCrmJob;
use App\Jobs\GetOffersPlexCrmJob;
use App\Jobs\PostFeedbackEmailJob;
use App\Jobs\PostFeedbackPlexCrmJob;
use App\Jobs\PostOffersLinkPlexCrmJob;
use App\Jobs\PostOfferTelegramChannelJob;
use App\Jobs\TestJob;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Config;

class Kernel extends ConsoleKernel
{
  protected function schedule(Schedule $schedule): void
  {
    if (Config::get('app.env') == 'local'){
      return;
    }
    $schedule->command('horizon:snapshot')->everyFiveMinutes();
    // PLEX
    $schedule->job(GetOffersPlexCrmJob::class)->everyThirtyMinutes()->withoutOverlapping(60);
    $schedule->job(PostOffersLinkPlexCrmJob::class)->everyThirtyMinutes()->withoutOverlapping(60);
    $schedule->job(PostFeedbackPlexCrmJob::class)->everyMinute()->withoutOverlapping(10);
    $schedule->job(GetCommunicationsPlexCrmJob::class)->weekly()->withoutOverlapping();

    // EMAIL
    $schedule->job(PostFeedbackEmailJob::class)->everyFiveMinutes()->withoutOverlapping(10);

    // FEED
    $schedule->job(CreateFeedJob::class)->dailyAt("01:00")->withoutOverlapping(120);

    // Telegram Channel

    $schedule->job(PostOfferTelegramChannelJob::class)->cron('30 */2 * * *');


    // Domains

    $schedule->job(CheckDomainStatusJob::class)->dailyAt("03:30")->withoutOverlapping();


//    $schedule->job(TestJob::class)->everyMinute()->withoutOverlapping();
  }

  protected function commands(): void
  {
    $this->load(__DIR__ . '/Commands');

    require base_path('routes/console.php');
  }
}
