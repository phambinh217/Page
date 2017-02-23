<?php
/**
 * ModuleAlias: page
 * ModuleName: page
 * Description: This is the first file run of module. You can assign bootstrap or register module services
 * @author: noname
 * @version: 1.0
 * @package: PhambinhCMS
 */
namespace Phambinh\Page\Providers;

use Illuminate\Support\ServiceProvider;

class ModuleServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        // Load views
        $this->loadViewsFrom(__DIR__ . '/../../resources/views', 'Page');

        // Load translations
        $this->loadTranslationsFrom(__DIR__ . '/../../resources/lang', 'Page');

        // Load migrations
        $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');

        // Merge configs
        if (\File::exists(__DIR__ . '/../../config/config.php')) {
            $this->mergeConfigFrom(__DIR__ . '/../../config/config.php', 'page');
        }

        // Load helper
        if (\File::exists(__DIR__ . '/../../helper/helper.php')) {
            include __DIR__ . '/../../helper/helper.php';
        }

        $this->publishes([
            __DIR__.'/../../assets' => public_path('assets'),
        ], 'public');

        $this->registerPolices();
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        \Module::registerFromJsonFile('user', __DIR__ .'/../../module.json');
        \Menu::registerType('Trang tĩnh', \Phambinh\Page\Page::class);
        $this->app->register(\Phambinh\Page\Providers\RoutingServiceProvider::class);
        $this->registerAdminMenu();
    }

    private function registerPolices()
    {
        \AccessControl::define('Trang tĩnh - Thêm trang mới', 'admin.page.create');
        \AccessControl::define('Trang tĩnh - Xem danh sách', 'admin.page.index');
        \AccessControl::define('Trang tĩnh - Ẩn trang', 'admin.page.disable');
        \AccessControl::define('Trang tĩnh - Công khai trang', 'admin.page.enable');
        \AccessControl::define('Trang tĩnh - Sửa trang tĩnh', 'admin.page.edit');
        \AccessControl::define('Trang tĩnh - Xóa trang', 'admin.page.destroy');
    }

    private function registerAdminMenu()
    {
        add_action('admin.init', function () {
            if (\Auth::user()->can('admin.page.index')) {
                \AdminMenu::register('page', [
                    'parent' => 'main-manage',
                    'label' => 'Trang tĩnh',
                    'icon' => 'icon-docs',
                    'url'   => route('admin.page.index'),
                    'order' => '2',
                ]);
            }

            if (\Auth::user()->can('admin.page.create')) {
                \AdminMenu::register('page.create', [
                    'parent' => 'page',
                    'label' => 'Thêm trang mới',
                    'icon' => 'icon-note',
                    'url'   => route('admin.page.create'),
                ]);
            }

            if (\Auth::user()->can('admin.page.index')) {
                \AdminMenu::register('page.index', [
                    'parent' => 'page',
                    'label' => 'Danh sách trang',
                    'icon' => 'icon-list',
                    'url'   => route('admin.page.index'),
                ]);
            }
        });
    }
}
