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
                	<p class="text-danger">Are you sure want to destroy this newsletter?</p>

                	<ul>
                		<li>Newsletter Title: {{ $newsletter->title }}</li>
                		<li>Content: {{ $newsletter->content }}</li>
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

                    <form action="{{ route('dashboard.newletters.delete') }}" method="POST">
                        @csrf
                        @method('DELETE')

                        <input type="hidden" name="id" value="{{ $newsletter->id }}">

                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-trash mr-1"></i> Delete
                        </button>

                        <a class="btn btn-secondary" href="{{ route('dashboard.newletters.index') }}">
                            Cancel
                        </a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection