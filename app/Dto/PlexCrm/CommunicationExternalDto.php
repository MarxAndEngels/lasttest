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

class CommunicationExternalDto extends Dto
{
  public int $communications_count;
  public int $contact_form_applications_count;
  public int $phone_calls_count;
  public int $id;

}
