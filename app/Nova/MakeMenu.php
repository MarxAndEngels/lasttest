<?php

namespace App\Nova;
use App\Nova\Content\Dealer;
use App\Nova\Content\Feed;
use App\Nova\Content\Site;
use App\Nova\Content\User;
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
      MenuSection::dashboard(Main::class)->icon('chart-bar'),
      MenuSection::make('Content', [
//        MenuItem::make('allcontent','resources/dealers'),
        MenuItem::resource(User::class),
        MenuItem::resource(Dealer::class),
        MenuItem::resource(Site::class),
        MenuItem::resource(Feed::class),
      ])->icon('document-text')->collapsable(),

//      MenuSection::dashboard(User::class)->icon('view-grid'),
//      MenuSection::dashboard(Dealer::class)->icon('view-grid'),
//      MenuSection::make('Content', [
//        MenuItem::resource(Dealer::class),
//      ])->icon('document-text')->collapsable(),

//
//      MenuSection::make('Digest', [
//        MenuGroup::make('CRM', [
//            // ->canSee(fn(NovaRequest $request) => $request->user()->can(RoleConstants::ROOT)),
//          MenuItem::resource(Site::class),
//        ]),
//        MenuGroup::make('Sites', [
//        ])
//      ])->icon('light-bulb'),
//      // ->canSee(fn(NovaRequest $request) => $request->user()->can(RoleConstants::AD))->collapsable(),
//
//
//      MenuSection::make('Collaborators', [
//        MenuItem::resource(User::class),
//        MenuItem::link(__('nova-spatie-permissions::lang.sidebar_label_roles'), 'resources/roles'),
//          // ->canSee(fn($request) => $request->user()->can(RoleConstants::ROOT)),
//        MenuItem::link(__('nova-spatie-permissions::lang.sidebar_label_permissions'), 'resources/permissions'),
//          // ->canSee(fn(NovaRequest $request) => $request->user()->can(RoleConstants::ROOT))
//      ])->icon('key')->collapsable(),
//
////      MenuSection::resource(SiteSetting::class)
////        ->icon('cog'),
//        // ->canSee(fn(NovaRequest $request) => $request->user()->can(RoleConstants::AD)),
//
////      MenuSection::resource(Domain::class)
////        ->icon('globe'),
//        // ->canSee(fn(NovaRequest $request) => $request->user()->can(DomainPermission::VIEW)),
//
//      MenuSection::make('Logs')
//        ->path('/logs')->icon('document'),
//        // ->canSee(fn(NovaRequest $request) => $request->user()->can(RoleConstants::ROOT)),
//
//      MenuItem::externalLink('GraphiQL', '/graphiql'),
//        // ->canSee(fn(NovaRequest $request) => $request->user()->can(RoleConstants::ADMIN)),
//
//      MenuItem::externalLink('Horizon', '/horizon'),
        // ->canSee(fn(NovaRequest $request) => $request->user()->can(RoleConstants::ROOT))
    ];
  }

  public function getMenu(): array
  {
    return $this->menu;
  }
}
