@extends('layouts.app')

@section('content')
<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
    <div class="d-flex flex-column-fluid">
        <div class="container">
            <div class="card card-custom col-12">
                <div class="container mt-3">
                    <a class="btn btn-sm btn-primary" href="{{ route('dashboard.containers.view', ['container' => $container]) }}">
                        Back
                    </a>
                </div>
                
                <div class="card-header flex-wrap border-0 pt-6 pb-0">
                    <div class="card-title">
                        <h3 class="card-label">Manage VPN Container
                            <div class="text-muted pt-2 font-size-sm">
                                Manage VPN Container
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

                    @if(session()->get('success'))
                        <div class="alert alert-success">
                            {{ session()->get('success') }}  
                        </div><br/>
                    @endif

                    <div class="row">
                        <div class="container">
                            <h3>VPN PID Numbers</h3>
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>PID Number</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($pidNumbers as $index => $pidNumber)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $pidNumber }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="row">
                        <div class="container">
                            <h3>VPN Users</h3>

                            <div class="float-right mb-3">
                                <a href="{{ route('dashboard.containers.vpn.input_create_user', ['container' => $container]) }}" class="btn btn-sm btn-primary">
                                    <i class="fas fa-user-plus"></i> Create User
                                </a>
                            </div>

                            <br />

                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Username</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($vpnUsers as $vpnUser)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $vpnUser->username }}</td>
                                            <td>
                                                <a class="btn btn-sm btn-primary" href="#">
                                                    <i class="fas fa-list mr-1"></i> Read Config
                                                </a>
                                                <a class="btn btn-sm btn-danger" href="{{ route('dashboard.containers.vpn.confirm_revoke_user', ['vpn_user' => $vpnUser, 'container' => $container]) }}">
                                                    <i class="fas fa-user-times mr-1"></i> Revoke
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection