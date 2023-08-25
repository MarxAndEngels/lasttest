<?php

namespace App\Constants\Enums;

class FeedbackEnum
{

  public const NEW = 'NEW';
  public const SUCCESS = 'SUCCESS';
  public const ERROR = 'ERROR';
  public const CREDIT = 'CREDIT';
  public const CALLBACK = 'CALLBACK';
  public const BUYOUT = 'BUYOUT';
  public const TRADE_IN = 'TRADE-IN';
  public const HIRE_PURCHASE  = 'HIRE-PURCHASE';
  public const PAID_SELECTION = 'PAID-SELECTION';
  public const FREE_SELECTION = 'FREE-SELECTION';
  public const STATION = 'STATION';
  public const TEST_DRIVE = 'TEST-DRIVE';

  public const STATUS_ENUM = [
    self::NEW, self::SUCCESS, self::ERROR
  ];
  public const TYPE_ENUM = [
    self::CREDIT, self::CALLBACK, self::BUYOUT, self::TRADE_IN, self::HIRE_PURCHASE, self::PAID_SELECTION, self::STATION, self::FREE_SELECTION, self::TEST_DRIVE
  ];
  public const TYPE_ENUM_TO_PLEX_CRM = [
    self::CREDIT, self::CALLBACK, self::BUYOUT, self::TRADE_IN, self::HIRE_PURCHASE, self::FREE_SELECTION, self::TEST_DRIVE
  ];

  public const TYPE_ENUM_TO_MEGA_CRM = [
    self::CREDIT, self::CALLBACK, self::BUYOUT, self::TRADE_IN, self::HIRE_PURCHASE, self::FREE_SELECTION, self::TEST_DRIVE
  ];
  public const TYPE_ENUM_TO_EMAIL = [
    self::PAID_SELECTION, self::STATION
  ];
}
