<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Http;

use App\Http\Controllers\Auth\LoginController;

use App\Http\Controllers\TestController;

use App\Http\Controllers\Dashboard\DashboardController;
	use App\Http\Controllers\Dashboard\PermissionController;
	use App\Http\Controllers\Dashboard\RegionController;
	use App\Http\Controllers\Dashboard\DatacenterController;
	use App\Http\Controllers\Dashboard\ServerController;
	use App\Http\Controllers\Dashboard\SubnetController;
	use App\Http\Controllers\Dashboard\SubnetIpController;
	use App\Http\Controllers\Dashboard\OrderController;
	use App\Http\Controllers\Dashboard\PaymentController;
	use App\Http\Controllers\Dashboard\ServicePlanController;
	use App\Http\Controllers\Dashboard\ServiceAddonController;
	use App\Http\Controllers\Dashboard\CommandHistoryController;
	use App\Http\Controllers\Dashboard\ContainerController;
		use App\Http\Controllers\Dashboard\ContainerVpnController;
		use App\Http\Controllers\Dashboard\ContainerSambaController;
		use App\Http\Controllers\Dashboard\ContainerNfsController;
		use App\Http\Controllers\Dashboard\ContainerNginxController;
	use App\Http\Controllers\Dashboard\PricingController;
	use App\Http\Controllers\Dashboard\UserController;
	use App\Http\Controllers\Dashboard\AdministratorController;
	use App\Http\Controllers\Dashboard\NewsletterController;
	use App\Http\Controllers\Dashboard\SettingController;
	use App\Http\Controllers\Dashboard\ActivityLogController;

use App\Http\Controllers\Api\Payments\PaymentController as PaymentApiController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes();
Route::post('login', [LoginController::class, 'authenticate'])->name('auth.login');

Route::get('many_to_many', function () {
	return \App\Models\SambaGroup::first()->shares;
});

/*Route::group(['prefix' => 'meta', 'as' => 'meta.'], function () {
	Route::group(['prefix' => 'countries', 'as' => 'countries.'], function () {
		Route::get('/', [MetaCountryController::class, 'countries'])->name('index');
	});

	Route::group(['prefix' => 'payment', 'as' => 'payment.'], function () {
		Route::get('methods', [MetaPaymentController::class, 'paymentMethods'])->name('methods');
		Route::get('statuses', [MetaPaymentController::class, 'paymentStatuses'])->name('statuses');
		Route::get('status_badges', [MetaPaymentController::class, 'statusBadges'])->name('status_badges');
	});

	Route::group(['prefix' => 'datacenter', 'as' => 'datacenter.'], function () {
		Route::get('status_badges', [MetaDatacenterController::class, 'statusBadges']);
	});

	Route::group(['prefix' => 'subnet', 'as' => 'subnet.'], function () {
		Route::get('status_badges', [MetaSubnetController::class, 'statusBadges']);
	});

	Route::group(['prefix' => 'container', 'as' => 'container.'], function () {
		Route::get('status_badges', [MetaContainerController::class, 'statusBadges']);
		Route::get('vpn_status_badges', [MetaContainerController::class, 'vpnStatusBadges']);
		Route::get('vpn_start_on_boot_statuses', [MetaContainerController::class, 'vpnStartOnBootStatuses']);
		Route::get('samba_status_badges', [MetaContainerController::class, 'sambaStatusBadges']);
		Route::get('nfs_status_badges', [MetaContainerController::class, 'nfsStatusBadges']);
		Route::get('nginx_status_badges', [MetaContainerController::class, 'nginxStatusBadges']);
		Route::get('samba_status_badges', [MetaContainerController::class, 'sambaStatusBadges']);
	});

	Route::group(['prefix' => 'job_tracker', 'as' => 'job_tracker.'], function () {
		Route::get('status_badges', [MetaJobTrackerController::class, 'statusBadges'])->name('status_badges');
	});

	Route::group(['prefix' => 'order', 'as' => 'order.'], function () {
		Route::get('statuses', [MetaOrderController::class, 'statuses']);
		Route::get('status_badges', [MetaOrderController::class, 'statusBadges']);
	});
});*/

