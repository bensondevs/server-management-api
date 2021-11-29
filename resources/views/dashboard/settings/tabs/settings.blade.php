<!--begin::Tab-->
<div class="tab-pane px-7 active" id="settings_tab" role="tabpanel">
	<!--begin::Row-->
	<div class="row">
		<div class="col-xl-2"></div>
		<div class="col-xl-7">
			<div class="my-2">
				<form id="save_site_settings_form" method="POST" action="{{ route('dashboard.settings.save_site_settings') }}">
					@csrf
					@method('PATCH')

					<!--begin::Row-->
					<div class="row">
						<label class="col-form-label col-3 text-lg-right text-left"></label>
						<div class="col-9">
							<h6 class="text-dark font-weight-bold mb-10">Site Settings:</h6>
						</div>
					</div>
					<!--end::Row-->
					<!--begin::Group-->
					<div class="form-group row">
						<label class="col-form-label col-3 text-lg-right text-left">Site Name: </label>
						<div class="col-9">
							<input 
								class="form-control form-control-lg form-control-solid" 
								type="text" 
								name="site_name" 
								value="{{ $settings['site_name'] }}" />
						</div>
					</div>
					<!--end::Group-->
					<!--begin::Group-->
					<div class="form-group row">
						<label class="col-form-label col-3 text-lg-right text-left">Site Title: </label>
						<div class="col-9">
							<input 
								class="form-control form-control-lg form-control-solid" 
								type="text" 
								name="site_title" 
								value="{{ $settings['site_title'] }}" />
						</div>
					</div>
					<!--end::Group-->
					<!--begin::Group-->
					<div class="form-group row">
						<label class="col-form-label col-3 text-lg-right text-left">Site Description: </label>
						<div class="col-9">
							<textarea 
								class="form-control form-control-lg form-control-solid" 
								rows="20"
								name="description" 
								type="text">{{ $settings['description'] }}</textarea>
						</div>
					</div>
					<!--end::Group-->
				</form>
			</div>
		</div>
	</div>
	<!--end::Row-->

	<!--begin::Row-->
    <div class="row">
        <div class="col-xl-2"></div>
        <div class="col-xl-7">
            <div class="row">
                <div class="col-3"></div>
                <div class="col-9">
                    <button 
                        type="submit" 
                        form="save_site_settings_form" 
                        class="btn btn-light-primary font-weight-bold">
                        Save changes
                    </button>
                    <a href="#" class="btn btn-clean font-weight-bold">Cancel</a>
                </div>
            </div>
        </div>
    </div>
    <!--end::Row-->
</div>
<!--end::Tab-->