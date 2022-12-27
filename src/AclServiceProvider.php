<?php

namespace Newnet\Acl;

use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use Newnet\Acl\Contracts\PermissionManagerInterface;
use Newnet\Acl\Facades\Permission;
use Newnet\Acl\Http\Middleware\AdminAuth;
use Newnet\Acl\Http\Middleware\AdminPermission;
use Newnet\Acl\Http\Middleware\RedirectIfAdminAuth;
use Newnet\Acl\Models\Role;
use Newnet\Acl\Models\Admin;
use Newnet\Acl\Repositories\RoleRepository;
use Newnet\Acl\Repositories\RoleRepositoryInterface;
use Newnet\Acl\Repositories\AdminRepository;
use Newnet\Acl\Repositories\AdminRepositoryInterface;
use Newnet\Core\Events\CoreAdminMenuRegistered;
use Newnet\Core\Facades\AdminMenu;

class AclServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/acl.php', 'acl');

        $this->registerMiddleware();
        $this->registerConfigData();

        $this->app->singleton(PermissionManagerInterface::class, PermissionManager::class);

        $this->app->singleton(RoleRepositoryInterface::class, function () {
            return new RoleRepository(new Role);
        });

        $this->app->singleton(AdminRepositoryInterface::class, function () {
            return new AdminRepository(new Admin);
        });
    }

    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        $this->loadRoutesFrom(__DIR__ . '/../routes/auth.php');
        $this->loadRoutesFrom(__DIR__ . '/../routes/admin.php');
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'acl');
        $this->loadTranslationsFrom(__DIR__ . '/../resources/lang', 'acl');

        $this->registerBladeDirectives();
        $this->registerPermissions();
        $this->registerAdminMenus();
    }

    protected function registerConfigData()
    {
        $aclConfigData = include __DIR__ . '/../config/acl.php';
        $authConfig = $this->app['config']->get('auth');
        $auth = array_merge_recursive_distinct($aclConfigData['auth'], $authConfig);
        $this->app['config']->set('auth', $auth);
    }

    protected function registerMiddleware()
    {
        /** @var Router $router */
        $router = $this->app['router'];
        $router->aliasMiddleware('admin.auth', AdminAuth::class);
        $router->aliasMiddleware('admin.can', AdminPermission::class);
        $router->aliasMiddleware('admin.guest', RedirectIfAdminAuth::class);
    }

    protected function registerBladeDirectives()
    {
        Blade::directive('admincan', function ($expression) {
            return "<?php if (Auth::guard('admin')->check() && Auth::guard('admin')->user()->hasPermission({$expression})): ?>";
        });

        Blade::directive('endadmincan', function () {
            return "<?php endif; ?>";
        });
    }

    protected function registerPermissions()
    {
        Permission::add('role.index', __('acl::permission.role.index'));
        Permission::add('role.create', __('acl::permission.role.create'));
        Permission::add('role.edit', __('acl::permission.role.edit'));
        Permission::add('role.destroy', __('acl::permission.role.destroy'));

        Permission::add('user.index', __('acl::permission.user.index'));
        Permission::add('user.create', __('acl::permission.user.create'));
        Permission::add('user.edit', __('acl::permission.user.edit'));
        Permission::add('user.destroy', __('acl::permission.user.destroy'));
    }

    private function registerAdminMenus()
    {
        Event::listen(CoreAdminMenuRegistered::class, function () {
            AdminMenu::addItem(__('acl::menu.user_system.index'), [
                'id'     => 'user_system',
                'parent' => 'system_root',
                'href'   => '#',
                'icon'   => 'fas fa-users-cog',
                'order'  => 10,
            ]);

            AdminMenu::addItem(__('acl::menu.user.index'), [
                'id'         => 'user',
                'parent'     => 'user_system',
                'route'      => 'admin.user.index',
                'permission' => 'user.index',
                'icon'       => 'fas fa-users-cog',
                'order'      => 1,
            ]);

            AdminMenu::addItem(__('acl::menu.role.index'), [
                'id'         => 'role',
                'parent'     => 'user_system',
                'route'      => 'admin.role.index',
                'permission' => 'role.index',
                'icon'       => 'fas fa-user-tag',
                'order'      => 2,
            ]);
        });
    }
}
