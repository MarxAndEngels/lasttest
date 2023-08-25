<?php

declare(strict_types=1);

namespace App\Dto;

use App\Complex\Dto\Dto;
use App\Dto\PlexCrm\NameTitleDto;
use Illuminate\Support\Str;

class FeedbackDto extends Dto
{
  public int $id;
  public int $site_id;
  public string $client_ip;
  public string $client_session;
  public string $client_user_agent;
  public string $client_phone;
  public ?string $client_name;
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
  public ?FeedbackOfferDto $feedback_offer;
  public SiteDto $site;
  public string $type_enum;
  public string $status_enum;

  protected function getOfferTitle(): ?string
  {
    return $this->offer_title ?: $this->feedback_offer?->offer_title;
  }
  public function createArrayForPlexCrm(): array
  {
    $data = [
      'client_ip' => $this->client_ip,
      'client_name' => $this->client_name,
      'client_phone' => $this->client_phone,
      'type' => Str::lower($this->type_enum),
      'source.dealerId' => ($this->feedback_offer) ? $this->feedback_offer->dealer->external_id : $this->site->dealer->external_id,
      'source.websiteId' => $this->site->external_id,
      'client.ip' => $this->client_ip,
      'client.session' => $this->client_session,
      'client.userAgent' => $this->client_user_agent,
      'values.offerId' => $this->feedback_offer?->external_id,
      'values.offerTitle' => $this->getOfferTitle(),
      'values.offerPrice' => $this->offer_price,
      'values.clientName' => $this->client_name,
      'values.clientPhone' => $this->client_phone,
      'values.utmSource' => $this->utm_source,
      'values.utmMedium' => $this->utm_medium,
      'values.utmCampaign' => $this->utm_campaign,
      'values.utmContent' => $this->utm_content,
      'values.utmTerm' => $this->utm_term,
      'values.clientAge' => $this->client_age,
      'values.clientRegion' => $this->client_region,
      'values.creditInitialFee' => $this->credit_initial_fee,
      'values.creditPeriod' => $this->credit_period,
      'values.clientVehicleMark' => $this->client_vehicle_mark,
      'values.clientVehicleModel' => $this->client_vehicle_model,
      'values.clientVehicleYear' => $this->client_vehicle_year,
      'values.clientVehicleRun' => $this->client_vehicle_run,
      'values.clientVehicleBodyType' => $this->client_vehicle_body_type,
      'values.clientVehiclePrice' => $this->client_vehicle_price,
      'values.clientVehicleOwners' => $this->client_vehicle_owners,
      'values.clientVehicleEngineVolume' => $this->client_vehicle_engine,
      'values.clientVehicleGearbox' => $this->client_vehicle_gearbox,
      ];
    $filterData = collect($data)->filter(fn($item) => !empty($item))->toArray();
    return \Arr::undot($filterData);
  }
}
