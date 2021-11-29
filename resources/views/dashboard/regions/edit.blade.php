@extends('layouts.app')

@section('content')
<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
    <div class="d-flex flex-column-fluid">
        <div class="container">
            <div class="card card-custom col-12">
                <div class="card-header flex-wrap border-0 pt-6 pb-0">
                    <div class="card-title">
                        <h3 class="card-label">Update Region
                            <div class="text-muted pt-2 font-size-sm">
                                Update Region
                            </div>
                        </h3>
                    </div>
                </div>

                <div class="card-body">
                    @if ($message = session()->get('error'))
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

                    <form action="{{ route('dashboard.regions.update', $region) }}" method="POST">
                        @csrf
                        @method('PATCH')

                        <input type="hidden" name="id" value="{{ $region->id }}">

                        @include('dashboard.regions.form-content')

                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-edit mr-1"></i> Update
                        </button>

                        <a class="btn btn-secondary" href="{{ route('dashboard.regions.index') }}">
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
    $('input[name=region_name]').val('{{ $region->region_name }}')
</script>
@endsection