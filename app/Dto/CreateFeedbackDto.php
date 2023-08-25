<?php
declare(strict_types=1);

namespace App\Dto;
use App\Constants\Attributes\AttributeName;
use App\Models\Offer;
use App\Models\Site;
use Illuminate\Support\Str;
use Spatie\DataTransferObject\DataTransferObject;

class CreateFeedbackDto extends DataTransferObject
{
  public ?int $external_id;
  public ?string $external_unique_id;
  public int $site_id;
  public ?int $client_region_id;
  public string $type;
  public string $client_session;
  public ?string $client_user_agent;
  public ?string $client_name;
  public string $client_phone;
  public ?string $client_age;
  public ?string $client_ip;
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

  protected function getFeedbackTypeEnum(): string
  {
    return (string)Str::upper($this->type);
  }
  public function getOffer(): ?array
  {
    if(!$this->external_id){
      return null;
    }
    $offerQuery = Offer::query();
    $siteId = $this->site_id;
    $siteQuery = Site::query();
    $siteModel = $siteQuery->getParentId($siteId)->first();
    $offer = $offerQuery->whereExternalId($this->external_id)->withPriceForSite($siteModel->id)->first();
    if($offer) {
      return
        [
          AttributeName::OFFER_TITLE => $offer->name,
          AttributeName::EXTERNAL_ID => $offer->external_id,
          AttributeName::EXTERNAL_UNIQUE_ID => $offer->external_unique_id,
          AttributeName::DEALER_ID => $offer->dealer_id,
          'year' => $offer->year,
          'engine_power' => $offer->engine_power,
          'run' => $offer->run,
          'engine_volume' => $offer->engine_volume,
          'price' => $offer->price,
          'price_old' => $offer->price_old,
          AttributeName::MARK_ID => $offer->mark_id,
          AttributeName::FOLDER_ID => $offer->folder_id,
          AttributeName::GENERATION_ID => $offer->generation_id,
          AttributeName::MODIFICATION_ID => $offer->modification_id,
          AttributeName::GEARBOX_ID => $offer->gearbox_id
        ];
    }else{
      return null;
    }
  }
  public function getFeedback(): array
  {
    return [
      AttributeName::CLIENT_IP => $this->client_ip,
      AttributeName::CLIENT_USER_AGENT => $this->client_user_agent ?: 'empty',
      AttributeName::CLIENT_SESSION => $this->client_session,
      AttributeName::CLIENT_NAME => $this->client_name,
      AttributeName::CLIENT_PHONE => $this->client_phone,
      AttributeName::CLIENT_AGE => $this->client_age,
      AttributeName::CLIENT_REGION => $this->client_region,
      AttributeName::CLIENT_VEHICLE_MARK => $this->client_vehicle_mark,
      AttributeName::CLIENT_VEHICLE_MODEL => $this->client_vehicle_model,
      AttributeName::CLIENT_VEHICLE_RUN => $this->client_vehicle_run,
      AttributeName::CLIENT_VEHICLE_YEAR => $this->client_vehicle_year,
      AttributeName::CLIENT_VEHICLE_BODY_TYPE => $this->client_vehicle_body_type,
      AttributeName::CLIENT_VEHICLE_PRICE => $this->client_vehicle_price,
      AttributeName::CLIENT_VEHICLE_OWNERS => $this->client_vehicle_owners,
      AttributeName::CLIENT_VEHICLE_GEARBOX => $this->client_vehicle_gearbox,
      AttributeName::CLIENT_VEHICLE_ENGINE => $this->client_vehicle_engine,
      AttributeName::CREDIT_INITIAL_FEE=> $this->credit_initial_fee,
      AttributeName::CREDIT_PERIOD => $this->credit_period,
      AttributeName::UTM_SOURCE => $this->utm_source,
      AttributeName::UTM_MEDIUM => $this->utm_medium,
      AttributeName::UTM_CAMPAIGN=> $this->utm_campaign,
      AttributeName::UTM_CONTENT => $this->utm_content,
      AttributeName::UTM_TERM => $this->utm_term,
      AttributeName::OFFER_TITLE => $this->offer_title,
      AttributeName::OFFER_PRICE => $this->offer_price,
      AttributeName::COMMENT => $this->comment,
      AttributeName::TYPE_ENUM => $this->getFeedbackTypeEnum(),
      AttributeName::SITE_ID => $this->site_id,
      AttributeName::CLIENT_REGION_ID => $this->client_region_id
    ];
  }




}
