@extends('layouts.app')

@section('content')
<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
    <div class="d-flex flex-column-fluid">
        <div class="container">
            <div class="card card-custom col-12">
                <div class="card-header flex-wrap border-0 pt-6 pb-0">
                    <div class="card-title">
                        <h3 class="card-label">Create New VPN User
                            <div class="text-muted pt-2 font-size-sm">
                                Create New VPN User
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

                    <form action="{{ route('dashboard.containers.vpn.create_user', ['container' => $container]) }}" method="POST">
                        @csrf

                        <div class="form-group">
                            <label>Username</label>
                            <div class="input-group">
                                <input class="form-control" type="text" name="username">
                            </div>
                        </div>

                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-plus mr-1"></i> Store
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