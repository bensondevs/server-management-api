<?php 

use App\Http\Controllers\Meta\{
	ContainerController,
	CountryController,
	PaymentController,
	DatacenterController,
	SubnetController,
	JobTrackerController,
	OrderController,
	UserController
};

/*
|--------------------------------------------------------------------------
| Meta Routes
|--------------------------------------------------------------------------
|
| Here is where you can register meta routes for your application. These
| routes are for providing data about enumeration class data to description.
|
*/

Route::group(['as' => 'meta.'], function () {
	Route::group(['prefix' => 'user', 'as' => 'user.'], function () {
		Route::get('account_types', [UserController::class, 'accountTypes']);
	});

	Route::group(['prefix' => 'countries', 'as' => 'countries.'], function () {
		Route::get('/', [CountryController::class, 'countries'])->name('index');
	});

	Route::group(['prefix' => 'payment', 'as' => 'payment.'], function () {
		Route::get('methods', [PaymentController::class, 'paymentMethods'])->name('methods');
		Route::get('statuses', [PaymentController::class, 'paymentStatuses'])->name('statuses');
		Route::get('status_badges', [PaymentController::class, 'statusBadges'])->name('status_badges');
	});

	Route::group(['prefix' => 'datacenter', 'as' => 'datacenter.'], function () {
		Route::get('status_badges', [DatacenterController::class, 'statusBadges']);
	});

	Route::group(['prefix' => 'subnet', 'as' => 'subnet.'], function () {
		Route::get('status_badges', [SubnetController::class, 'statusBadges']);
	});

	Route::group(['prefix' => 'container', 'as' => 'container.'], function () {
		Route::get('status_badges', [ContainerController::class, 'statusBadges']);
		Route::get('vpn_status_badges', [ContainerController::class, 'vpnStatusBadges']);
		Route::get('samba_status_badges', [ContainerController::class, 'sambaStatusBadges']);
		Route::get('nfs_status_badges', [ContainerController::class, 'nfsStatusBadges']);
		Route::get('nginx_status_badges', [ContainerController::class, 'nginxStatusBadges']);
		Route::get('samba_status_badges', [ContainerController::class, 'sambaStatusBadges']);
	});

	Route::group(['prefix' => 'job_tracker', 'as' => 'job_tracker.'], function () {
		Route::get('status_badges', [JobTrackerController::class, 'statusBadges'])->name('status_badges');
	});

	Route::group(['prefix' => 'order', 'as' => 'order.'], function () {
		Route::get('statuses', [OrderController::class, 'statuses']);
		Route::get('status_badges', [OrderController::class, 'statusBadges']);
	});
});
