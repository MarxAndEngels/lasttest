<?php

namespace App\Jobs;

use App\Constants\Enums\FeedbackEnum;
use App\Mail\FeedbackShippedEmail;
use App\Models\Feedback;
use App\Services\ApiClient\TelegramClient;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class PostFeedbackEmailJob implements ShouldQueue
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
    $feedbacks = Feedback::query()->whereNew()->whereSitePostToEmail()->with(['site', 'feedbackOffer'])->get();
    $feedbacks->each(function (Feedback $feedback) {
      try {
        $view = match ($feedback->type_enum) {
          FeedbackEnum::CREDIT => 'mail.credit',
          FeedbackEnum::CALLBACK => 'mail.callback',
          FeedbackEnum::BUYOUT => 'mail.buyout',
          FeedbackEnum::TRADE_IN => 'mail.trade-in',
          FeedbackEnum::HIRE_PURCHASE => 'mail.hire-purchase',
          FeedbackEnum::STATION => 'mail.station',
          FeedbackEnum::PAID_SELECTION => 'mail.paid_selection',
          FeedbackEnum::TEST_DRIVE => 'mail.test-drive',
          default => 'mail.default',
        };
        $subject = match ($feedback->type_enum) {
          FeedbackEnum::STATION => "Заявка на услугу детейлинга с сайта {$feedback->site->title}",
          FeedbackEnum::PAID_SELECTION => "Заявка на подбор автомобиля с сайта {$feedback->site->title}",
          default => "Заявка с сайта {$feedback->site->title}",
        };
        Mail::to($feedback->site->feedback_email ?: 'dev@igamov.ru')->send(new FeedbackShippedEmail($feedback, $view, $subject));
        $feedback->status_enum = FeedbackEnum::SUCCESS;
        $feedback->save();

      } catch (\Throwable $exception) {
//        #\Log::error($exception->getMessage());
        $feedback->status_enum = FeedbackEnum::ERROR;
        $feedback->save();
        $tgClient = new TelegramClient();
        $tgClient->telegramSendMessage(
          ['text' => "Не удалось отправить заявку с {$this->appName}. Заявка #{$feedback->id}. Сообщение об ошибке: {$exception->getMessage()}"]);
      }
    });
  }
}
