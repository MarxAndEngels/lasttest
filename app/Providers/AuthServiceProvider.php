<?php

namespace App\Providers;

use App\Models\Article;
use App\Models\ArticleCategory;
use App\Models\Bank;
use App\Models\Dealer;
use App\Models\Domain;
use App\Models\Feedback;
use App\Models\FeedFilter;
use App\Models\Folder;
use App\Models\Mark;
use App\Models\Offer;
use App\Models\PriceOldSite;
use App\Models\Region;
use App\Models\SeoTag;
use App\Models\Set;
use App\Models\Site;
use App\Models\SiteSetting;
use App\Models\Slide;
use App\Models\Station;
use App\Models\StationCategory;
use App\Models\Story;
use App\Models\StoryContent;
use App\Models\TelegramChannelSite;
use App\Models\User;
use App\Policies\ArticleCategoryPolicy;
use App\Policies\ArticlePolicy;
use App\Policies\BankPolicy;
use App\Policies\DealerPolicy;
use App\Policies\DomainPolicy;
use App\Policies\FeedbackPolicy;
use App\Policies\FolderPolicy;
use App\Policies\MarkPolicy;
use App\Policies\OfferPolicy;
use App\Policies\PriceOldPolicy;
use App\Policies\RegionPolicy;
use App\Policies\SeoTagPolicy;
use App\Policies\SetPolicy;
use App\Policies\SitePolicy;
use App\Policies\SiteSettingPolicy;
use App\Policies\SlidePolicy;
use App\Policies\StationPolicy;
use App\Policies\StoryPolicy;
use App\Policies\TelegramChannelPolicy;
use App\Policies\UserPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AuthServiceProvider extends ServiceProvider
{
  /**
   * The model to policy mappings for the application.
   *
   * @var array
   */
  protected $policies = [
//    Bank::class => BankPolicy::class,
//    Story::class => StoryPolicy::class,
//    StoryContent::class => StoryPolicy::class,
//    Station::class => StationPolicy::class,
//    StationCategory::class => StationPolicy::class,
//    Dealer::class => DealerPolicy::class,
//    Site::class => SitePolicy::class,
//    User::class => UserPolicy::class,
//    Permission::class => UserPolicy::class,
//    Role::class => UserPolicy::class,
//    PriceOldSite::class => PriceOldPolicy::class,
//    Region::class => RegionPolicy::class,
//    SeoTag::class => SeoTagPolicy::class,
//    Set::class => SetPolicy::class,
//    Article::class => ArticlePolicy::class,
//    ArticleCategory::class => ArticleCategoryPolicy::class,
//    Offer::class => OfferPolicy::class,
//    Feedback::class => FeedbackPolicy::class,
//    SiteSetting::class => SiteSettingPolicy::class,
//    FeedFilter::class => FeedbackPolicy::class,
//    Slide::class => SlidePolicy::class,
//    TelegramChannelSite::class => TelegramChannelPolicy::class,
//    Mark::class => MarkPolicy::class,
//    Folder::class => FolderPolicy::class,
//    Domain::class => DomainPolicy::class
    // 'App\Model' => 'App\Policies\ModelPolicy',
  ];

  /**
   * Register any authentication / authorization services.
   *
   * @return void
   */
  public function boot()
  {
    $this->registerPolicies();

    //
  }
}
