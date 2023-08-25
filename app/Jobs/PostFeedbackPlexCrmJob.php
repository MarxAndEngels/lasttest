<?php

namespace App\Jobs;

use App\Constants\Attributes\AttributeName;
use App\Constants\Enums\FeedbackEnum;
use App\Dto\FeedbackDto;
use App\Models\Feedback;
use App\Services\ApiClient\PlexCrmClient;
use App\Services\ApiClient\TelegramClient;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class PostFeedbackPlexCrmJob implements ShouldQueue
{
  use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

  public string $appName;
  /**
   * Create a new job instance.
   *
   * @return void
   */
  public function __construct()
  {
    $this->appName = config('app.name');
  }

  /**
   * Execute the job.
   *
   * @return void
   */
  public function handle(): void
  {

    $plexCrmClient = new PlexCrmClient();
    $feedbacks = Feedback::query()
                          ->whereNew()
//                          ->whereTypeSendToPlexCrm()
                          ->whereSitePostToPlexCrm()
                          ->with(['feedbackOffer.dealer', 'site.dealer'])
                          ->get();

    $feedbacks->each(function ($feedback) use ($plexCrmClient) {
      try {
        $feedbackDto = new FeedbackDto($feedback->toArray());
        if($feedbackDto->type_enum == FeedbackEnum::CALLBACK && $feedbackDto->site_id == 56 && !$feedbackDto->feedback_offer){
          $feedback->comment = 'Возможно спам';
          $feedback->status_enum = FeedbackEnum::SUCCESS;
          $feedback->save();
          return;
        }
        $feedbackData = $feedbackDto->createArrayForPlexCrm();
      } catch (\Throwable $exception) {
        #\Log::error($exception->getMessage());
        $feedback->status_enum = FeedbackEnum::ERROR;
        $feedback->save();
        $tgClient = new TelegramClient();

        $tgClient->telegramSendMessage(
          ['text' => "Не удалось отправить заявку с {$this->appName}. Заявка #{$feedback->id}. SITE-ID {$feedback->site_id} Сообщение об ошибке: {$exception->getMessage()}"]);
        return;
      }
      if ($feedbackExternalId = $plexCrmClient->sendFeedback($feedbackData)) {
        $feedback->external_id = $feedbackExternalId;
        $feedback->status_enum = FeedbackEnum::SUCCESS;
        $feedback->save();
      } else {
        $feedback->status_enum = FeedbackEnum::ERROR;
        $feedback->save();
        $tgClient = new TelegramClient();
        $tgClient->telegramSendMessage(
          ['text' => "Не удалось отправить заявку {$feedbackDto->type_enum} с {$this->appName}. Заявка #{$feedbackDto->id}. Сайт {$feedbackDto->site->title}"]);
      }
    });
  }
}
