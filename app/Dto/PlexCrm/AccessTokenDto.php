<?php

declare(strict_types=1);

namespace App\Dto\PlexCrm;

use App\Complex\Dto\Dto;

class AccessTokenDto extends Dto
{
  public string $access_token;
  public int $expires_in;
  public string $token_type;

  public function getAccessToken():string
  {
    $accessToken = trim($this->access_token, '"');
    return "{$accessToken}";
  }
}
