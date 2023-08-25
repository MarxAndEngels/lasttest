<?php

declare(strict_types=1);

namespace App\Dto\MegaCrm;

use App\Complex\Dto\Dto;
use App\Complex\Support\Arr;
use App\Constants\Enums\FeedbackEnum;
use Carbon\Carbon;
use Carbon\CarbonInterface;
use DateTimeInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class FeedbackDto extends Dto
{
  public int $id;
  public ?string $client_ip;
  public string $client_session;

  public ?string $client_user_agent;

  public ?string $client_name;
  public string $client_phone;
  public ?string $client_age;

  public ?string $client_region;

  public ?string $client_vehicle_mark;
  public ?string $client_vehicle_model;
  public ?string $client_vehicle_run;
  public ?string $client_vehicle_year;
  public ?string $client_vehicle_body_type;
  public ?string $client_vehicle_price;
  public ?string $client_vehicle_owners;
  public ?string $client_vehicle_gearbox;
  public ?string $client_vehicle_engine;
  public ?string $credit_initial_fee;
  public ?string $credit_period;
  public ?string $utm_source;
  public ?string $utm_medium;
  public ?string $utm_campaign;
  public ?string $utm_content;
  public ?string $utm_term;
  public ?string $offer_title;
  public ?string $offer_price;
  public ?string $comment;

  public int $site_id;
  public string $status_enum;
  public string $type_enum;
  public string $created_at;

  public ?FeedbackOfferDto $feedback_offer;

  private function getFullNameArray(): ?array
  {
    if (!$this->client_name){
      return null;
    }
    return explode(' ', $this->client_name);
  }
  private function getAge(): ?int
  {
    if (!$this->client_age){
      return null;
    }
    try {
      return Carbon::createFromFormat('d/m/Y', $this->client_age)?->age;
    }catch (\Exception $exception){
      report($exception->getMessage());

    }
    return null;
  }

  public function getFeedback():array
  {
    $fullName = $this->getFullNameArray();
    $feedbackCollection = Collection::make([
      'mobile_tel' => preg_replace('/[^0-9]/', '', Str::replaceFirst('+7', '', $this->client_phone)),
      'last_name' => $fullName[0] ?? '',
      'first_name' => $fullName[1] ?? '',
      'middle_name' => $fullName[2] ?? '',
      'created' => Carbon::parse($this->created_at)->setTimezone('Europe/Moscow')->toDateTimeString(),
      'age' => $this->getAge(),
      'request_type' => $this->type_enum,
      'ip_address' => $this->client_ip,
      'utm_source' => $this->utm_source,
      'utm_medium' => $this->utm_medium,
      'utm_content' => $this->utm_content,
      'utm_campaign' => $this->utm_campaign,
      'inital_pay' => $this->credit_initial_fee,
      'credit_term' => (int)$this->credit_period,
      'brand' => $this->feedback_offer?->mark->title ?: $this->client_vehicle_mark,
      'model' => $this->feedback_offer?->folder->title ?: $this->client_vehicle_model,
      'car_cost' => $this->feedback_offer?->price ?: $this->client_vehicle_price,
      'model_year' => $this->feedback_offer?->year ?: $this->client_vehicle_year,
      'mileage' => $this->feedback_offer?->run ?: $this->client_vehicle_run,
    ]);

    return $feedbackCollection->filter(fn($v) => !is_null($v))->toArray();
  }

}
