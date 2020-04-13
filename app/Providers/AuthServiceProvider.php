<?php

namespace App\Providers;

use App\Policies\DeskPolicy;
use App\Policies\EmployeePolicy;
use App\Policies\ImportPolicy;
use App\Policies\MaterialPolicy;
use App\Policies\PositionPolicy;
use App\Policies\ProductCatePolicy;
use App\Policies\ProductPolicy;
use App\Policies\SellPolicy;
use App\Policies\SupplierPolicy;
use App\Policies\VoucherPolicy;
use App\Policies\WorkdayPolicy;
use App\Policies\ZonePolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
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
        Gate::resource('position', PositionPolicy::class);
        Gate::define('position.role', PositionPolicy::class . '@role');
        Gate::resource('employee', EmployeePolicy::class);
        Gate::resource('zone', ZonePolicy::class);
        Gate::resource('desk', DeskPolicy::class);
        Gate::resource('material', MaterialPolicy::class);
        Gate::resource('supplier', SupplierPolicy::class);
        Gate::resource('import', ImportPolicy::class);
        Gate::resource('productcate', ProductCatePolicy::class);
        Gate::resource('product', ProductPolicy::class);
        Gate::resource('voucher', VoucherPolicy::class);
        Gate::resource('workday', WorkdayPolicy::class);
        Gate::resource('sell', SellPolicy::class);
        Gate::define('sell.pay', SellPolicy::class . '@pay');
        Gate::define('sell.merge', SellPolicy::class . '@merge');
    }
}
