<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

use App\Providers\AuthUserProvider;// 追加
use Auth;// 追加

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
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

      // 追加
    public function register() {
  		Auth::provider('auth_ex', function($app) {
  			$model = $app['config']['auth.providers.users.model'];
  			return new AuthUserProvider($app['hash'], $model);
  		});
  	}
      // 追加END
}
