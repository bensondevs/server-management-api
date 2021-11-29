@extends('layouts.app')

@section('content')
<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
    <div class="d-flex flex-column-fluid">
        <div class="container">
            <div class="card card-custom col-12">
                <div class="card-header flex-wrap border-0 pt-6 pb-0">
                    <div class="card-title">
                        <h3 class="card-label">Edit Pricing
                            <div class="text-muted pt-2 font-size-sm">
                                Edit Pricing
                            </div>
                        </h3>
                    </div>
                </div>

                <div class="card-body">
                    @if(session()->get('success'))
                        <div class="alert alert-success">
                            {{ session()->get('success') }}  
                        </div><br/>
                    @endif
                    
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

                    <form action="{{ route('dashboard.pricings.update', ['id' => request()->id]) }}" method="POST">
                        @csrf

                        <input type="hidden" name="priceable_type" value="{{ $pricing->priceable_type }}">
                        <input type="hidden" name="priceable_id" value="{{ $pricing->priceable_id }}">

                        @include('dashboard.pricings.form-content')

                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-pencil-alt mr-1"></i> Update
                        </button>
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
    $('select[name=currency]').val('{{ $pricing->currency }}');
    $('input[name=price]').val('{{ $pricing->price }}');
</script>
@endsection