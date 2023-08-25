<?php
declare(strict_types=1);

namespace App\Dto\PlexCrm;

use App\Complex\Dto\Dto;
use App\Constants\Attributes\AttributeName;
use App\Constants\Enums\OfferEnum;
use App\Constants\Translate\FolderRussianConstants;
use App\Constants\Translate\MarkRussianConstants;
use App\Helpers\Modifiers;
use App\Models\BodyType;
use App\Models\Color;
use App\Models\Complectation;
use App\Models\Dealer;
use App\Models\DriveType;
use App\Models\EngineType;
use App\Models\Folder;
use App\Models\Gearbox;
use App\Models\Generation;
use App\Models\Mark;
use App\Models\Modification;
use App\Models\OfferCommercialType;
use App\Models\Owner;
use App\Models\Wheel;
use Illuminate\Support\Str;

class OfferExternalDto extends Dto
{

  public int $id;
  public string $uniqueId;
  public NameTitleDto $offerType;
  public NameTitleCyrillicDto $mark;
  public NameTitleCyrillicDto $model;
  public ?GenerationDto $generation;
  public ?string $modification;
  public ?string $complectation;
  public NameTitleDto $bodyType;
  public NameTitleDto $category;
  public ?NameTitleDto $type;
  public NameTitleDto $section;
  public int $dealerId;
  public ?string $dealerName;
  public ?string $dealerDescription;
  public int $enginePower;
  public $engineVolume;
  public NameTitleDto $engineType;
  public NameTitleDto $gearbox;
  public NameTitleDto $driveType;
  public ?NameTitleDto $color;
  public NameTitleDto $wheel;
  public NameTitleDto $owners;
  public ?NameTitleDto $state;
  public int $year;
  public int $run;
  public int $price;
  public ?int $priceOld;
  public ?string $vin;
  public array $images;
  public ?string $video;
  public ?string $description;
  public ?array $specifications;
  public ?array $equipmentGroups;
  public ?array $equipment;
  public ?string $createdAt;
  public ?string $updatedAt;
  public bool $isActive;


  private function getMarkId(): int
  {
    return
      Mark::firstOrCreate(
        [
          'slug' => Str::slug($this->mark->name)
        ],
        [
          'title' => $this->mark->title,
          'title_rus' => $this->mark->cyrillicTitle ?: MarkRussianConstants::MARK_TITLES[$this->mark->name] ?? $this->mark->title,
        ]
      )->id;
  }

  private function getFolderId(): int
  {
    return
      Folder::firstOrCreate(
        [
          AttributeName::MARK_ID => $this->getMarkId(),
          AttributeName::SLUG => Str::slug($this->model->name),
        ],
        [
          'title' => $this->model->title,
          'title_rus' => $this->model->cyrillicTitle ?: FolderRussianConstants::FOLDER_TITLES[$this->model->name] ?? $this->model->title,
        ]
      )->id;
  }

  private function getGenerationId(): ?int
  {
    if (!$this->generation) {
      return null;
    }
    $generationTitle = $this->generation->title ?? 'I';
    $yearBegin = $this->generation->yearBegin;
    $yearEnd = $this->generation->yearEnd ?: null;
    $slug = Str::slug("{$generationTitle} {$this->generation->yearBegin}");
    $yearEnd = $yearEnd ?: 'now';
    $slug .= "-{$yearEnd}";
    return Generation::firstOrCreate(
      [
        AttributeName::SLUG => $slug,
        AttributeName::FOLDER_ID => $this->getFolderId()
      ],
      [
        'name' => $generationTitle,
        'year_begin' => $yearBegin,
        'year_end' => $this->generation->yearEnd ?: null,
      ]
    )->id;
  }

  private function getModificationId(): ?int
  {
    if (!$this->modification) {
      return null;
    }
    return Modification::firstOrCreate(
      [
        'name' => $this->modification,
        AttributeName::GENERATION_ID => $this->getGenerationId(),
        AttributeName::BODY_TYPE_ID => $this->getBodyTypeId(),
      ]
    )->id;
  }

  private function getComplectationId(): ?int
  {
    if (!$this->complectation) {
      return null;
    }
    return Complectation::firstOrCreate(
      [
        'name' => $this->complectation,
        'modification_id' => $this->getModificationId(),
      ]
    )->id;
  }

  private function getBodyTypeId(): int
  {
    return BodyType::firstOrCreate(
      [
        'title' => $this->bodyType->title,
        'name' => Str::slug($this->bodyType->title)
      ]
    )->id;
  }

  private function getDriveTypeId(): int
  {
    return DriveType::firstOrCreate(
      [
        'title' => $this->driveType->title,
        'name' => $this->driveType->name
      ]
    )->id;
  }

  private function getEngineTypeId(): int
  {
    return EngineType::firstOrCreate(
      [
        'title' => $this->engineType->title,
        'name' => $this->engineType->name
      ]
    )->id;
  }

  private function getGearboxId(): int
  {
    return Gearbox::firstOrCreate(
      [
        'title' => $this->gearbox->title,
        'name' => $this->gearbox->name
      ]
    )->id;
  }

  private function getColorId(): ?int
  {
    if(!$this->color) {
      return null;
    }
    return Color::firstOrCreate(
      [
        'title' => $this->color->title,
        'name' => $this->color->name
      ]
    )->id;
  }

