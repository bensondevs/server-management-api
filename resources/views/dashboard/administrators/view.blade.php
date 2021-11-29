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
                                <a class="nav-link active" data-toggle="tab" href="#kt_user_edit_tab_1">
                                    <span class="nav-icon">
                                        <span class="svg-icon">
                                            <!--begin::Svg Icon | path:/metronic/theme/html/demo1/dist/assets/media/svg/icons/Design/Layers.svg-->
                                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                    <polygon points="0 0 24 0 24 24 0 24"></polygon>
                                                    <path d="M12.9336061,16.072447 L19.36,10.9564761 L19.5181585,10.8312381 C20.1676248,10.3169571 20.2772143,9.3735535 19.7629333,8.72408713 C19.6917232,8.63415859 19.6104327,8.55269514 19.5206557,8.48129411 L12.9336854,3.24257445 C12.3871201,2.80788259 11.6128799,2.80788259 11.0663146,3.24257445 L4.47482784,8.48488609 C3.82645598,9.00054628 3.71887192,9.94418071 4.23453211,10.5925526 C4.30500305,10.6811601 4.38527899,10.7615046 4.47382636,10.8320511 L4.63,10.9564761 L11.0659024,16.0730648 C11.6126744,16.5077525 12.3871218,16.5074963 12.9336061,16.072447 Z" fill="#000000" fill-rule="nonzero"></path>
                                                    <path d="M11.0563554,18.6706981 L5.33593024,14.122919 C4.94553994,13.8125559 4.37746707,13.8774308 4.06710397,14.2678211 C4.06471678,14.2708238 4.06234874,14.2738418 4.06,14.2768747 L4.06,14.2768747 C3.75257288,14.6738539 3.82516916,15.244888 4.22214834,15.5523151 C4.22358765,15.5534297 4.2250303,15.55454 4.22647627,15.555646 L11.0872776,20.8031356 C11.6250734,21.2144692 12.371757,21.2145375 12.909628,20.8033023 L19.7677785,15.559828 C20.1693192,15.2528257 20.2459576,14.6784381 19.9389553,14.2768974 C19.9376429,14.2751809 19.9363245,14.2734691 19.935,14.2717619 L19.935,14.2717619 C19.6266937,13.8743807 19.0546209,13.8021712 18.6572397,14.1104775 C18.654352,14.112718 18.6514778,14.1149757 18.6486172,14.1172508 L12.9235044,18.6705218 C12.377022,19.1051477 11.6029199,19.1052208 11.0563554,18.6706981 Z" fill="#000000" opacity="0.3"></path>
                                                </g>
                                            </svg>
                                            <!--end::Svg Icon-->
                                        </span>
                                    </span>
                                    <span class="nav-text font-size-lg">Profile</span>
                                </a>
                            </li>
                            <!--end::Item-->
                            <!--begin::Item-->
                            <li class="nav-item mr-3">
                                <a class="nav-link" data-toggle="tab" href="#kt_user_edit_tab_2">
                                    <span class="svg-icon"><!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2021-03-11-144509/theme/html/demo2/dist/../src/media/svg/icons/Text/Bullet-list.svg-->
                                    	<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
										    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
										        <rect x="0" y="0" width="24" height="24"/>
										        <path d="M10.5,5 L19.5,5 C20.3284271,5 21,5.67157288 21,6.5 C21,7.32842712 20.3284271,8 19.5,8 L10.5,8 C9.67157288,8 9,7.32842712 9,6.5 C9,5.67157288 9.67157288,5 10.5,5 Z M10.5,10 L19.5,10 C20.3284271,10 21,10.6715729 21,11.5 C21,12.3284271 20.3284271,13 19.5,13 L10.5,13 C9.67157288,13 9,12.3284271 9,11.5 C9,10.6715729 9.67157288,10 10.5,10 Z M10.5,15 L19.5,15 C20.3284271,15 21,15.6715729 21,16.5 C21,17.3284271 20.3284271,18 19.5,18 L10.5,18 C9.67157288,18 9,17.3284271 9,16.5 C9,15.6715729 9.67157288,15 10.5,15 Z" fill="#000000"/>
										        <path d="M5.5,8 C4.67157288,8 4,7.32842712 4,6.5 C4,5.67157288 4.67157288,5 5.5,5 C6.32842712,5 7,5.67157288 7,6.5 C7,7.32842712 6.32842712,8 5.5,8 Z M5.5,13 C4.67157288,13 4,12.3284271 4,11.5 C4,10.6715729 4.67157288,10 5.5,10 C6.32842712,10 7,10.6715729 7,11.5 C7,12.3284271 6.32842712,13 5.5,13 Z M5.5,18 C4.67157288,18 4,17.3284271 4,16.5 C4,15.6715729 4.67157288,15 5.5,15 C6.32842712,15 7,15.6715729 7,16.5 C7,17.3284271 6.32842712,18 5.5,18 Z" fill="#000000" opacity="0.3"/>
										    </g>
										</svg><!--end::Svg Icon-->
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
	                    </div>
	                    <br/>
                    @endif

                    <div class="tab-content">
                        <!--begin::Tab-->
                        <div class="tab-pane show px-7 active" id="kt_user_edit_tab_1" role="tabpanel">
                            <form id="update_user_profile_form" method="POST" action="{{ route('dashboard.users.update_profile') }}">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="id" value="{{ $administrator->id }}">

                                <!--begin::Row-->
                                <div class="row">
                                    <div class="col-xl-2"></div>
                                    <div class="col-xl-7 my-2">
                                        <input type="hidden" name="id" value="{{ $administrator->id }}">
                                        <!--begin::Row-->
                                        <div class="row">
                                            <label class="col-3"></label>
                                            <div class="col-9">
                                                <h6 class="text-dark font-weight-bold mb-10">User Info:</h6>
                                            </div>
                                        </div>
                                        <!--end::Row-->
                                        <!--begin::Group-->
                                        <div class="form-group row">
                                            <label class="col-form-label col-3 text-lg-right text-left">First Name*</label>
                                            <div class="col-9">
                                                <input 
                                                    class="form-control form-control-lg form-control-solid" 
                                                    name="first_name" 
                                                    form="update_user_profile_form" 
                                                    type="text" 
                                                    placeholder="First Name" 
                                                    value="{{ $administrator->first_name }}">
                                            </div>
                                        </div>
                                        <!--end::Group-->
                                        <!--begin::Group-->
                                        <div class="form-group row">
                                            <label class="col-form-label col-3 text-lg-right text-left">Middle Name</label>
                                            <div class="col-9">
                                                <input 
                                                    class="form-control form-control-lg form-control-solid" 
                                                    name="middle_name" 
                                                    form="update_user_profile_form" 
                                                    type="text" 
                                                    placeholder="Middle Name" 
                                                    value="{{ $administrator->middle_name }}">
                                            </div>
                                        </div>
                                        <!--end::Group-->
                                        <!--begin::Group-->
                                        <div class="form-group row">
                                            <label class="col-form-label col-3 text-lg-right text-left">Last Name*</label>
                                            <div class="col-9">
                                                <input 
                                                    class="form-control form-control-lg form-control-solid" 
                                                    name="last_name" 
                                                    form="update_user_profile_form" 
                                                    type="text" 
                                                    placeholder="Last Name" 
                                                    value="{{ $administrator->last_name }}">
                                            </div>
                                        </div>
                                        <!--end::Group-->
                                        <!--begin::Group-->
                                        <div class="form-group row">
                                            <label class="col-form-label col-3 text-lg-right text-left">Company Name</label>
                                            <div class="col-9">
                                                <input 
                                                    class="form-control form-control-lg form-control-solid" 
                                                    name="company_name" 
                                                    form="update_user_profile_form" 
                                                    type="text" 
                                                    placeholder="Company Name" 
                                                    value="{{ $administrator->company_name }}">
                                            </div>
                                        </div>
                                        <!--end::Group-->
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
                                                    class="btn btn-light-primary font-weight-bold">
                                                	Save changes
                                                </button>
                                                <a href="#" class="btn btn-clean font-weight-bold">Cancel</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!--end::Row-->
                            </form>
                        </div>
                        <!--end::Tab-->
                        <!--begin::Tab-->
                        <div class="tab-pane px-7" id="kt_user_edit_tab_2" role="tabpanel">
                            <!--begin::Row-->
                            <div class="row">
                                <div class="col-12">
									<!--begin::Card-->
									<!--begin::Responsive container-->
									<div class="table-responsive">
										<!--begin::Items-->
										<div class="list list-hover min-w-500px" data-inbox="list">
											@foreach ($activities as $key => $activity)
												<!--begin::Item-->
												<div class="d-flex align-items-start list-item card-spacer-x py-4" data-inbox="message">
													<!--begin::Toolbar-->
													<div class="d-flex align-items-center">
														<!--begin::Actions-->
														<div class="d-flex align-items-center mr-3" data-inbox="actions">
															<!--begin::Buttons-->
															<a href="#" class="btn btn-icon btn-xs text-hover-warning" data-toggle="tooltip" data-placement="right" title="" data-original-title="Star">
																<i class="flaticon-star text-muted"></i>
															</a>
															<!--end::Buttons-->
														</div>
														<!--end::Actions-->
													</div>
													<!--end::Toolbar-->
													<!--begin::Info-->
													<div class="flex-grow-1 mt-1 mr-2" data-toggle="view">
														<!--begin::Title-->
														<div class="font-weight-bolder mr-2">
															{!! $activity->description !!}
														</div>
														<!--end::Title-->
													</div>
													<!--end::Info-->
													<!--begin::Details-->
													<div class="d-flex align-items-center justify-content-end flex-wrap" data-toggle="view">
														<!--begin::Datetime-->
														<div class="font-weight-bolder" data-toggle="view">
															{{ $activity->created_time }}
														</div>
														<!--end::Datetime-->
													</div>
													<!--end::Details-->
												</div>
												<!--end::Item-->
											@endforeach
										</div>
										<!--end::Items-->
									</div>
									<!--end::Responsive container-->									
									<!--end::Card-->
								</div>
                            </div>
                            <!--end::Row-->
                        </div>
                        <!--end::Tab-->
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