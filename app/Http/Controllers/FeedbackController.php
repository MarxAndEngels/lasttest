<?php
declare(strict_types=1);
namespace App\Http\Controllers;


use App\Constants\Attributes\AttributeName;
use App\Constants\Enums\FeedbackEnum;
use App\Dto\MegaCrm\FeedbackDto;
use App\Http\Requests\FeedbackMegaCrmRequest;
use App\Models\Feedback;
use App\Models\FeedbackMegaCrm;
use App\QueryBuilders\SiteQueryBuilder;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;

class FeedbackController extends Controller
{
//  http://localhost:8080/feedbacks/mega-crm/list/test?token=test&last_request_date=2021-03-03 01:00:00

  public function exportFeedbacksMegaCrm(FeedbackMegaCrmRequest $request): string
  {
    $requestDto = $request->toDto();
    $feedbackMegaCrm = FeedbackMegaCrm::query()
      ->where(AttributeName::TOKEN, '=', $requestDto->token)
      ->whereHas('site', fn(SiteQueryBuilder $query) => $query->whereSlug($requestDto->siteSlug))
      ->with('site')
      ->firstOrFail();


    $feedbackCollection = Collection::make();
    $feedbacks = Feedback::query()
      ->whereNew()
//      ->whereTypeSendToMegaCrm()
      ->whereSiteSlug($requestDto->siteSlug)
      ->whereDateTimeFrom($requestDto->last_request_date)
      ->with(['feedbackOffer.mark', 'feedbackOffer.folder'])
      ->orderByDesc((new Feedback)->getQualifiedCreatedAtColumn())
      ->get();
    $feedbacks->each(function (Feedback $feedback) use ($feedbackCollection){
      $feedbackDto = new FeedbackDto($feedback->toArray());
      $feedbackCollection->push($feedbackDto->getFeedback());
//      $feedback->type_enum = FeedbackEnum::SUCCESS;
//      $feedback->save();
    });

    $feedbackMegaCrm->last_request_at = $requestDto->last_request_date;
    $feedbackMegaCrm->download_at = Carbon::now();
    $feedbackMegaCrm->save();

    return $feedbackCollection->toJson(JSON_UNESCAPED_UNICODE);


  }
}
