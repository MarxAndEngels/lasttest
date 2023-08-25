<?php

namespace App\Constants;

class MediaConstants
{
  public const MEDIA_STORIES          = 'stories';
  public const MEDIA_STORY_CONTENTS   = 'story_contents';
  public const MEDIA_STATIONS         = 'stations';
  public const MEDIA_BANKS            = 'banks';
  public const MEDIA_BANKS_CAR        = 'banks_car';
  public const MEDIA_BANKS_LICENSE    = 'banks_license';
  public const MEDIA_ARTICLES         = 'articles';
  public const MEDIA_ARTICLE_PREVIEWS = 'article_previews';

  public const MEDIA_ARTICLE_SLIDE    = 'article_slide';
  public const MEDIA_DEALERS          = 'dealers';
  public const MEDIA_DEALER_LOGO      = 'dealer_logo';
  public const MEDIA_SLIDES           = 'slides';
  public const MEDIA_SLIDE_ELEMENTS   = 'slide_elements';

  public const CONVERSION_XS          = 'xs';
  public const CONVERSION_TINY        = 'tiny';
  public const CONVERSION_THUMB       = 'thumb';
  public const CONVERSION_SMALL       = 'small';
  public const CONVERSION_MEDIUM      = 'medium';
  public const CONVERSION_LARGE       = 'large';
  public const CONVERSION_ORIGINAL    = 'original';
  public const CONVERSION_SRC         = 'src';
  public const CONVERSION_SLIDE_1X    = 'slide_1x';
  public const CONVERSION_SLIDE_2X    = 'slide_2x';

  public const CONVERSION_XS_WEBP     = 'xs_webp';
  public const CONVERSION_TINY_WEBP     = 'tiny_webp';
  public const CONVERSION_THUMB_WEBP    = 'thumb_webp';
  public const CONVERSION_SMALL_WEBP    = 'small_webp';
  public const CONVERSION_MEDIUM_WEBP   = 'medium_webp';
  public const CONVERSION_LARGE_WEBP    = 'large_webp';
  public const CONVERSION_ORIGINAL_WEBP = 'original_webp';
  public const CONVERSION_SLIDE_1X_WEBP = 'slide_1x_webp';
  public const CONVERSION_SLIDE_2X_WEBP = 'slide_2x_webp';


  public const WIDTH = [
    self::CONVERSION_ORIGINAL => 1920,
    self::CONVERSION_LARGE => 1200,
    self::CONVERSION_SLIDE_2X => 1000,
    self::CONVERSION_SLIDE_1X => 750,
    self::CONVERSION_MEDIUM => 720,
    self::CONVERSION_SMALL => 660,
    self::CONVERSION_THUMB => 480,
    self::CONVERSION_TINY => 240,
    self::CONVERSION_XS => 120,

    self::CONVERSION_ORIGINAL_WEBP => 1920,
    self::CONVERSION_LARGE_WEBP => 1200,
    self::CONVERSION_SLIDE_2X_WEBP => 1000,
    self::CONVERSION_SLIDE_1X_WEBP => 750,
    self::CONVERSION_MEDIUM_WEBP => 720,
    self::CONVERSION_SMALL_WEBP => 660,
    self::CONVERSION_THUMB_WEBP => 480,
    self::CONVERSION_TINY_WEBP => 240,
    self::CONVERSION_XS_WEBP => 120,

  ];
  public const CONVERSION_COLLECTION_WEBP = [
    self::MEDIA_STORIES => [
      self::CONVERSION_XS_WEBP,
      self::CONVERSION_TINY_WEBP,
      self::CONVERSION_THUMB_WEBP
      ],
    self::MEDIA_STORY_CONTENTS => [
      self::CONVERSION_SMALL_WEBP,
      self::CONVERSION_MEDIUM_WEBP
    ],
    self::MEDIA_STATIONS => [
      self::CONVERSION_MEDIUM_WEBP,
      self::CONVERSION_LARGE_WEBP
    ],
    self::MEDIA_BANKS_CAR => [
      self::CONVERSION_THUMB_WEBP,
      self::CONVERSION_SMALL_WEBP
    ],
    self::MEDIA_ARTICLES => [
      self::CONVERSION_MEDIUM_WEBP,
      self::CONVERSION_LARGE_WEBP
    ],
    self::MEDIA_ARTICLE_PREVIEWS => [
      self::CONVERSION_THUMB_WEBP,
      self::CONVERSION_SMALL_WEBP
    ],
    self::MEDIA_DEALERS => [
      self::CONVERSION_SMALL_WEBP,
      self::CONVERSION_MEDIUM_WEBP
    ],
    self::MEDIA_SLIDES => [
      self::CONVERSION_SLIDE_1X_WEBP,
      self::CONVERSION_SLIDE_2X_WEBP
    ],
    self::MEDIA_SLIDE_ELEMENTS => [
      self::CONVERSION_THUMB_WEBP,
      self::CONVERSION_SMALL_WEBP
    ],
//    self::MEDIA_ARTICLE_SLIDE => [
//      self::CONVERSION_THUMB_WEBP,
//      self::CONVERSION_SMALL_WEBP
//    ]
  ];
  public const CONVERSION_COLLECTION = [
    self::MEDIA_STORIES => [
      self::CONVERSION_XS,
      self::CONVERSION_TINY,
      self::CONVERSION_THUMB,
    ],
    self::MEDIA_STORY_CONTENTS => [
      self::CONVERSION_SMALL,
      self::CONVERSION_MEDIUM,
    ],
    self::MEDIA_STATIONS => [
      self::CONVERSION_MEDIUM,
      self::CONVERSION_LARGE
    ],
    self::MEDIA_BANKS_CAR => [
      self::CONVERSION_THUMB,
      self::CONVERSION_SMALL
    ],
    self::MEDIA_ARTICLES => [
      self::CONVERSION_MEDIUM,
      self::CONVERSION_LARGE
    ],
    self::MEDIA_ARTICLE_PREVIEWS => [
      self::CONVERSION_THUMB,
      self::CONVERSION_SMALL
    ],
    self::MEDIA_DEALERS => [
      self::CONVERSION_SMALL,
      self::CONVERSION_MEDIUM,
    ],
    self::MEDIA_SLIDES => [
      self::CONVERSION_SLIDE_1X,
      self::CONVERSION_SLIDE_2X
    ],
    self::MEDIA_SLIDE_ELEMENTS => [
      self::CONVERSION_THUMB,
      self::CONVERSION_SMALL
    ],
    self::MEDIA_ARTICLE_SLIDE => [
      self::CONVERSION_THUMB
    ]
  ];

  public const FORMAT_WEBP = 'webp';

}
