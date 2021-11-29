@extends('layouts.app')

@section('content')
<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
    <div class="d-flex flex-column-fluid">
        <div class="container">
            <div class="card card-custom col-12">
                <div class="card-header flex-wrap border-0 pt-6 pb-0">
                    <div class="card-title">
                        <h3 class="card-label">Update Datacenter
                            <div class="text-muted pt-2 font-size-sm">
                                Update Datacenter
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

                    <form action="{{ route('dashboard.datacenters.update') }}" method="POST">
                        @csrf
                        @method('PATCH')

                        <input type="hidden" name="id" value="{{ $datacenter->id }}">

                        @include('dashboard.datacenters.form-content')

                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-edit mr-1"></i> Update
                        </button>

                        <a class="btn btn-secondary" href="{{ route('dashboard.datacenters.index') }}">
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
<script type="text/javascript" src="{{ asset('js/app.js') }}"></script>

<script type="text/javascript">
    $('input[name=datacenter_name]').val('{{ $datacenter->datacenter_name }}')
    $('input[name=client_datacenter_name]').val('{{ $datacenter->client_datacenter_name }}')
    $('input[name=location]').val('{{ $datacenter->location }}')
    $('input[name=status]').val('{{ $datacenter->status }}')
</script>
@endsection