<?php

declare(strict_types=1);

namespace App\QueryBuilders;

use App\Constants\Attributes\AttributeName;
use App\Constants\Enums\FeedbackEnum;
use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Builder;

final class FeedbackQueryBuilder extends Builder
{

  public function whereNew(): self
  {
    return $this->where(AttributeName::STATUS_ENUM, '=', FeedbackEnum::NEW);
  }
  public function whereTypeSendToPlexCrm(): self
  {
    return $this->whereIn(AttributeName::TYPE_ENUM, FeedbackEnum::TYPE_ENUM_TO_PLEX_CRM);
  }

  public function whereTypeSendToMegaCrm(): self
  {
    return $this->whereIn(AttributeName::TYPE_ENUM, FeedbackEnum::TYPE_ENUM_TO_MEGA_CRM);
  }
  public function whereSendToEmail(): self
  {
    return $this->whereIn(AttributeName::TYPE_ENUM, FeedbackEnum::TYPE_ENUM_TO_EMAIL);
  }
  public function whereSitePostToPlexCrm(): self
  {
    return $this->whereHas('site', fn(SiteQueryBuilder $builder) => $builder->wherePostFeedbackPlexCrm());
  }
  public function whereSiteSlug(string $siteSlug): self
  {
    return $this->whereHas('site', fn(SiteQueryBuilder $builder) => $builder->whereSlug($siteSlug));
  }
  public function whereSitePostToEmail(): self
  {
    return $this->whereHas('site', fn(SiteQueryBuilder $builder) => $builder->wherePostFeedbackEmail());
  }

  public function whereDateTimeFrom(CarbonInterface $dateTime): self
  {
    return $this->where(AttributeName::CREATED_AT, '>', $dateTime);
  }
}
