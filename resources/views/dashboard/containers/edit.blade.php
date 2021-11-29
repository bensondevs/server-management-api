@extends('layouts.app')

@section('content')
<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
    <div class="d-flex flex-column-fluid">
        <div class="container">
            <div class="card card-custom col-12">
                <div class="card-header flex-wrap border-0 pt-6 pb-0">
                    <div class="card-title">
                        <h3 class="card-label">Edit Container
                            <div class="text-muted pt-2 font-size-sm">
                                Edit Container
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

                    <form action="{{ route('dashboard.containers.update', ['container' => $container]) }}" method="POST">
                        @csrf
                        @method('PATCH')

                        <input type="hidden" name="id" value="{{ $container->id }}">

                        @include('dashboard.containers.form-content')

                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-edit mr-1"></i> Update
                        </button>

                        <a class="btn btn-secondary" href="{{ route('dashboard.containers.index') }}">
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
<script type="text/javascript">
	$('select[name=customer_id]').val('{{ $container->customer_id }}')
	$('select[name=server_id]').val('{{ $container->server_id }}')

	$('select[name=subnet_id]').val('{{ $container->subnet_id }}')
    populateIps('{{ $container->subnet_id }}', '{{ $container->subnet_ip_id }}')
    $('select[name=subnet_ip_id]').val('{{ $container->subnet_ip_id }}')

	$('input[name=hostname]').val('{{ $container->hostname }}')
	$('input[name=client_email]').val('{{ $container->client_email }}')
	$('input[name=order_date]').val('{{ $container->order_date }}')
	$('input[name=activation_date]').val('{{ $container->activation_date }}')
	$('input[name=expiration_date]').val('{{ $container->expiration_date }}')
</script>
@endsection