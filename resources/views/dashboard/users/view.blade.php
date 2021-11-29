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
                                    <span class="nav-icon">
                                        <span class="svg-icon">
                                            <!--begin::Svg Icon | path:/metronic/theme/html/demo1/dist/assets/media/svg/icons/General/User.svg-->
                                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                    <polygon points="0 0 24 0 24 24 0 24"></polygon>
                                                    <path d="M12,11 C9.790861,11 8,9.209139 8,7 C8,4.790861 9.790861,3 12,3 C14.209139,3 16,4.790861 16,7 C16,9.209139 14.209139,11 12,11 Z" fill="#000000" fill-rule="nonzero" opacity="0.3"></path>
                                                    <path d="M3.00065168,20.1992055 C3.38825852,15.4265159 7.26191235,13 11.9833413,13 C16.7712164,13 20.7048837,15.2931929 20.9979143,20.2 C21.0095879,20.3954741 20.9979143,21 20.2466999,21 C16.541124,21 11.0347247,21 3.72750223,21 C3.47671215,21 2.97953825,20.45918 3.00065168,20.1992055 Z" fill="#000000" fill-rule="nonzero"></path>
                                                </g>
                                            </svg>
                                            <!--end::Svg Icon-->
                                        </span>
                                    </span>
                                    <span class="nav-text font-size-lg">Account</span>
                                </a>
                            </li>
                            <!--end::Item-->
                            <!--begin::Item-->
                            <li class="nav-item mr-3">
                                <a class="nav-link" data-toggle="tab" href="#kt_user_edit_tab_3">
                                    <span class="nav-icon">
                                        <span class="svg-icon">
                                            <!--begin::Svg Icon | path:/metronic/theme/html/demo1/dist/assets/media/svg/icons/Communication/Shield-user.svg-->
                                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                    <rect x="0" y="0" width="24" height="24"></rect>
                                                    <path d="M4,4 L11.6314229,2.5691082 C11.8750185,2.52343403 12.1249815,2.52343403 12.3685771,2.5691082 L20,4 L20,13.2830094 C20,16.2173861 18.4883464,18.9447835 16,20.5 L12.5299989,22.6687507 C12.2057287,22.8714196 11.7942713,22.8714196 11.4700011,22.6687507 L8,20.5 C5.51165358,18.9447835 4,16.2173861 4,13.2830094 L4,4 Z" fill="#000000" opacity="0.3"></path>
                                                    <path d="M12,11 C10.8954305,11 10,10.1045695 10,9 C10,7.8954305 10.8954305,7 12,7 C13.1045695,7 14,7.8954305 14,9 C14,10.1045695 13.1045695,11 12,11 Z" fill="#000000" opacity="0.3"></path>
                                                    <path d="M7.00036205,16.4995035 C7.21569918,13.5165724 9.36772908,12 11.9907452,12 C14.6506758,12 16.8360465,13.4332455 16.9988413,16.5 C17.0053266,16.6221713 16.9988413,17 16.5815,17 C14.5228466,17 11.463736,17 7.4041679,17 C7.26484009,17 6.98863236,16.6619875 7.00036205,16.4995035 Z" fill="#000000" opacity="0.3"></path>
                                                </g>
                                            </svg>
                                            <!--end::Svg Icon-->
                                        </span>
                                    </span>
                                    <span class="nav-text font-size-lg">Change Password</span>
                                </a>
                            </li>
                            <!--end::Item-->
                            <!--begin::Item-->
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#kt_user_edit_tab_4">
                                    <span class="nav-icon">
                                        <span class="svg-icon">
                                            <!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2021-03-11-144509/theme/html/demo1/dist/../src/media/svg/icons/General/Settings-2.svg-->
                                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                    <rect x="0" y="0" width="24" height="24"/>
                                                    <path d="M5,8.6862915 L5,5 L8.6862915,5 L11.5857864,2.10050506 L14.4852814,5 L19,5 L19,9.51471863 L21.4852814,12 L19,14.4852814 L19,19 L14.4852814,19 L11.5857864,21.8994949 L8.6862915,19 L5,19 L5,15.3137085 L1.6862915,12 L5,8.6862915 Z M12,15 C13.6568542,15 15,13.6568542 15,12 C15,10.3431458 13.6568542,9 12,9 C10.3431458,9 9,10.3431458 9,12 C9,13.6568542 10.3431458,15 12,15 Z" fill="#000000"/>
                                                </g>
                                            </svg>
                                            <!--end::Svg Icon-->
                                        </span>
                                    </span>
                                    <span class="nav-text font-size-lg">Settings</span>
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
                                <input type="hidden" name="id" value="{{ $user->id }}">

                                <!--begin::Row-->
                                <div class="row">
                                    <div class="col-xl-2"></div>
                                    <div class="col-xl-7 my-2">
                                        <input type="hidden" name="id" value="{{ $user->id }}">
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
                                                    value="{{ $user->first_name }}">
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
                                                    value="{{ $user->middle_name }}">
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
                                                    value="{{ $user->last_name }}">
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
                                                    value="{{ $user->company_name }}">
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
                                <div class="col-xl-2"></div>
                                <div class="col-xl-7">
                                    <div class="my-2">
                                        <form id="update_user_account_form" method="POST" action="{{ route('dashboard.users.update_account') }}">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="id" value="{{ $user->id }}">
                                            <!--begin::Row-->
                                            <div class="row">
                                                <label class="col-form-label col-3 text-lg-right text-left"></label>
                                                <div class="col-9">
                                                    <h6 class="text-dark font-weight-bold mb-10">Account:</h6>
                                                </div>
                                            </div>
                                            <!--end::Row-->
                                            <!--begin::Group-->
                                            <div class="form-group row">
                                                <label class="col-form-label col-3 text-lg-right text-left">Username</label>
                                                <div class="col-9">
                                                    <input 
                                                        class="form-control form-control-lg form-control-solid" 
                                                        form="update_user_account_form" 
                                                        name="username" 
                                                        type="text" 
                                                        value="{{ $user->username }}">
                                                </div>
                                            </div>
                                            <!--end::Group-->
                                            <!--begin::Group-->
                                            <div class="form-group row">
                                                <label class="col-form-label col-3 text-lg-right text-left">Email</label>
                                                <div class="col-9">
                                                    <div class="input-group input-group-lg input-group-solid">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text">
                                                            <i class="la la-at"></i>
                                                            </span>
                                                        </div>
                                                        <input 
                                                            type="text" 
                                                            class="form-control form-control-lg form-control-solid" 
                                                            name="email" 
                                                            form="update_user_account_form" 
                                                            value="{{ $user->email }}" 
                                                            placeholder="Email">
                                                    </div>
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
                                                form="update_user_account_form" 
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
                        <!--begin::Tab-->
                        <div class="tab-pane px-7" id="kt_user_edit_tab_3" role="tabpanel">
                            <!--begin::Body-->
                            <div class="card-body">
                                <!--begin::Row-->
                                <div class="row">
                                    <div class="col-xl-2"></div>
                                    <div class="col-xl-7">
                                        <!--begin::Row-->
                                        <div class="row mb-5">
                                            <label class="col-3"></label>
                                            <div class="col-9">
                                                <div class="alert alert-custom alert-light-danger fade show py-4" role="alert">
                                                    <div class="alert-icon">
                                                        <i class="flaticon-warning"></i>
                                                    </div>
                                                    <div class="alert-text font-weight-bold">Configure user passwords to expire periodically. 
                                                        <br>Users will need warning that their passwords are going to expire, or they might inadvertently get locked out of the system!
                                                    </div>
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
                                        <!--begin::Row-->
                                        <div class="row">
                                            <label class="col-3"></label>
                                            <div class="col-9">
                                                <h6 class="text-dark font-weight-bold mb-10">Change Or Recover Your Password:</h6>
                                            </div>
                                        </div>
                                        <!--end::Row-->

                                        <form id="update_user_password_form" method="POST" action="{{ route('dashboard.users.update_password') }}">
                                        	@csrf
                                        	@method('PATCH')
                                            <input type="hidden" name="id" value="{{ $user->id }}">

	                                        <!--begin::Group-->
	                                        <div class="form-group row">
	                                            <label class="col-form-label col-3 text-lg-right text-left">New Password</label>
	                                            <div class="col-9">
	                                                <input 
	                                                	class="form-control form-control-lg form-control-solid" 
	                                                	type="password" 
	                                                	name="password"
	                                                	placeholder="New Password" 
	                                                	value="">
	                                            </div>
	                                        </div>
	                                        <!--end::Group-->
	                                        <!--begin::Group-->
	                                        <div class="form-group row">
	                                            <label class="col-form-label col-3 text-lg-right text-left">
	                                            	Confirm Password
	                                            </label>
	                                            <div class="col-9">
	                                                <input 
	                                                	class="form-control form-control-lg form-control-solid" 
	                                                	type="password" 
	                                                	name="confirm_password"
	                                                	placeholder="Confirm Password" 
	                                                	value="">
	                                            </div>
	                                        </div>
	                                        <!--end::Group-->
                                        </form>
                                    </div>
                                </div>
                                <!--end::Row-->
                            </div>
                            <!--end::Body-->
                            <!--begin::Footer-->
                            <div class="card-footer pb-0">
                                <div class="row">
                                    <div class="col-xl-2"></div>
                                    <div class="col-xl-7">
                                        <div class="row">
                                            <div class="col-3"></div>
                                            <div class="col-9">
                                                <button 
                                                	type="submit"
                                                	form="update_user_password_form" 
                                                	class="btn btn-light-primary font-weight-bold">Save changes
                                                </button>
                                                <a href="#" class="btn btn-clean font-weight-bold">Cancel</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--end::Footer-->
                        </div>
                        <!--end::Tab-->
                        <!--begin::Tab-->
                        <div class="tab-pane px-7" id="kt_user_edit_tab_4" role="tabpanel">
                            <div class="row">
                                <div class="col-xl-2"></div>
                                <div class="col-xl-8">
                                    <div class="my-2">
                                        <div class="row">
                                            <label class="col-form-label col-3 text-lg-right text-left"></label>
                                            <div class="col-9">
                                                <h6 class="text-dark font-weight-bold mb-7">News Letter:</h6>
                                            </div>
                                        </div>
                                        <div class="form-group row mb-2">
                                            <form id="update_user_newsletter_form" method="POST" action="{{ route('dashboard.users.update_newsletter') }}">
                                                @csrf
                                                @method('PATCH')

                                                <label class="col-form-label col-7 text-lg-right text-left">
                                                    Recieve News Letter
                                                </label>
                                                <div class="col-5">
                                                    <span class="switch">
                                                    <label>
                                                        <input 
                                                            type="checkbox" 
                                                            name="newsletter" 
                                                            {{ $user->newsletter ? 'checked' : null }}>
                                                        <span></span>
                                                    </label>
                                                    </span>
                                                </div>
                                            </form>
                                        </div>

                                        <!--begin::Row-->
                                        <div class="row">
                                            <div class="col-xl-2"></div>
                                            <div class="col-xl-7">
                                                <div class="row">
                                                    <div class="col-3"></div>
                                                    <div class="col-9">
                                                        <button 
                                                            type="submit" 
                                                            form="update_user_newsletter_form" 
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
                                </div>
                            </div>

                            <div class="separator separator-dashed mb-10"></div>

                            <div class="row">
                                <label class="col-form-label col-3 text-lg-right text-left"></label>
                                <div class="col-9">
                                    <h6 class="text-dark font-weight-bold mb-7">Permissions:</h6>
                                </div>
                                <div class="col-xl-2"></div>
                                <form id="update_user_permissions_form" method="POST" action="{{ route('dashboard.users.update_permissions') }}">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="id" value="{{ $user->id }}">

                                    <div class="col-xl-12">
                                        <div class="form-group row">
                                            <label class="col-form-label col-3 text-lg-right text-left">Dashboard Access</label>
                                            <div class="col-9">
                                                <div class="checkbox-inline mt-4">
                                                    <label class="checkbox">
                                                        <input 
                                                            name="permissions[]" 
                                                            type="checkbox" 
                                                            value="dashboard login"
                                                            {{ in_array('dashboard login', $user->permissions) ? 'checked' : null }}>
                                                        <span></span>Admin Dashboard Access
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-form-label col-3 text-lg-right text-left">Regions</label>
                                            <div class="col-9">
                                                <div class="checkbox-inline mb-2">
                                                    <label class="checkbox">
                                                        <input 
                                                            name="permissions[]" 
                                                            type="checkbox" 
                                                            value="view regions"
                                                            {{ in_array('view regions', $user->permissions) ? 'checked' : null }}>
                                                        <span></span>View Regions
                                                    </label>
                                                </div>
                                                <div class="checkbox-inline mb-2">
                                                    <label class="checkbox">
                                                        <input 
                                                            name="permissions[]" 
                                                            type="checkbox" 
                                                            value="create regions"
                                                            {{ in_array('create regions', $user->permissions) ? 'checked' : null }}>
                                                        <span></span>Create Regions
                                                    </label>
                                                </div>
                                                <div class="checkbox-inline mb-2">
                                                    <label class="checkbox">
                                                        <input 
                                                            name="permissions[]" 
                                                            type="checkbox" 
                                                            value="edit regions"
                                                            {{ in_array('edit regions', $user->permissions) ? 'checked' : null }}>
                                                        <span></span>Edit Regions
                                                    </label>
                                                </div>
                                                <div class="checkbox-inline mb-2">
                                                    <label class="checkbox">
                                                        <input 
                                                            name="permissions[]" 
                                                            type="checkbox" 
                                                            value="delete regions"
                                                            {{ in_array('delete regions', $user->permissions) ? 'checked' : null }}>
                                                        <span></span>Delete Regions
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-form-label col-3 text-lg-right text-left">Data Centers</label>
                                            <div class="col-9">
                                                <div class="checkbox-inline mb-2">
                                                    <label class="checkbox">
                                                        <input 
                                                            name="permissions[]" 
                                                            type="checkbox" 
                                                            value="view datacenters"
                                                            {{ in_array('view datacenters', $user->permissions) ? 'checked' : null }}>
                                                        <span></span>View Data Centers
                                                    </label>
                                                </div>
                                                <div class="checkbox-inline mb-2">
                                                    <label class="checkbox">
                                                        <input 
                                                            name="permissions[]" 
                                                            type="checkbox" 
                                                            value="create datacenters"
                                                            {{ in_array('create datacenters', $user->permissions) ? 'checked' : null }}>
                                                        <span></span>Create Data Centers
                                                    </label>
                                                </div>
                                                <div class="checkbox-inline mb-2">
                                                    <label class="checkbox">
                                                        <input 
                                                            name="permissions[]" 
                                                            type="checkbox" 
                                                            value="toggle datacenters"
                                                            {{ in_array('toggle datacenters', $user->permissions) ? 'checked' : null }}>
                                                        <span></span>Toggle Data Centers Status
                                                    </label>
                                                </div>
                                                <div class="checkbox-inline mb-2">
                                                    <label class="checkbox">
                                                        <input 
                                                            name="permissions[]" 
                                                            type="checkbox" 
                                                            value="edit datacenters"
                                                            {{ in_array('edit datacenters', $user->permissions) ? 'checked' : null }}>
                                                        <span></span>Edit Data Centers
                                                    </label>
                                                </div>
                                                <div class="checkbox-inline mb-2">
                                                    <label class="checkbox">
                                                        <input 
                                                            name="permissions[]" 
                                                            type="checkbox" 
                                                            value="delete datacenters"
                                                            {{ in_array('delete datacenters', $user->permissions) ? 'checked' : null }}>
                                                        <span></span>Delete Data Centers
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-form-label col-3 text-lg-right text-left">Servers</label>
                                            <div class="col-9">
                                                <div class="checkbox-inline mb-2">
                                                    <label class="checkbox">
                                                        <input 
                                                            name="permissions[]" 
                                                            type="checkbox" 
                                                            value="view servers"
                                                            {{ in_array('view servers', $user->permissions) ? 'checked' : null }}>
                                                        <span></span>View Servers
                                                    </label>
                                                </div>
                                                <div class="checkbox-inline mb-2">
                                                    <label class="checkbox">
                                                        <input 
                                                            name="permissions[]" 
                                                            type="checkbox" 
                                                            value="create servers"
                                                            {{ in_array('create servers', $user->permissions) ? 'checked' : null }}>
                                                        <span></span>Create Servers
                                                    </label>
                                                </div>
                                                <div class="checkbox-inline mb-2">
                                                    <label class="checkbox">
                                                        <input 
                                                            name="permissions[]" 
                                                            type="checkbox" 
                                                            value="toggle servers"
                                                            {{ in_array('toggle servers', $user->permissions) ? 'checked' : null }}>
                                                        <span></span>Toggle Servers Status
                                                    </label>
                                                </div>
                                                <div class="checkbox-inline mb-2">
                                                    <label class="checkbox">
                                                        <input 
                                                            name="permissions[]" 
                                                            type="checkbox" 
                                                            value="edit servers"
                                                            {{ in_array('edit servers', $user->permissions) ? 'checked' : null }}>
                                                        <span></span>Edit Servers
                                                    </label>
                                                </div>
                                                <div class="checkbox-inline mb-2">
                                                    <label class="checkbox">
                                                        <input 
                                                            name="permissions[]" 
                                                            type="checkbox" 
                                                            value="delete servers"
                                                            {{ in_array('delete servers', $user->permissions) ? 'checked' : null }}>
                                                        <span></span>Delete Servers
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-form-label col-3 text-lg-right text-left">Subnets</label>
                                            <div class="col-9">
                                                <div class="checkbox-inline mb-2">
                                                    <label class="checkbox">
                                                        <input 
                                                            name="permissions[]" 
                                                            type="checkbox" 
                                                            value="assign subnets"
                                                            {{ in_array('assign subnets', $user->permissions) ? 'checked' : null }}>
                                                        <span></span>Assign Subnets
                                                    </label>
                                                </div>
                                                <div class="checkbox-inline mb-2">
                                                    <label class="checkbox">
                                                        <input 
                                                            name="permissions[]" 
                                                            type="checkbox" 
                                                            value="view subnets"
                                                            {{ in_array('view subnets', $user->permissions) ? 'checked' : null }}>
                                                        <span></span>View Subnets
                                                    </label>
                                                </div>
                                                <div class="checkbox-inline mb-2">
                                                    <label class="checkbox">
                                                        <input 
                                                            name="permissions[]" 
                                                            type="checkbox" 
                                                            value="create servers"
                                                            {{ in_array('create servers', $user->permissions) ? 'checked' : null }}>
                                                        <span></span>Create Subnets
                                                    </label>
                                                </div>
                                                <div class="checkbox-inline mb-2">
                                                    <label class="checkbox">
                                                        <input 
                                                            name="permissions[]" 
                                                            type="checkbox" 
                                                            value="edit subnets"
                                                            {{ in_array('edit subnets', $user->permissions) ? 'checked' : null }}>
                                                        <span></span>Edit Subnets
                                                    </label>
                                                </div>
                                                <div class="checkbox-inline mb-2">
                                                    <label class="checkbox">
                                                        <input 
                                                            name="permissions[]" 
                                                            type="checkbox" 
                                                            value="delete subnets"
                                                            {{ in_array('delete subnets', $user->permissions) ? 'checked' : null }}>
                                                        <span></span>Delete Subnets
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-form-label col-3 text-lg-right text-left">Subnet IP(s)</label>
                                            <div class="col-9">
                                                <div class="checkbox-inline mb-2">
                                                    <label class="checkbox">
                                                        <input 
                                                            name="permissions[]" 
                                                            type="checkbox" 
                                                            value="assign ips"
                                                            {{ in_array('assign ips', $user->permissions) ? 'checked' : null }}>
                                                        <span></span>Assign Subnet IP(s)
                                                    </label>
                                                </div>
                                                <div class="checkbox-inline mb-2">
                                                    <label class="checkbox">
                                                        <input 
                                                            name="permissions[]" 
                                                            type="checkbox" 
                                                            value="view ips"
                                                            {{ in_array('view ips', $user->permissions) ? 'checked' : null }}>
                                                        <span></span>View Subnet IP(s)
                                                    </label>
                                                </div>
                                                <div class="checkbox-inline mb-2">
                                                    <label class="checkbox">
                                                        <input 
                                                            name="permissions[]" 
                                                            type="checkbox" 
                                                            value="edit ips"
                                                            {{ in_array('edit ips', $user->permissions) ? 'checked' : null }}>
                                                        <span></span>Edit Subnet IP(s)
                                                    </label>
                                                </div>
                                                <div class="checkbox-inline mb-2">
                                                    <label class="checkbox">
                                                        <input 
                                                            name="permissions[]" 
                                                            type="checkbox" 
                                                            value="delete ips"
                                                            {{ in_array('delete ips', $user->permissions) ? 'checked' : null }}>
                                                        <span></span>Delete Subnet IP(s)
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-form-label col-3 text-lg-right text-left">Command Histories</label>
                                            <div class="col-9">
                                                <div class="checkbox-inline mb-2">
                                                    <label class="checkbox">
                                                        <input 
                                                            name="permissions[]" 
                                                            type="checkbox" 
                                                            value="view command histories"
                                                            {{ in_array('view command histories', $user->permissions) ? 'checked' : null }}>
                                                        <span></span>View Command Histories
                                                    </label>
                                                </div>
                                                <div class="checkbox-inline mb-2">
                                                    <label class="checkbox">
                                                        <input 
                                                            name="permissions[]" 
                                                            type="checkbox" 
                                                            value="execute commands"
                                                            {{ in_array('execute commands', $user->permissions) ? 'checked' : null }}>
                                                        <span></span>Execute Command
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-form-label col-3 text-lg-right text-left">
                                                Users
                                            </label>
                                            <div class="col-9">
                                                <div class="checkbox-inline mb-2">
                                                    <label class="checkbox">
                                                        <input 
                                                            name="permissions[]" 
                                                            type="checkbox" 
                                                            value="view users"
                                                            {{ in_array('view users', $user->permissions) ? 'checked' : null }}>
                                                        <span></span>View Users
                                                    </label>
                                                </div>
                                                <div class="checkbox-inline mb-2">
                                                    <label class="checkbox">
                                                        <input 
                                                            name="permissions[]" 
                                                            type="checkbox" 
                                                            value="create users"
                                                            {{ in_array('create users', $user->permissions) ? 'checked' : null }}>
                                                        <span></span>Create Users
                                                    </label>
                                                </div>
                                                <div class="checkbox-inline mb-2">
                                                    <label class="checkbox">
                                                        <input 
                                                            name="permissions[]" 
                                                            type="checkbox" 
                                                            value="edit users"
                                                            {{ in_array('edit users', $user->permissions) ? 'checked' : null }}>
                                                        <span></span>Edit Users
                                                    </label>
                                                </div>
                                                <div class="checkbox-inline mb-2">
                                                    <label class="checkbox">
                                                        <input 
                                                            name="permissions[]" 
                                                            type="checkbox" 
                                                            value="delete users"
                                                            {{ in_array('delete users', $user->permissions) ? 'checked' : null }}>
                                                        <span></span>Delete Users
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>

                            <!--begin::Row-->
                            <div class="row">
                                <div class="col-xl-2"></div>
                                <div class="col-xl-7">
                                    <div class="row">
                                        <div class="col-3"></div>
                                        <div class="col-9">
                                            <button 
                                                type="submit" 
                                                form="update_user_permissions_form" 
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
	<script src="{{ asset('js/app.js') }}"></script>
	<script src="{{ asset('assets/plugins/global/plugins.bundle.js?v=7.2.3') }}"></script>
	<script src="{{ asset('assets/js/scripts.bundle.js?v=7.2.3') }}"></script>
@endsection