  private function getWheelId(): int
  {
    return Wheel::firstOrCreate(
      [
        'title' => $this->wheel->title,
        'name' => $this->wheel->name
      ]
    )->id;
  }

  private function getOwnerId(): int
  {
    $num = (int)$this->owners->title;
    return Owner::firstOrCreate(
      [
        'name' => $this->owners->name,
      ],
      [
        'title' => Modifiers::declension($num, 'владелец', 'владельца', 'владельцев', true),
        'number' => $num
      ]
    )->id;
  }



  private function getOfferCommercialTypeId(): ?int
  {
    if ($this->type) {
      return OfferCommercialType::firstOrCreate(
        [
          'title' => $this->type->title,
          'name' => $this->type->name
        ]
      )->id;
    }
    return null;
  }

  private function getDealerId(): int
  {
    return Dealer::firstOrCreate(
      [
        AttributeName::EXTERNAL_ID => $this->dealerId,
      ],
      [
        'title' => $this->dealerName,
        'slug' => Str::slug($this->dealerName),
        'description' => $this->dealerDescription,
      ]
    )->id;
  }

  private function getVin(): ?string
  {
    if ($this->vin) {
      try {
        $codedVin_top = mb_substr($this->vin, 0, 8);
        $codedVin_center = mb_substr($this->vin, 9, 2);
        $codedVin_last = mb_substr($this->vin, 15);
        return $codedVin_top . '*' . $codedVin_center . '****' . $codedVin_last;
      }catch (\Exception $exception) {
        return null;
      }
    }
    return null;
  }
  private function getEquipment():string
  {
    return collect($this->equipment)->map(function ($item, $key){
      return
        [
          'key' => $key,
          'value' => $item['value']
        ];
    })->values()->toJson(JSON_UNESCAPED_UNICODE);
  }
  private function getEquipmentGroup(): string
  {
    $equipmentGroups = collect($this->equipmentGroups)->filter(fn($item) => $item['values'])->all();
    return collect($equipmentGroups)->map(function ($item) {
      return
        [
          'title' => $item['title'] == 'Default' ? 'Общие' : $item['title'],
          'values' => collect($item['values'])->map(function ($t, $k){
            return $t['value'];
          })->values()->all()
        ];
    })->values()->toJson(JSON_UNESCAPED_UNICODE);
  }
  public function getCategoryEnum(): string
  {
    return
      match ($this->dealerId) {
        7 => OfferEnum::COMMERCIAL, #комм авто
        129 => OfferEnum::EUROPE, #prime премиум
        default => Str::upper($this->category->name)
    };
  }
  private function getVideo(): ?string
  {
    if (!$this->video){
      return null;
    }
    $youtubeId = '';
    $shortUrlRegex = '/youtu.be\/([a-zA-Z0-9_-]+)\??/i';
    $longUrlRegex = '/youtube.com\/((?:embed)|(?:watch))((?:\?v\=)|(?:\/))([a-zA-Z0-9_-]+)/i';

    if (preg_match($longUrlRegex, $this->video, $matches)) {
      $youtubeId = $matches[count($matches) - 1];
    }

    if (preg_match($shortUrlRegex, $this->video, $matches)) {
      $youtubeId = $matches[count($matches) - 1];
    }
    return $youtubeId ? "https://www.youtube.com/embed/{$youtubeId}" : null;
  }
  public function getExternalId(): int
  {
    return $this->id;
  }
  public function getOffer(): array
  {
    return [
      'external_id' => $this->id,
      'external_unique_id' => $this->uniqueId,
      'name' => "{$this->mark->title} {$this->model->title}, {$this->year} г.",
      "category_enum" => $this->getCategoryEnum(),
      "section_enum" => Str::upper($this->section->name),
      "state_enum" => $this->state ? Str::upper($this->state->name) : '',
      'vin' => $this->getVin(),
      'video' => $this->getVideo(),
      'year' => $this->year,
      'engine_power' => $this->enginePower,
      'engine_volume' => (float)$this->engineVolume,
      'run' => $this->run,
      'images' => json_encode($this->images, JSON_UNESCAPED_UNICODE),
      'equipment' => $this->getEquipment(),
      'equipment_groups' => $this->getEquipmentGroup(),
      'specifications' => json_encode($this->specifications, JSON_UNESCAPED_UNICODE),
      AttributeName::TYPE_ENUM => $this->offerType->title,
      AttributeName::OFFER_COMMERCIAL_TYPE_ID => $this->getOfferCommercialTypeId(),
      AttributeName::MARK_ID => $this->getMarkId(),
      AttributeName::FOLDER_ID => $this->getFolderId(),
      AttributeName::GENERATION_ID => $this->getGenerationId(),
      AttributeName::MODIFICATION_ID => $this->getModificationId(),
      AttributeName::COMPLECTATION_ID => $this->getComplectationId(),
      AttributeName::GEARBOX_ID => $this->getGearboxId(),
      AttributeName::DRIVE_TYPE_ID => $this->getDriveTypeId(),
      AttributeName::ENGINE_TYPE_ID => $this->getEngineTypeId(),
      AttributeName::BODY_TYPE_ID => $this->getBodyTypeId(),
      AttributeName::COLOR_ID => $this->getColorId(),
      AttributeName::WHEEL_ID => $this->getWheelId(),
      AttributeName::OWNER_ID => $this->getOwnerId(),
      AttributeName::DEALER_ID => $this->getDealerId(),
    ];
  }
}
