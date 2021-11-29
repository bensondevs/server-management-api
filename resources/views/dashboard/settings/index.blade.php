@extends('layouts.app')

@section('content')
<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
	<!--begin::Entry-->
	<div class="d-flex flex-column-fluid">
		<!--begin::Container-->
		<div class="container">
			<!--begin::Card-->
			<div class="card card-custom">
				<!--begin::Card header-->
				<div class="card-header card-header-tabs-line nav-tabs-line-3x">
					<!--begin::Toolbar-->
					<div class="card-toolbar">
						<ul class="nav nav-tabs nav-bold nav-tabs-line nav-tabs-line-3x">
							<!--begin::Item-->
							<li class="nav-item mr-3">
								<a class="nav-link active" data-toggle="tab" href="#settings_tab">
									<span class="nav-icon">
										<span class="svg-icon"><!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2021-04-03-014522/theme/html/demo1/dist/../src/media/svg/icons/General/Settings-2.svg-->
											<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
											    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
											        <rect x="0" y="0" width="24" height="24"/>
											        <path d="M5,8.6862915 L5,5 L8.6862915,5 L11.5857864,2.10050506 L14.4852814,5 L19,5 L19,9.51471863 L21.4852814,12 L19,14.4852814 L19,19 L14.4852814,19 L11.5857864,21.8994949 L8.6862915,19 L5,19 L5,15.3137085 L1.6862915,12 L5,8.6862915 Z M12,15 C13.6568542,15 15,13.6568542 15,12 C15,10.3431458 13.6568542,9 12,9 C10.3431458,9 9,10.3431458 9,12 C9,13.6568542 10.3431458,15 12,15 Z" fill="#000000"/>
											    </g>
											</svg><!--end::Svg Icon-->
										</span>
									</span>
									<span class="nav-text font-size-lg">Settings</span>
								</a>
							</li>
							<!--end::Item-->
							<!--begin::Item-->
							<li class="nav-item mr-3">
								<a class="nav-link" data-toggle="tab" href="#server_tab">
									<span class="nav-icon">
										<span class="svg-icon"><!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2021-04-03-014522/theme/html/demo1/dist/../src/media/svg/icons/Devices/Server.svg-->
											<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
											    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
											        <rect x="0" y="0" width="24" height="24"/>
											        <path d="M5,2 L19,2 C20.1045695,2 21,2.8954305 21,4 L21,6 C21,7.1045695 20.1045695,8 19,8 L5,8 C3.8954305,8 3,7.1045695 3,6 L3,4 C3,2.8954305 3.8954305,2 5,2 Z M11,4 C10.4477153,4 10,4.44771525 10,5 C10,5.55228475 10.4477153,6 11,6 L16,6 C16.5522847,6 17,5.55228475 17,5 C17,4.44771525 16.5522847,4 16,4 L11,4 Z M7,6 C7.55228475,6 8,5.55228475 8,5 C8,4.44771525 7.55228475,4 7,4 C6.44771525,4 6,4.44771525 6,5 C6,5.55228475 6.44771525,6 7,6 Z" fill="#000000" opacity="0.3"/>
											        <path d="M5,9 L19,9 C20.1045695,9 21,9.8954305 21,11 L21,13 C21,14.1045695 20.1045695,15 19,15 L5,15 C3.8954305,15 3,14.1045695 3,13 L3,11 C3,9.8954305 3.8954305,9 5,9 Z M11,11 C10.4477153,11 10,11.4477153 10,12 C10,12.5522847 10.4477153,13 11,13 L16,13 C16.5522847,13 17,12.5522847 17,12 C17,11.4477153 16.5522847,11 16,11 L11,11 Z M7,13 C7.55228475,13 8,12.5522847 8,12 C8,11.4477153 7.55228475,11 7,11 C6.44771525,11 6,11.4477153 6,12 C6,12.5522847 6.44771525,13 7,13 Z" fill="#000000"/>
											        <path d="M5,16 L19,16 C20.1045695,16 21,16.8954305 21,18 L21,20 C21,21.1045695 20.1045695,22 19,22 L5,22 C3.8954305,22 3,21.1045695 3,20 L3,18 C3,16.8954305 3.8954305,16 5,16 Z M11,18 C10.4477153,18 10,18.4477153 10,19 C10,19.5522847 10.4477153,20 11,20 L16,20 C16.5522847,20 17,19.5522847 17,19 C17,18.4477153 16.5522847,18 16,18 L11,18 Z M7,20 C7.55228475,20 8,19.5522847 8,19 C8,18.4477153 7.55228475,18 7,18 C6.44771525,18 6,18.4477153 6,19 C6,19.5522847 6.44771525,20 7,20 Z" fill="#000000"/>
											    </g>
											</svg><!--end::Svg Icon-->
										</span>
									</span>
									<span class="nav-text font-size-lg">Server</span>
								</a>
							</li>
							<!--end::Item-->
							<!--begin::Item-->
							<li class="nav-item mr-3">
								<a class="nav-link" data-toggle="tab" href="#activities_tab">
									<span class="nav-icon">
										<span class="svg-icon"><!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2021-04-03-014522/theme/html/demo1/dist/../src/media/svg/icons/General/Notifications1.svg-->
											<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
											    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
											        <path d="M17,12 L18.5,12 C19.3284271,12 20,12.6715729 20,13.5 C20,14.3284271 19.3284271,15 18.5,15 L5.5,15 C4.67157288,15 4,14.3284271 4,13.5 C4,12.6715729 4.67157288,12 5.5,12 L7,12 L7.5582739,6.97553494 C7.80974924,4.71225688 9.72279394,3 12,3 C14.2772061,3 16.1902508,4.71225688 16.4417261,6.97553494 L17,12 Z" fill="#000000"/>
											        <rect fill="#000000" opacity="0.3" x="10" y="16" width="4" height="4" rx="2"/>
											    </g>
											</svg><!--end::Svg Icon-->
										</span>
									</span>
									<span class="nav-text font-size-lg">Activity Log</span>
								</a>
							</li>
							<!--end::Item-->
						</ul>
					</div>
					<!--end::Toolbar-->
				</div>
				<!--end::Card header-->
				<!--begin::Card body-->
				<div class="card-body">
					<div class="tab-content">

						@if ($message = Session::get('error'))
	                        <div class="alert alert-danger alert-block">
	                            <button type="button" class="close" data-dismiss="alert">×</button> 
	                            {{ $message }}
	                        </div>
	                    @endif

	                    @if ($errors->any())
	                        <div class="alert alert-danger first">
	                            @foreach ($errors->all() as $error)
	                                {{ $error }} <br>
	                            @endforeach
	                        </div>

	                        <br/>
	                    @endif

	                    @if(session()->get('success'))
	                        <div class="alert alert-success">
	                            {{ session()->get('success') }}  
	                        </div><br/>
	                    @endif

						@include('dashboard.settings.tabs.settings')
						@include('dashboard.settings.tabs.server')
						@include('dashboard.settings.tabs.activities')
					</div>
					
				</div>
				<!--begin::Card body-->
			</div>
			<!--end::Card-->
		</div>
		<!--end::Container-->
	</div>
	<!--end::Entry-->
</div>
@endsection

@section('custom_scripts')
	<script src="{{ asset('js/app.js') }}" type="text/javascript" />
@endsection