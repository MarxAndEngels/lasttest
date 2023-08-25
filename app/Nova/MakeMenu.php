<?php

namespace App\Nova;

use App\Constants\Permission\DomainPermission;
use App\Constants\Permission\RoleConstants;
use App\Nova\Content\Article;
use App\Nova\Content\ArticleCategory;
use App\Nova\Content\Bank;
use App\Nova\Content\Domain;
use App\Nova\Content\Mark;
use App\Nova\Content\Slide;
use App\Nova\Content\StationCategory;
use App\Nova\Content\Story;
use App\Nova\Content\TelegramChannel;
use App\Nova\Content\User;
use App\Nova\Crm\Dealer;
use App\Nova\Crm\Feedback;
use App\Nova\Crm\FeedbackMegaCrm;
use App\Nova\Crm\Offer\Offer;
use App\Nova\Crm\Site;
use App\Nova\Site\FeedFilter;
use App\Nova\Site\PriceOldSite;
use App\Nova\Site\Region;
use App\Nova\Site\SeoTag;
use App\Nova\Site\Set;
use App\Nova\Site\SiteSetting;
use Illuminate\Http\Request;
use Itsmejoshua\Novaspatiepermissions\Permission;
use Laravel\Nova\Dashboards\Main;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Menu\MenuGroup;
use Laravel\Nova\Menu\MenuItem;
use Laravel\Nova\Menu\MenuSection;

class MakeMenu
{
  protected array $menu;
  public function __construct(Request $request)
  {
    $this->makeMenu();
  }
  protected function makeMenu(): void
  {
    $this->menu = [
      MenuSection::dashboard(Main::class)->icon('view-grid'),


      MenuSection::make('Content', [
        MenuItem::resource(Story::class),
        MenuItem::resource(Slide::class),
        MenuItem::resource(Bank::class),
        MenuItem::resource(Article::class),
        MenuItem::resource(TelegramChannel::class),
        MenuItem::resource(Mark::class)
      ])->icon('document-text')->collapsable(),

      MenuSection::make('Digest', [
        MenuGroup::make('CRM', [
          MenuItem::resource(Feedback::class),
          MenuItem::resource(FeedbackMegaCrm::class)
            ->canSee(fn(NovaRequest $request) => $request->user()->can(RoleConstants::ROOT)),
          MenuItem::resource(Dealer::class),
          MenuItem::resource(Site::class),
        ]),
        MenuGroup::make('Sites', [
          MenuItem::resource(FeedFilter::class),
          MenuItem::resource(PriceOldSite::class),
          MenuItem::resource(SeoTag::class),
          MenuItem::resource(Region::class),
          MenuItem::resource(Set::class),
        ])
      ])->icon('light-bulb')->canSee(fn(NovaRequest $request) => $request->user()->can(RoleConstants::AD))->collapsable(),


      MenuSection::make('Collaborators', [
        MenuItem::resource(Permission::class),
        MenuItem::resource(User::class),
        MenuItem::link(__('nova-spatie-permissions::lang.sidebar_label_roles'), 'resources/roles')
          ->canSee(fn($request) => $request->user()->can(RoleConstants::ROOT)),
        MenuItem::link(__('nova-spatie-permissions::lang.sidebar_label_permissions'), 'resources/permissions')
          ->canSee(fn(NovaRequest $request) => $request->user()->can(RoleConstants::ROOT))
      ])->icon('key')->collapsable(),

      MenuSection::resource(SiteSetting::class)
        ->icon('cog')
        ->canSee(fn(NovaRequest $request) => $request->user()->can(RoleConstants::AD)),

      MenuSection::resource(Domain::class)
        ->icon('globe')
        ->canSee(fn(NovaRequest $request) => $request->user()->can(DomainPermission::VIEW)),

      MenuSection::make('Logs')
        ->path('/logs')->icon('document')
        ->canSee(fn(NovaRequest $request) => $request->user()->can(RoleConstants::ROOT)),

      MenuItem::externalLink('GraphiQL', '/graphiql')
        ->canSee(fn(NovaRequest $request) => $request->user()->can(RoleConstants::ADMIN)),

      MenuItem::externalLink('Horizon', '/horizon')
        ->canSee(fn(NovaRequest $request) => $request->user()->can(RoleConstants::ROOT))
    ];
  }

  public function getMenu(): array
  {
    return $this->menu;
  }
}
