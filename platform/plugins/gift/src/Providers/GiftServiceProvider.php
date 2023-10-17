<?php

namespace Botble\Gift\Providers;

use Botble\Gift\Models\Gift;
use Botble\Base\Facades\DashboardMenu;
use Botble\Base\Traits\LoadAndPublishDataTrait;
use Botble\Base\Supports\ServiceProvider;
use Illuminate\Routing\Events\RouteMatched;

class GiftServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    public function boot(): void
    {
        $this
            ->setNamespace('plugins/gift')
            ->loadHelpers()
            ->loadAndPublishConfigurations(['permissions'])
            ->loadMigrations()
            ->loadAndPublishTranslations()
            ->loadAndPublishViews()
            ->loadRoutes();

        if (defined('LANGUAGE_ADVANCED_MODULE_SCREEN_NAME')) {
            \Botble\LanguageAdvanced\Supports\LanguageAdvancedManager::registerModule(Gift::class, [
                'name',
            ]);
        }

        $this->app['events']->listen(RouteMatched::class, function () {
            DashboardMenu::registerItem([
                'id' => 'cms-plugins-gift',
                'priority' => 5,
                'parent_id' => null,
                'name' => 'plugins/gift::gift.name',
                'icon' => 'fa fa-list',
                'url' => route('gift.index'),
                'permissions' => ['gift.index'],
            ]);
        });
    }
}
