<?php

declare(strict_types=1);

namespace App\QueryBuilders;

use App\Constants\Attributes\AttributeName;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

final class SiteQueryBuilder extends Builder
{
  public function selectForSeoTag(): self
  {
    return $this->select(AttributeName::CATEGORY_ASSOCIATION, AttributeName::ROUTE_PAGES, AttributeName::GENERATION_URL, AttributeName::BANK_PAGES, AttributeName::DEALER_PAGES);
  }
  public function selectForOfferPrice(): self
  {
    return $this->select(AttributeName::ID, AttributeName::PARENT_SITE_ID, AttributeName::FILTER);
  }

  public function selectForFields(): self
  {
    return $this->select(
      AttributeName::ID, AttributeName::PARENT_SITE_ID, AttributeName::FILTER, AttributeName::GENERATION_URL, AttributeName::CATEGORY_ASSOCIATION,
      AttributeName::ROUTE_PAGES, AttributeName::BANK_PAGES, AttributeName::DEALER_PAGES);
  }
  public function whereParentNull(): self
  {
    return $this->whereNull(AttributeName::PARENT_SITE_ID);
  }

  public function whereEnabled(): self
  {
    return $this->where(AttributeName::IS_DISABLED, 0);
  }

  public function whereSlug(string $slug): self
  {
    return $this->where(AttributeName::SLUG, '=', $slug);
  }

  public function wherePostLinkCrm(): self
  {
    return $this->where(AttributeName::POST_LINK_CRM, '=', 1);
  }
  public function wherePostFeedbackPlexCrm(): self
  {
    return $this->where(AttributeName::POST_FEEDBACK_PLEX_CRM, '=', 1);
  }

  public function whereGetCommunications(): self
  {
    return $this->where(AttributeName::GET_COMMUNICATIONS, '=', 1);
  }
  public function wherePostFeedbackEmail(): self
  {
    return $this->where(AttributeName::POST_FEEDBACK_EMAIL, '=', 1)
                ->whereNotNull(AttributeName::FEEDBACK_EMAIL);
  }
  public function whereParentSiteId(int $parentSiteId): self
  {
    return $this->where(AttributeName::PARENT_SITE_ID, '=', $parentSiteId);
  }
  public function getParentId(int $siteId): self
  {
    return
      $this->where(AttributeName::ID, '=', $siteId)
        ->addSelect(DB::raw('COALESCE (' . AttributeName::PARENT_SITE_ID . ',' . AttributeName::ID . ') as ' . AttributeName::ID));
  }

  public function whereId(int $siteId): self
  {
    return $this->where($this->qualifyColumn(AttributeName::ID), '=', $siteId);
  }
}
