<!--begin::Tab-->
<div class="tab-pane px-7" id="server_tab" role="tabpanel">
	<div class="row">
		<div class="col-xl-2"></div>
		<div class="col-xl-8">
			<div class="my-2">
				<div class="row">
					<label class="col-form-label col-3 text-lg-right text-left"></label>
					<div class="col-9">
						<h6 class="text-dark font-weight-bold mb-7">RabbitMQ Option Settings:</h6>
					</div>
				</div>

				<form id="save_rabbitmq_option_form" method="POST" action="{{ route('dashboard.settings.rabbitmq.save_option') }}">
					@csrf
					@method('PATCH')

					<div class="form-group row mb-2">
						<label class="col-form-label col-3 text-lg-right text-left">
							Connect Use
						</label>
						<div class="col-9 col-form-label">
							<div class="radio-inline">
								<label class="radio">
									<input 
										type="radio" 
										@if ($settings['config_rabbitmq_connection'] == 'env') 
											checked="checked" 
										@endif
										value="env" 
										name="config_rabbitmq_connection">
									<span></span>
									.env
								</label>

								<label class="radio">
									<input 
										type="radio" 
										@if ($settings['config_rabbitmq_connection'] == 'database') 
											checked="checked" 
										@endif 
										value="database" 
										name="config_rabbitmq_connection">
									<span></span>
									Database
								</label>
							</div>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-form-label col-3 text-lg-right text-left">
							Auto Sync DB to Queue
						</label>
						<div class="col-3">
							<span class="switch">
								<label>
									<input 
										type="checkbox" 
										name="autosync_rabbitmq" 
										@if ($settings['autosync_rabbitmq'] == 'on')
											checked="true" 
										@endif>
									<span></span>
								</label>
							</span>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>

	<!--begin::Footer-->
	<div class="card-footer pb-0">
		<div class="row">
			<div class="col-xl-2"></div>
			<div class="col-xl-7">
				<div class="row">
					<div class="col-3"></div>
					<div class="col-9">
						<button 
							form="save_rabbitmq_option_form" 
							type="submit" 
							href="#" 
							class="btn btn-light-primary font-weight-bold">
							Save changes
						</button>
						<a href="#" class="btn btn-clean font-weight-bold">Cancel</a>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!--end::Footer-->

	@if ($settings['config_rabbitmq_connection'] == 'database')
		<div class="separator separator-dashed my-10"></div>

		<!--begin::Row-->
		<div class="row">
			<div class="col-xl-2"></div>
			<div class="col-xl-7">
				<!--begin::Row-->
				<div class="row">
					<label class="col-3"></label>
					<div class="col-9">
						<h6 class="text-dark font-weight-bold mb-10">Rabbit MQ Configuration:</h6>
					</div>
				</div>
				<!--end::Row-->

				<!--begin::Row-->
				<div class="row mb-5">
					<label class="col-3"></label>
					<div class="col-9">
						<div class="alert alert-custom alert-light-danger fade show py-4" role="alert">
							<div class="alert-icon">
								<i class="flaticon-warning"></i>
							</div>
							<div class="alert-text font-weight-bold">Becareful in changing your configuration
							<br>Wrong configuration may cause some of the features will not work the way they should be!</div>
							<div class="alert-close">
								<button type="button" class="close" data-dismiss="alert" aria-label="Close">
									<span aria-hidden="true">
										<i class="la la-close"></i>
									</span>
								</button>
							</div>
						</div>
					</div>
				</div>
				<!--end::Row-->

				<form id="save_site_server_form" method="POST" action="{{ route('dashboard.settings.rabbitmq.save_configs') }}">
					@csrf
					@method('PATCH')

					<!--begin::Group-->
					<div class="form-group row">
						<label class="col-form-label col-3 text-lg-right text-left">
							Host
						</label>
						<div class="col-9">
							<input 
								class="form-control form-control-lg form-control-solid mb-1" 
								type="text" 
								name="rabbitmq_host" 
								value="{{ $settings['rabbitmq_host'] }}">
						</div>
					</div>
					<!--end::Group-->

					<!--begin::Group-->
					<div class="form-group row">
						<label class="col-form-label col-3 text-lg-right text-left">
							Port
						</label>
						<div class="col-9">
							<input 
								class="form-control form-control-lg form-control-solid" 
								type="number" 
								name="rabbitmq_port" 
								value="{{ $settings['rabbitmq_port'] }}">
						</div>
					</div>
					<!--end::Group-->

					<!--begin::Group-->
					<div class="form-group row">
						<label class="col-form-label col-3 text-lg-right text-left">
							Username
						</label>
						<div class="col-9">
							<input 
								class="form-control form-control-lg form-control-solid" 
								type="text" 
								name="rabbitmq_user" 
								value="{{ $settings['rabbitmq_user'] }}">
						</div>
					</div>
					<!--end::Group-->

					<!--begin::Group-->
					<div class="form-group row">
						<label class="col-form-label col-3 text-lg-right text-left">
							Password
						</label>
						<div class="col-9">
							<input 
								class="form-control form-control-lg form-control-solid" 
								type="text" 
								name="rabbitmq_password" 
								value="{{ $settings['rabbitmq_password'] }}">
						</div>
					</div>
					<!--end::Group-->

					<!--begin::Group-->
					<div class="form-group row">
						<label class="col-form-label col-3 text-lg-right text-left">
							Virtual Host
						</label>
						<div class="col-9">
							<input 
								class="form-control form-control-lg form-control-solid" 
								type="text" 
								name="rabbitmq_vhost" 
								value="{{ $settings['rabbitmq_vhost'] }}">
						</div>
					</div>
					<!--end::Group-->

					<!--begin::Group-->
					<div class="form-group row">
						<label class="col-form-label col-3 text-lg-right text-left">
							API Base URL
						</label>
						<div class="col-9">
							<input 
								class="form-control form-control-lg form-control-solid" 
								type="text" 
								name="rabbitmq_api_base_url" 
								value="{{ $settings['rabbitmq_api_base_url'] }}">
						</div>
					</div>
					<!--end::Group-->
				</form>
			</div>
		</div>
		<!--end::Row-->

		<!--begin::Footer-->
		<div class="card-footer pb-0">
			<div class="row">
				<div class="col-xl-2"></div>
				<div class="col-xl-7">
					<div class="row">
						<div class="col-3"></div>
						<div class="col-9">
							<button 
								form="save_site_server_form" 
								type="submit" 
								href="#" 
								class="btn btn-light-primary font-weight-bold">
								Save changes
							</button>
							<a href="#" class="btn btn-clean font-weight-bold">Cancel</a>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!--end::Footer-->
	@endif
</div>
<!--end::Tab-->