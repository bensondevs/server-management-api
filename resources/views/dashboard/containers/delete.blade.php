@extends('layouts.app')

@section('content')
<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
	<div class="d-flex flex-column-fluid">
        <div class="container">
            <div class="card card-custom col-12">
                <div class="card-header flex-wrap border-0 pt-6 pb-0">
                    <div class="card-title">
                        <h3 class="card-label">Destroy Container
                            <div class="text-muted pt-2 font-size-sm">
                                Destroy Unused Container
                            </div>
                        </h3>
                    </div>
                </div>

                <div class="card-body">
                	<p class="text-danger">Are you sure want to destroy this container?</p>

                	<ul>
                		<li>Container ID: {{ $container->id }}</li>
                		<li>Server: {{ $container->server->server_name }}</li>
                		<li>Subnet: {{ $container->subnet->subnet_mask }}</li>
                		<li>Subnet IP: {{ $container->subnetIp->ip_address }}</li>
                		<li>Status: {{ strtoupper($container->status) }}</li>
                		<li>Hostname: {{ $container->hostname }}</li>
                		<li>Client Email: {{ $container->client_email }}</li>
                		<li>Order Date: {{ $container->order_human_date }}</li>
                		<li>Activation Date: {{ $container->activation_human_date }}</li>
                		<li>Expiration Date: {{ $container->expiration_human_date }}</li>
                	</ul>
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

                    <form action="{{ route('dashboard.containers.destroy', ['container' => $container]) }}" method="POST">
                        @csrf
                        @method('DELETE')

                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-trash mr-1"></i> Delete
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