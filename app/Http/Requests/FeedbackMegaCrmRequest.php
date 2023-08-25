<?php
declare(strict_types=1);

namespace App\Http\Requests;

use App\Dto\Request\FeedbackRequestMegaCrmDto;
use Carbon\Carbon;
use Carbon\CarbonInterface;
use Illuminate\Foundation\Http\FormRequest;

class FeedbackMegaCrmRequest extends FormRequest
{

  public function authorize(): bool
  {
    return true;
  }

  /**
   * Get the validation rules that apply to the request.
   *
   * @return array<string, mixed>
   */
  public function rules(): array
  {
    return [
      'token' => ['required', 'string'],
      'last_request_date' => ['required', 'date', 'date_format:Y-m-d H:i:s']
    ];
  }

  public function toDto(): FeedbackRequestMegaCrmDto
  {
    return new FeedbackRequestMegaCrmDto([
      'token' => $this->get('token'),
      'last_request_date' => Carbon::createFromFormat(CarbonInterface::DEFAULT_TO_STRING_FORMAT, $this->get('last_request_date')),
      'siteSlug' => strip_tags($this->route('site_slug')),
    ]);
  }
}
