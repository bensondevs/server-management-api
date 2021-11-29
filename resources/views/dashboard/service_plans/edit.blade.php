@extends('layouts.app')

@section('content')
<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
    <div class="d-flex flex-column-fluid">
        <div class="container">
            <div class="card card-custom col-12">
                <div class="card-header flex-wrap border-0 pt-6 pb-0">
                    <div class="card-title">
                        <h3 class="card-label">Update Service Plans
                            <div class="text-muted pt-2 font-size-sm">
                                Update Service Plans
                            </div>
                        </h3>
                    </div>
                </div>

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

                    <form action="{{ route('dashboard.service_plans.update') }}" method="POST">
                        @csrf
                        @method('PATCH')

                        <input type="hidden" name="id" value="{{ $plan->id }}">

                        @include('dashboard.service_plans.form-content')

                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-edit mr-1"></i> Update
                        </button>

                        <a class="btn btn-secondary" href="{{ route('dashboard.service_plans.index') }}">
                            Cancel
                        </a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('custom_scripts')
	<script src="{{ asset('js/app.js') }}" type="text/javascript"></script>
	<script type="text/javascript">
		$('input[name=plan_name]').val('{{ $plan->plan_name }}')
		$('select[name=currency]').val('{{ strtoupper($plan->currency) }}')
		$('input[name=subscription_fee]').val('{{ $plan->subscription_fee }}')
		$('input[name=time_quantity]').val('{{ $plan->time_quantity }}')
		$('select[name=time_unit]').val('{{ $plan->time_unit }}')
		$('textarea[name=description]').val('{{ $plan->description }}')
	</script>
@endsection