<?php

namespace App\Providers;

use App\Constants\Permission\RoleConstants;
use App\Models\Bank;
use App\Models\Dealer;
use App\Models\SeoTag;
use App\Models\Site;
use App\Models\SiteSetting;
use App\Models\User;
use App\Nova\MakeMenu;
use App\Observers\BankObserver;
use App\Observers\DealerObserver;
use App\Observers\SeoTagObserver;
use App\Observers\SiteObserver;
use App\Observers\SiteSettingObserver;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Itsmejoshua\Novaspatiepermissions\Novaspatiepermissions;
use Laravel\Nova\Nova;
use Laravel\Nova\NovaApplicationServiceProvider;

class NovaServiceProvider extends NovaApplicationServiceProvider
{
  /**
   * Bootstrap any application services.
   *
   * @return void
   */
  public function boot()
  {
    parent::boot();

    Nova::mainMenu(function (Request $request) {
      return (new MakeMenu($request))->getMenu();
    });
    Bank::observe(BankObserver::class);
    Dealer::observe(DealerObserver::class);
    SiteSetting::observe(SiteSettingObserver::class);
    SeoTag::observe(SeoTagObserver::class);
    Site::observe(SiteObserver::class);
  }

  /**
   * Register the Nova routes.
   *
   * @return void
   */
  protected function routes()
  {
    Nova::routes()
      ->withAuthenticationRoutes()
      ->withPasswordResetRoutes()
      ->register();
  }

  /**
   * Register the Nova gate.
   *
   * This gate determines who can access Nova in non-local environments.
   *
   * @return void
   */
  protected function gate()
  {
    Gate::define('viewNova', fn(User $user) => $user->can(RoleConstants::MANAGER));
  }

  /**
   * Get the dashboards that should be listed in the Nova sidebar.
   *
   * @return array
   */
  protected function dashboards()
  {
    return [
      new \App\Nova\Dashboards\Main,
    ];
  }

  /**
   * Get the tools that should be listed in the Nova sidebar.
   *
   * @return array
   */
  public function tools()
  {
    return [
      Novaspatiepermissions::make(),
      (new \Stepanenko3\LogsTool\LogsTool())
        ->canSee(fn(Request $request) => $request->user()->isRoot())
        ->canDownload(fn(Request $request) => $request->user()->isRoot())
        ->canDelete(fn(Request $request) => $request->user()->isRoot()),
    ];
  }

  /**
   * Register any application services.
   *
   * @return void
   */
  public function register()
  {
    //
  }
}
