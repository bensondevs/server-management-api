<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\{
	AuthController,
	UserController,
	OrderController,
	ContainerController,
		Container\Nfs\ContainerNfsController as NfsController,
			Container\Nfs\ContainerNfsFolderController as NfsFolderController,
			Container\Nfs\ContainerNfsExportController as NfsExportController,
		Container\Nginx\ContainerNginxController as NginxController,
			Container\Nginx\ContainerNginxLocationController as NginxLocationController,
		Container\Samba\ContainerSambaController as SambaController,
			// Container\Samba\ContainerSambaDirectoryController as SambaDirectoryController,
			Container\Samba\ContainerSambaShareController as SambaShareController,
			Container\Samba\ContainerSambaGroupController as SambaGroupController,
			Container\Samba\ContainerSambaUserController as SambaUserController,
		Container\Vpn\ContainerVpnController as VpnController,
			Container\Vpn\ContainerVpnUserController as VpnUserController,
			Container\Vpn\ContainerVpnSubnetController as VpnSubnetController,
	RegionController,
	DatacenterController,
	CartController,
	Payments\PaymentController,
		Payments\SebPaymentController,
	ServerController,
	ServicePlanController,
	SubscriptionController
};

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['as' => 'api.'], function () {
	/**
	 * API Callbacks Module
	 */
	Route::group(['prefix' => 'callbacks'], function () {
		/**
		 * SEB Payment callback
		 */
		Route::get('payments/seb', [SebPaymentController::class, 'callback']);
	});

	/**
	 * Auth API Module
	 */
	Route::group(['prefix' => 'auth', 'as' => 'auth.'], function () {
		Route::post('login', [AuthController::class, 'login'])->name('login');
		Route::post('register', [AuthController::class, 'register'])->name('register');
		Route::post('logout', [AuthController::class, 'logout'])->name('logout');
	});

	/**
	 * Service Plan Module
	 */
	Route::group(['prefix' => 'service_plans', 'as' => 'service_plans.'], function () {
		Route::get('/', [ServicePlanController::class, 'servicePlans']);
		Route::get('/{plan}', [ServicePlanController::class, 'show']);
	});

	/**
	 * Authenticated and Verified Groups
	 */
	Route::group(['middleware' => ['auth:sanctum', 'verified']], function () {
		/**
		 * Region API Module
		 */
		Route::group(['prefix' => '/regions'], function () {
			Route::get('/', [RegionController::class, 'regions']);
		});

		/**
		 * Datacenter API Module
		 */
		Route::group(['prefix' => '/datacenters'], function () {
			Route::get('/', [DatacenterController::class, 'datacenters']);
		});

		/**
		 * Server API Module
		 */
		Route::group(['prefix' => '/servers'], function () {
			Route::get('/', [ServerController::class, 'servers']);
		});

		/**
		 * Cart API Module
		 */
		Route::group(['prefix' => '/carts'], function () {
			Route::get('/', [CartController::class, 'carts']);
			
			Route::post('/add_plan/{plan}', [CartController::class, 'addServicePlan'])
				->middleware('one_time_free_plan');
			Route::group(['prefix' => '/{cart}'], function () {
				Route::get('/items', [CartController::class, 'cartItems']);
				Route::post('/add_addon/{addon}', [CartController::class, 'addServiceAddon']);
				Route::delete('/destroy', [CartController::class, 'destroy']);
			});
			
			Route::group(['prefix' => '/items/{cart_item}'], function () {
				Route::match(['PUT', 'PATCH'], 'set_quantity', [CartController::class, 'setItemQuantity']);
				Route::delete('remove', [CartController::class, 'removeItem']);
			});

			Route::post('checkout', [CartController::class, 'checkout']);
		});

		/**
		 * Order API Module
		 */
		Route::group(['prefix' => '/orders'], function () {
			Route::get('/', [OrderController::class, 'orders']);
			Route::get('/{order}', [OrderController::class, 'show']);
		});

		/**
		 * Container service module
		 */
		Route::group(['prefix' => '/containers'], function () {
			Route::get('/', [ContainerController::class, 'userContainers']);
			Route::get('/exists', [ContainerController::class, 'exists']);
			Route::get('/current', [ContainerController::class, 'current']);

			/**
			 * Container API main content
			 */
			Route::group(['prefix' => '/{container}'], function () {
				Route::get('/', [ContainerController::class, 'show']);
				Route::post('/select', [ContainerController::class, 'select']);

				/**
				 * Container NFS Service
				 */
				Route::group(['prefix' => '/nfs'], function () {
					Route::get('/', [NfsController::class, 'containerNfs']);

					/**
					 * Action to NFS Service
					 */
					Route::post('/start', [NfsController::class, 'start']);
					Route::post('/restart', [NfsController::class, 'restart']);
					Route::post('/reload', [NfsController::class, 'reload']);
					Route::post('/stop', [NfsController::class, 'stop']);
					Route::post('/enable', [NfsController::class, 'enable']);
					Route::post('/disable', [NfsController::class, 'disable']);

					/**
					 * Container Folders Module
					 */
					Route::group(['prefix' => '/folders'], function () {
						Route::get('/', [NfsFolderController::class, 'nfsFolders']);
						Route::post('/create', [NfsFolderController::class, 'create']);
						Route::get('/{folder}/show', [NfsFolderController::class, 'show']);
						Route::match(['PUT', 'PATCH'], '/{folder}/update', [NfsFolderController::class, 'update']);
						Route::delete('/{folder}/delete', [NfsFolderController::class, 'delete']);
					});

					/**
					 * Container Exports Module
					 */
					Route::group(['prefix' => '/exports'], function () {
						Route::get('/', [NfsExportController::class, 'nfsExports']);
						Route::post('/create', [NfsExportController::class, 'create']);
						Route::get('/{export}/show', [NfsExportController::class, 'show']);
						Route::match(['PUT', 'PATCH'], '/{export}/update', [NfsExportController::class, 'update']);
						Route::delete('/{export}/delete', [NfsExportController::class, 'delete']);
					});
				});

				/**
				 * Container Nginx Service
				 */
				Route::group(['prefix' => '/nginx'], function () {
					Route::get('/', [NginxController::class, 'containerNginx']);
					Route::post('/start', [NginxController::class, 'start']);
					Route::post('/restart', [NginxController::class, 'restart']);
					Route::post('/reload', [NginxController::class, 'reload']);
					Route::post('/stop', [NginxController::class, 'stop']);
					Route::post('/enable', [NginxController::class, 'enable']);
					Route::post('/disable', [NginxController::class, 'disable']);

					Route::group(['prefix' => '/locations'], function () {
						Route::get('/', [NginxLocationController::class, 'nginxLocations']);
						Route::post('/create', [NginxLocationController::class, 'create']);
						Route::delete('/{location}/remove', [NginxLocationController::class, 'remove']);
					});
				});

				/**
				 * Container Samba Service
				 */
				Route::group(['prefix' => '/samba'], function () {
					Route::get('/', [SambaController::class, 'containerSamba']);

					/**
					 * Actions to Samba Service
					 */
					Route::post('/start', [SambaController::class, 'start']);
					Route::post('/restart', [SambaController::class, 'restart']);
					Route::post('/reload', [SambaController::class, 'reload']);
					Route::post('/stop', [SambaController::class, 'stop']);
					Route::post('/enable', [SambaController::class, 'enable']);
					Route::post('/disable', [SambaController::class, 'disable']);

					/**
					 * Samba Share Modules
					 */
					Route::group(['prefix' => '/shares'], function () {
						Route::get('/', [SambaShareController::class, 'sambaShares']);
						Route::post('/create', [SambaShareController::class, 'create']);
						Route::group(['prefix' => '/{share}'], function () {
							Route::get('/show', [SambaShareController::class, 'show']);
							Route::match(['PUT', 'PATCH'], 'update', [SambaShareController::class, 'update']);
							Route::delete('delete', [SambaShareController::class, 'delete']);
						});

						/**
						 * Samba Share Group
						 */
						Route::group(['prefix' => '/{share}/groups'], function () {
							Route::get('/', [SambaShareController::class, 'shareGroups']);
							Route::post('add', [SambaShareController::class, 'addShareGroup']);
							Route::delete('remove', [SambaShareController::class, 'removeShareGroup']);
						});

						/**
						 * Samba Share User
						 */
						Route::group(['prefix' => 'users'], function () {
							Route::get('/', [SambaShareController::class, 'shareUsers']);
							Route::post('add', [SambaShareController::class, 'addUser']);
							Route::delete('remove', [SambaShareController::class, 'removeUser']);
						});
					});

					/**
					 * Samba Groups
					 */
					Route::group(['prefix' => '/groups'], function () {
						Route::get('/', [SambaGroupController::class, 'sambaGroups']);
						Route::get('/{group}/show', [SambaGroupController::class, 'show']);

						/**
						 * Samba Group Users
						 */
						Route::group(['prefix' => '/{group}/users'], function () {
							Route::get('/', [SambaGroupController::class, 'groupUsers']);
							Route::post('add', [SambaGroupController::class, 'addUser']);
							Route::delete('remove', [SambaGroupController::class, 'removeUser']);
						});
					});

					/**
					 * Samba User
					 */
					Route::group(['prefix' => '/users'], function () {
						Route::get('/', [SambaUserController::class, 'sambaUsers']);
						Route::post('create', [SambaUserController::class, 'create']);
						Route::match(['PUT', 'PATCH'], 'update_password', [SambaUserController::class, 'updatePassword']);
						Route::delete('delete', [SambaUserController::class, 'delete']);
					});
				});

				/**
				 * Container VPN Service
				 */
				Route::group(['prefix' => '/vpn'], function () {
					Route::get('/', [VpnController::class, 'containerVpn']);

					Route::post('/start', [VpnController::class, 'start']);
					Route::post('/reload', [VpnController::class, 'reload']);
					Route::post('/restart', [VpnController::class, 'restart']);
					Route::post('/stop', [VpnController::class, 'stop']);
					Route::post('/enable', [VpnController::class, 'enable']);
					Route::post('/disable', [VpnController::class, 'disable']);

					/**
					 * Container VPN Subnet Module
					 */
					Route::group(['prefix' => '/subnet'], function () {
						Route::get('ips', [VpnSubnetController::class, 'ips']);
						Route::post('change', [VpnSubnetController::class, 'change']);
					});

					/**
					 * Container VPN Users Module
					 */
					Route::group(['prefix' => '/users'], function () {
						Route::get('/', [VpnUserController::class, 'vpnUsers']);
						Route::post('create', [VpnUserController::class, 'create']);

						Route::group(['prefix' => '/{user}'], function () {
							Route::get('/', [VpnUserController::class, 'show']);
							Route::delete('revoke', [VpnUserController::class, 'revoke']);
							// Route::post('change_subnet_ip', [VpnUserController::class, 'change_subnet_ip']);
							Route::get('download_config', [VpnUserController::class, 'downloadConfig']);
						});
					});
				});
			});
		});

		/**
		 *	Payment API Module
		 */
		Route::group(['prefix' => '/payments'], function () {
			Route::get('/', [PaymentController::class, 'payments']);
			Route::post('/create/{order}', [PaymentController::class, 'create']);

			/**
			 * Payment main API Module
			 */
			Route::group(['prefix' => '/{payment}'], function () {
				Route::get('/', [PaymentController::class, 'show']);
				Route::post('/report', [PaymentController::class, 'report']);
			});

			/**
			 * SEB Payment API Module
			 */
			Route::group(['prefix' => '/seb/{seb}'], function () {
				Route::get('/', [SebPaymentController::class, 'show']);
				Route::post('/pay', [SebPaymentController::class, 'pay']);
				Route::get('/check', [SebPaymentController::class, 'check']);
			});
		});

		/**
		 * Subscription API Module
		 */
		Route::group(['prefix' => '/subscriptions'], function () {
			Route::get('/', [SubscriptionController::class, 'subscriptions']);

			Route::group(['prefix' => '{subscription}'], function () {
				Route::get('/', [SubscriptionController::class, 'show']);
				Route::post('/renew', [SubscriptionController::class, 'renew']);
			});
			Route::post('/renew_multiple', [SubscriptionController::class, 'renewMultiple']);
		});

		/**
		 * User API Module
		 */
		Route::group(['prefix' => 'users', 'as' => 'users.'], function () {
			Route::get('current', [UserController::class, 'current'])->name('current');
		});
	});
});
