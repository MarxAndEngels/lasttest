<?php
declare(strict_types=1);

namespace App\Http\Requests;

use App\Dto\Request\AutotekaRequestDto;
use Illuminate\Foundation\Http\FormRequest;
use JetBrains\PhpStorm\ArrayShape;

class AutotekaRequest extends FormRequest
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
  #[ArrayShape(['offer_external_id' => "string[]"])]
  public function rules() : array
  {
    return [
      'offer_external_id' => ['required', 'integer']
    ];
  }
  public function toDto(): AutotekaRequestDto
  {
    return new AutotekaRequestDto([
      'offer_external_id' => (int)$this->get('offer_external_id')
    ]);
  }
}
