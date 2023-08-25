<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;

class AppServiceProvider extends ServiceProvider
{
  /**
   * Register any application services.
   *
   * @return void
   */
  public function register()
  {
    Blade::directive('engine', function ($expression) {
      return "<?php echo \App\Helpers\Modifiers::numberFormatFloat($expression);?>";
    });
    Blade::directive('lower', function ($expression) {
      return "<?php echo \App\Helpers\Modifiers::toLower($expression);?>";
    });
    Blade::directive('lcfirst', function ($expression) {
      return "<?php echo \App\Helpers\Modifiers::lcfirst($expression);?>";
    });
    Blade::directive('ucfirst', function ($expression) {
      return "<?php echo \App\Helpers\Modifiers::ucfirst($expression);?>";
    });

    Blade::directive('number', function ($expression) {
      return "<?php echo \App\Helpers\Modifiers::numberFormatPrice($expression);?>";
    });
    Blade::directive('owner', function ($count) {

      return "<?php echo \App\Helpers\Modifiers::declension($count,'владелец','владельца','владельцев',true);?>";
    });

    Blade::directive('declension', function (int $count, string $one, string $few, string $many) {

      return "<?php echo \App\Helpers\Modifiers::declension($count,$one,$few,$many,true);?>";
    });
  }

  /**
   * Bootstrap any application services.
   *
   * @return void
   */
  public function boot()
  {
    //
  }
}
