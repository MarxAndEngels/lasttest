<?php

declare(strict_types=1);

namespace App\Providers;


use App\Complex\GraphQL\GraphQL as AppGraphQL;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Contracts\Container\Container;
use Illuminate\Support\ServiceProvider;
use Rebing\GraphQL\GraphQL as VendorGraphQL;

final class GraphQLServiceProvider extends ServiceProvider
{
  public function register(): void
  {
    $this->registerGraphQL();
  }

  private function registerGraphQL(): void
  {
    $this->app->extend(
      VendorGraphQL::class,
      fn(VendorGraphQL $graphQL, Container $app): VendorGraphQL => new AppGraphQL($app, $app->make(Repository::class))
    );
  }
}
