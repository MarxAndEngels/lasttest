<?php

namespace App\Dto\Feeds;

use Spatie\DataTransferObject\DataTransferObjectCollection;

class OfferFeedCollectionDto extends DataTransferObjectCollection
{
  public function current(): OfferFeedDto
  {
    return parent::current();
  }

  public static function create(array $data): self
  {
    return new static(OfferFeedDto::arrayOf($data));
  }

  public static function getOffersArrayForYandexFeedXml(array $data, string $siteUrl, string $categoryUrl, bool $urlWithGeneration, array $categoryAssociation):array
  {
    $OfferFeedDto = new static(OfferFeedDto::arrayOf($data));
    return collect($OfferFeedDto)->map(fn(OfferFeedDto $dto) => $dto->getOfferArrayForYandexFeedXml($siteUrl, $categoryUrl, $urlWithGeneration, $categoryAssociation))->all();
  }
  public static function getOffersArrayForGoogleXmlFeed(array $data, string $siteUrl, string $categoryUrl, bool $urlWithGeneration, array $categoryAssociation):array
  {
    $OfferFeedDto = new static(OfferFeedDto::arrayOf($data));
    return collect($OfferFeedDto)->map(fn(OfferFeedDto $dto) => $dto->getOfferArrayForGoogleFeedXml($siteUrl, $categoryUrl, $urlWithGeneration, $categoryAssociation))->all();
  }
  public static function getOffersArrayForYandexYmlCatalog(array $data, string $siteUrl, string $categoryUrl, bool $urlWithGeneration, array $categoryAssociation):array
  {
    $OfferFeedDto = new static(OfferFeedDto::arrayOf($data));
    return collect($OfferFeedDto)->map(fn(OfferFeedDto $dto) => $dto->getOfferArrayForYandexFeedYmlCatalog($siteUrl, $categoryUrl, $urlWithGeneration, $categoryAssociation))->all();
  }
  public static function getOffersArrayForYandexYml(array $data, string $siteUrl, string $categoryUrl, bool $urlWithGeneration, array $categoryAssociation):array
  {
    $OfferFeedDto = new static(OfferFeedDto::arrayOf($data));
    return collect($OfferFeedDto)->map(fn(OfferFeedDto $dto) => $dto->getOfferArrayForYandexFeedYml($siteUrl, $categoryUrl, $urlWithGeneration, $categoryAssociation))->all();
  }
  public static function getOffersArrayLinkForPlexCrm(array $data, string $siteUrl, string $categoryUrl, int $siteExternalId, bool $urlWithGeneration, array $categoryAssociation):array
  {
    $OfferFeedDto = new static(OfferFeedDto::arrayOf($data));
    return collect($OfferFeedDto)->map(fn(OfferFeedDto $dto) => $dto->getOfferArrayLinkForPlexCrm($siteUrl, $categoryUrl, $siteExternalId, $urlWithGeneration, $categoryAssociation))->all();
  }
}