Route::group([
	'middleware' => [
		'auth', 
		'verified', 
		'permission:dashboard login',
	], 
	'prefix' => 'dashboard', 
	'as' => 'dashboard.'
], function () {

	Route::get('pay', [PaymentApiController::class, 'pay']);
	Route::get('customer_url', [PaymentApiController::class, 'customerCallback']);

	/*
		Dashboard Index
	*/
	Route::get('/', [DashboardController::class, 'index'])->name('index');

	/*
		Permission Module
	*/
	Route::group(['prefix' => 'permissions', 'as' => 'permissions.'], function () {
		Route::get('check', [PermissionController::class, 'checkPermission'])->name('check');
		Route::get('check_permissions', [PermissionController::class, 'checkPermissions'])->name('check_permissions');
	});

	/*
		Regions Module
	*/
	Route::group(['prefix' => 'regions', 'as' => 'regions.'], function () {
		/* Pages */
		Route::get('/', [RegionController::class, 'index'])->name('index');
		Route::get('create', [RegionController::class, 'create'])->name('create');
		Route::get('{region}/edit', [RegionController::class, 'edit'])->name('edit');
		Route::get('{region}/delete', [RegionController::class, 'delete'])->name('delete');

		/* Actions */
		Route::post('store', [RegionController::class, 'store'])->name('store');
		Route::patch('{region}/update', [RegionController::class, 'update'])->name('update');
		Route::delete('{region}/destroy', [RegionController::class, 'destroy'])->name('destroy');

		/* AJAX Requests */
		Route::get('populate', [RegionController::class, 'populate']);
	});

	/*
		Datacenters Module
	*/
	Route::group(['prefix' => 'datacenters', 'as' => 'datacenters.'], function () {
		/* Pages */
		Route::get('/', [DatacenterController::class, 'index'])->name('index');
		Route::get('create', [DatacenterController::class, 'create'])->name('create');
		Route::get('{datacenter}/edit', [DatacenterController::class, 'edit'])->name('edit');
		Route::get('{datacenter}/delete', [DatacenterController::class, 'delete'])->name('delete');

		/* Actions */
		Route::post('store', [DatacenterController::class, 'store'])->name('store');
		Route::patch('{datacenter}/update', [DatacenterController::class, 'update'])->name('update');
		Route::delete('{datacenter}/destroy', [DatacenterController::class, 'destroy'])->name('destroy');

		/* AJAX Requests */
		Route::post('{datacenter}/switch_status', [DatacenterController::class, 'switchStatus']);

		/* Child Content */
		Route::group(['prefix' => '{datacenter}/subnets', 'as' => 'subnets'], function () {
			Route::get('/', [SubnetController::class, 'datacenterSubnets'])->name('index');
		});

	});

	/*
		Subnets Module
	*/
	Route::group(['prefix' => 'subnets', 'as' => 'subnets.'], function () {
		/* Pages */
		Route::get('/', [SubnetController::class, 'index'])->name('index');
		Route::get('create', [SubnetController::class, 'create'])->name('create');
		Route::get('edit', [SubnetController::class, 'edit'])->name('edit');

		Route::group(['prefix' => '{subnet}/ips', 'as' => 'ips.'], function () {
			/* Pages */
			Route::get('/', [SubnetIpController::class, 'index'])->name('index');
			Route::get('create', [SubnetIpController::class, 'create'])->name('create');
			Route::get('{ip}/edit', [SubnetIpController::class, 'edit'])->name('edit');
			Route::get('{ip}/delete', [SubnetIpController::class, 'delete'])->name('delete');

			/* Actions */
			Route::post('store', [SubnetIpController::class, 'store'])->name('store');
			Route::post('{ip}/switch_forbidden', [SubnetIpController::class, 'switchForbidden'])->name('switch_forbidden');
			Route::patch('{ip}/update', [SubnetIpController::class, 'update'])->name('update');
			Route::delete('{ip}/destroy', [SubnetIpController::class, 'destroy'])->name('destroy');
		});

		/* Actions */
		Route::post('store', [SubnetController::class, 'store'])->name('store');
		Route::patch('update', [SubnetController::class, 'update'])->name('update');
		Route::delete('delete', [SubnetController::class, 'delete'])->name('delete');

		/* AJAX Requests */
		Route::get('populate', [SubnetController::class, 'populate']);
		Route::patch('toggle_status', [SubnetController::class, 'toggleStatus'])->name('toggle_status');
		Route::get('populate_ips', [SubnetController::class, 'populateIps']);
	});

	/*
		Servers Module
	*/
	Route::group(['prefix' => 'servers', 'as' => 'servers.'], function () {
		/* Pages */
		Route::get('/', [ServerController::class, 'index'])->name('index');
		Route::get('create', [ServerController::class, 'create'])->name('create');
		Route::get('containers', [ServerController::class, 'containers'])->name('containers');
		Route::get('edit', [ServerController::class, 'edit'])->name('edit');
		Route::get('confirm_delete', [ServerController::class, 'confirmDelete'])->name('confirm_delete');

		/* Actions */
		Route::post('store', [ServerController::class, 'store'])->name('store');
		Route::patch('update', [ServerController::class, 'update'])->name('update');
		Route::delete('delete', [ServerController::class, 'delete'])->name('delete');

		/* AJAX Requests */
		Route::get('populate', [ServerController::class, 'populate']);
		Route::post('toggle_status', [ServerController::class, 'toggleStatus']);
	});

	/*
		Containers Module
	*/
	Route::group(['prefix' => 'containers', 'as' => 'containers.'], function () {
		/* Pages */
		Route::get('/', [ContainerController::class, 'index'])->name('index');
		Route::get('create', [ContainerController::class, 'create'])->name('create');
		Route::get('{container}/edit', [ContainerController::class, 'edit'])->name('edit');
		Route::get('{container}/view', [ContainerController::class, 'view'])->name('view');
		Route::get('{container}/command_executions', [ContainerController::class, 'commandExecutions'])->name('command_executions');
		Route::get('{container}/delete', [ContainerController::class, 'delete'])->name('delete');

		/* Actions */
		Route::get('load', [ContainerController::class, 'load'])->name('load');
		Route::post('store', [ContainerController::class, 'store'])->name('store');
		Route::patch('{container}/update', [ContainerController::class, 'update'])->name('update');
		Route::delete('{container}/destroy', [ContainerController::class, 'destroy'])->name('destroy');

		/* AJAX Requests */
		Route::get('populate', [ContainerController::class, 'populate']);
		Route::post('install_system', [ContainerController::class, 'install_system']);

		Route::group(['prefix' => '{container}/vpn', 'as' => 'vpn.'], function () {
			Route::get('manage', [ContainerVpnController::class, 'manage'])->name('manage');
			Route::get('input_create_user', [ContainerVpnController::class, 'inputCreateUser'])->name('input_create_user');
			Route::get('{vpn_user}/confirm_revoke_user', [ContainerVpnController::class, 'confirmRevokeUser'])->name('confirm_revoke_user');

			Route::get('check_pid_numbers', [ContainerVpnController::class, 'checkPidNumbers']);
			Route::get('check_status', [ContainerVpnController::class, 'checkStatus']);
			Route::get('download_config', [ContainerVpnController::class, 'downloadConfig']);
			Route::post('create_user', [ContainerVpnController::class, 'createUser'])->name('create_user');
			Route::post('{vpn_user}/revoke_user', [ContainerVpnController::class, 'revokeUser'])->name('revoke_user');
			Route::post('start', [ContainerVpnController::class, 'start']);
			Route::post('restart', [ContainerVpnController::class, 'restart']);
			Route::post('stop', [ContainerVpnController::class, 'stop']);
			Route::post('toggle_start_on_boot', [ContainerVpnController::class, 'toggleStartOnBoot']);
		});

		Route::group(['prefix' => '{container}/samba', 'as' => 'samba.'], function () {
			Route::get('check_status', [ContainerSambaController::class, 'checkStatus']);
			Route::get('check_pid_numbers', [ContainerSambaController::class, 'checkPidNumbers']);
			Route::post('start', [ContainerSambaController::class, 'start']);
			Route::post('restart', [ContainerSambaController::class, 'restart']);
			Route::post('stop', [ContainerSambaController::class, 'stop']);
			Route::post('toggle_start_on_boot', [ContainerSambaController::class, 'toggleStartOnBoot']);
		});

		Route::group(['prefix' => '{container}/nfs', 'as' => 'nfs.'], function () {
			Route::get('manage', [ContainerNfsController::class, 'manage'])->name('manage');

			Route::group(['prefix' => 'exports'], function () {
				Route::post('create', [ContainerNfsController::class, 'create'])->name('create');
				Route::post('store', [ContainerNfsController::class, 'store'])->name('store');
				Route::post('edit', [ContainerNfsController::class, 'edit'])->name('edit');
				Route::post('update', [ContainerNfsController::class, 'update'])->name('update');
				Route::post('delete', [ContainerNfsController::class, 'delete'])->name('delete');
				Route::post('destroy', [ContainerNfsController::class, 'destroy'])->name('destroy');
			});

			Route::get('check_status', [ContainerNfsController::class, 'checkStatus']);
			Route::get('check_pid_numbers', [ContainerNfsController::class, 'checkPidNumbers']);
			Route::post('start', [ContainerNfsController::class, 'start']);
			Route::post('restart', [ContainerNfsController::class, 'restart']);
			Route::post('stop', [ContainerNfsController::class, 'stop']);
			Route::post('toggle_start_on_boot', [ContainerNfsController::class, 'toggleStartOnBoot']);
		});

		Route::group(['prefix' => '{container}/nginx', 'as' => 'nginx.'], function () {
			Route::get('check_status', [ContainerNginxController::class, 'checkStatus']);
			Route::get('check_pid_numbers', [ContainerNginxController::class, 'checkPidNumbers']);
			Route::post('create_location', [ContainerNginxController::class, 'createLocation']);
			Route::post('remove_location', [ContainerNginxController::class, 'removeLocation']);
			Route::post('start', [ContainerNginxController::class, 'start']);
			Route::post('reload', [ContainerNginxController::class, 'reload']);
			Route::post('restart', [ContainerNginxController::class, 'restart']);
			Route::post('stop', [ContainerNginxController::class, 'stop']);
		});
	});

	/*
		Job Tracker
	*/
	Route::group(['prefix' => 'job_trackers', 'as' => 'job_trackers.'], function () {
		/* Pages */
		// Route::get('/', [JobTrackerController::class, 'index'])->name('index');

		/* Actions */
		// Route::post('clear', [JobTrackerController::class, 'clear'])->name('clear');

		/* AJAX Request */
		// Route::get('populate', [JobTrackerController::class, 'populate'])->name('populate');
	});

	/*
		Orders Module
	*/
	Route::group(['prefix' => 'orders', 'as' => 'orders.'], function () {
		/* Pages */
		Route::get('/', [OrderController::class, 'index'])->name('index');
		Route::get('create', [OrderController::class, 'create'])->name('create');
		Route::get('{order}/edit', [OrderController::class, 'edit'])->name('edit');
		Route::get('{order}/view', [OrderController::class, 'view'])->name('view');
		Route::get('{order}/delete', [OrderController::class, 'delete'])->name('delete');

		/* Actions */
		Route::post('store', [OrderController::class, 'store'])->name('store');
		Route::get('{order}/manually_create_container', [OrderController::class, 'manuallyCreateContainer']);
		Route::patch('{order}/update', [OrderController::class, 'update'])->name('update');
		Route::delete('{order}/destroy', [OrderController::class, 'destroy'])->name('destroy');

		/* AJAX Requests */
		Route::get('{order}/load', [OrderController::class, 'load']);
	});

	/*
		Payments Module
	*/
	Route::group(['prefix' => 'payments', 'as' => 'payments.'], function () {
		/* Pages */
		Route::get('/', [PaymentController::class, 'index'])->name('index');
		Route::get('view', [PaymentController::class, 'view'])->name('view');

		Route::get('pay', [PaymentController::class, 'pay'])->name('pay');

		/* AJAX Requests */
		Route::get('populate', [PaymentController::class, 'populate'])->name('populate');
		Route::get('load', [PaymentController::class, 'load'])->name('load');
	});

	/*
		Service Plans Module
	*/
	Route::group(['prefix' => 'service_plans', 'as' => 'service_plans.'], function () {
		/* Pages */
		Route::get('/', [ServicePlanController::class, 'index'])->name('index');
		Route::get('create', [ServicePlanController::class, 'create'])->name('create');
		Route::get('view', [ServicePlanController::class, 'view'])->name('view');
		Route::get('edit', [ServicePlanController::class, 'edit'])->name('edit');
		Route::get('confirm_delete', [ServicePlanController::class, 'confirmDelete'])->name('confirmDelete');

		/* Actions */
		Route::post('store', [ServicePlanController::class, 'store'])->name('store');
		Route::patch('update', [ServicePlanController::class, 'update'])->name('update');
		Route::delete('delete', [ServicePlanController::class, 'delete'])->name('delete');

		/* AJAX Requests */
		Route::get('populate', [ServicePlanController::class, 'populate'])->name('populate');
		Route::get('orders', [ServicePlanController::class, 'orders'])->name('orders');
		Route::post('change_status', [ServicePlanController::class, 'changeStatus'])->name('change_status');
	});

	/*
		Service Addons Module
	*/
	Route::group(['prefix' => 'service_addons', 'as' => 'service_addons.'], function () {
		/* Pages */
		Route::get('/', [ServiceAddonController::class, 'index'])->name('index');
		Route::get('create', [ServiceAddonController::class, 'create'])->name('create');
		Route::get('edit', [ServiceAddonController::class, 'edit'])->name('edit');
		Route::get('confirm_delete', [ServiceAddonController::class, 'confirmDelete'])->name('confirm_delete');

		/* Actions */
		Route::post('store', [ServiceAddonController::class, 'store'])->name('store');
		Route::patch('update', [ServiceAddonController::class, 'update'])->name('update');
		Route::delete('delete', [ServiceAddonController::class, 'delete'])->name('delete');

		/* AJAX Requests */
		Route::get('populate', [ServiceAddonController::class, 'populate'])->name('populate');
	});

	/*
		Pricings Module
	*/
	Route::group(['prefix' => 'pricings', 'as' => 'pricings.'], function () {
		/* Pages */
		Route::get('create', [PricingController::class, 'create'])->name('create');
		Route::get('edit', [PricingController::class, 'edit'])->name('edit');
		Route::get('confirm_delete', [PricingController::class, 'confirmDelete'])->name('confirm_delete');

		/* Actions */
		Route::post('store', [PricingController::class, 'store'])->name('store');
		Route::post('update', [PricingController::class, 'update'])->name('update');
		Route::delete('delete', [PricingController::class, 'delete'])->name('delete');
	});

	/*
		Users Module
	*/
	Route::group(['prefix' => 'users', 'as' => 'users.'], function () {
		/* Pages */
		Route::get('/', [UserController::class, 'index'])->name('index');
		Route::get('create', [UserController::class, 'create'])->name('create');
		Route::get('view', [UserController::class, 'view'])->name('view');
		Route::get('confirm_delete', [UserController::class, 'confirmDelete'])->name('confirm_delete');

		/* Actions */
		Route::post('store', [UserController::class, 'store'])->name('store');
		Route::patch('update_profile', [UserController::class, 'updateProfile'])->name('update_profile');
		Route::patch('update_account', [UserController::class, 'updateAccount'])->name('update_account');
		Route::patch('update_password', [UserController::class, 'updatePassword'])->name('update_password');
		Route::patch('update_newsletter', [UserController::class, 'updateNewsletter'])->name('update_newsletter');
		Route::patch('update_permissions', [UserController::class, 'updatePermissions'])->name('update_permissions');
		Route::delete('delete', [UserController::class, 'delete'])->name('delete');

		/* AJAX Requests */
		Route::get('populate', [UserController::class, 'populate']);
		Route::get('populate_all', [UserController::class, 'populateAll']);
	});

	/*
		Administrator Module
	*/
	Route::group(['prefix' => 'administrators', 'as' => 'administrators.'], function () {
		/* Pages */
		Route::get('/', [AdministratorController::class, 'index'])->name('index');
		Route::get('view', [AdministratorController::class, 'view'])->name('view');
		Route::get('demote_administrator', [AdministratorController::class, 'demoteAdministrator'])->name('demote_administrator');
		Route::get('add_users', [AdministratorController::class, 'addUsers'])->name('add_users');

		/* Actions */
		Route::patch('promotes', [AdministratorController::class, 'promoteUsers'])->name('promotes');
		Route::patch('demote', [AdministratorController::class, 'demoteAdministrator'])->name('demote');

		/* AJAX Requests */
		Route::get('populate', [AdministratorController::class, 'populate']);
	});

	/*
		Newsletter Module
	*/
	Route::group(['prefix' => 'newsletters', 'as' => 'newsletters.'], function () {
		/* Pages */
		Route::get('/', [NewsletterController::class, 'index'])->name('index');
		Route::get('create', [NewsletterController::class, 'create'])->name('create');
		Route::get('view', [NewsletterController::class, 'view'])->name('view');
		Route::get('confirm_delete', [NewsletterController::class, 'confirmDelete'])->name('confirm_delete');

		/* Actions */
		Route::post('store', [NewsletterController::class, 'store'])->name('store');
		Route::match(['PUT', 'PATCH'], 'update', [NewsletterController::class, 'update'])->name('update');
		Route::delete('delete', [NewsletterController::class, 'delete'])->name('delete');

		/* AJAX Requests */
		Route::get('populate', [NewsletterController::class, 'populate']);
	});

	/*
		Setting Module
	*/
	Route::group(['prefix' => 'settings', 'as' => 'settings.'], function () {
		/* Pages */
		Route::get('/', [SettingController::class, 'index'])->name('index');

		/* Actions */
		Route::patch('save_site_settings', [SettingController::class, 'saveSiteSettings'])->name('save_site_settings');
		Route::group(['prefix' => 'rabbitmq', 'as' => 'rabbitmq.'], function () {
			Route::patch('save_option', [SettingController::class, 'saveRabbitMQOption'])->name('save_option');
			Route::patch('save_configs', [SettingController::class, 'saveRabbitMQConfigs'])->name('save_configs');
		});
	});

	/*
		Activity Log Module
	*/
	Route::group(['prefix' => 'activity_logs', 'as' => 'activity_logs.'], function () {
		/* AJAX Requests */
		Route::get('populate', [ActivityLogController::class, 'populate']);
	});
});