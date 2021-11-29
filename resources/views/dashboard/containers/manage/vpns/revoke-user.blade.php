@extends('layouts.app')

@section('content')
<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
    <div class="d-flex flex-column-fluid">
        <div class="container">
            <div class="card card-custom col-12">
                <div class="card-header flex-wrap border-0 pt-6 pb-0">
                    <div class="card-title">
                        <h3 class="card-label">Revoke VPN User
                            <div class="text-muted pt-2 font-size-sm">
                                Revoke VPN User
                            </div>
                        </h3>
                    </div>
                </div>

                <div class="card-body">
                	<p class="text-danger">Are you sure to revoke user from container?</p>

                	<ul>
                		<li>Container ID: {{ $container->id }}</li>
                		<li>Username: {{ $vpnUser->username }}</li>
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

                    <form action="{{ route('dashboard.containers.vpn.revoke_user', ['container' => $container, 'vpn_user' => $vpnUser]) }}" method="POST">
                        @csrf

                        <input type="hidden" name="vpn_user_id" value="{{ $vpnUser->id }}">
                        <input type="hidden" name="username" value="{{ $vpnUser->username }}">

                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-trash mr-1"></i> Revoke
                        </button>

                        <a class="btn btn-secondary" href="{{ route('dashboard.containers.vpn.manage', ['container' => $container]) }}">
                            Cancel
                        </a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection