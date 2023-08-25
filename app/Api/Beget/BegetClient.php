<?php

declare(strict_types=1);

namespace App\Api\Beget;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class BegetClient
{

  private string $apiUrl;
  private string $login;
  private string $password;

  public function __construct(
  )
  {
    $this->apiUrl = config('beget.url');
    $this->login = config('beget.login');
    $this->password = config('beget.password');
  }

  public function getListDomain(): ?array
  {

    $response = Http::get("{$this->apiUrl}/api/domain/getList", [
      'login' => $this->login,
      'passwd' => $this->password
    ]);
    if (!$response->successful())
    {
      Log::error($response->body());
      return null;
    }
    return $response->json();
  }






}
