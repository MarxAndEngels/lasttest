<?php

namespace App\Jobs;

use App\Api\Beget\BegetClient;
use App\Constants\Attributes\AttributeName;
use App\Dto\Beget\DomainDto;
use App\Dto\Beget\DomainListCollectionDto;
use App\Models\Domain;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CheckDomainStatusJob implements ShouldQueue
{
  use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

  /**
   * Create a new job instance.
   *
   * @return void
   */
  public function __construct()
  {
    //
  }
  public function handle(): void
  {
    $domainList = (new BegetClient())->getListDomain();
    if ($domainList['status'] != 'success') {
      Log::error("Не удалось получить список доменов BEGET");
      return;
    }

    $domainList = DomainListCollectionDto::create($domainList['answer']['result']);

    collect($domainList)->each(function (DomainDto $domainDto) {
      if ($this->checkSubDomain($domainDto->fqdn)) {
        return;
      }

      $domainDto->available = !$domainDto->date_expire;

      $domainQuery = Domain::query();

      $domain = $domainQuery->firstOrCreate(
        [
          AttributeName::EXTERNAL_ID => $domainDto->id
        ],
        [
          AttributeName::FQDN => $domainDto->fqdn,
          AttributeName::DATE_ADD => $domainDto->date_add,
          AttributeName::DATE_REGISTER => $domainDto->date_register,
          AttributeName::DATE_EXPIRE => $domainDto->date_expire,
          AttributeName::AVAILABLE => $domainDto->available
        ]
      );

      if (!$domain->date_expire || ($domain->date_expire && Carbon::parse($domain->date_expire)->diff(Carbon::now())->days <= 30)) {
        $whois = $this->whois($domainDto->fqdn);
        if ($whois) {
          $domain->date_register = $whois['date_register'];
          $domain->date_expire = $whois['date_expire'];
          $domain->available = false;
          $domain->save();
        }
      }

    });
  }
  private function whois(string $fqdn): ?array
  {
    $response = Http::get("https://api.whois.vu/?q={$fqdn}&clean");
    if (!$response->successful()) {
      Log::error("Not work api.whois.vu");
      return null;
    }
    $json = $response->json();

    if ($json['available'] == 'yes') {
      return null;
    }
    return [
      AttributeName::DATE_REGISTER => Carbon::createFromTimestamp($json['created'])->toDateString(),
      AttributeName::DATE_EXPIRE => Carbon::createFromTimestamp($json['expires'])->toDateString()
    ];

  }

  private function checkSubDomain(string $fqdn): bool
  {
    $exp = explode('.', $fqdn);
    if (count($exp) > 2) {
      return true;
    } else {
      return false;
    }
  }

}
