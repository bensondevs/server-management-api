@extends('layouts.app')

@section('content')
<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
    <div class="d-flex flex-column-fluid">
        <div class="container">
            <div class="card card-custom col-12">
                <div class="card-header flex-wrap border-0 pt-6 pb-0">
                    <div class="card-title">
                        <h3 class="card-label">Job Tracker
                            <div class="text-muted pt-2 font-size-sm">
                            	Log of Job Execution
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
                    
                    <table class="table">
                    	<thead>
                    		<th>#</th>
                    		<th>Model Type</th>
                            <th>Model ID</th>
                            <th>Job Class</th>
                            <th>Status</th>
                    		<th>Actions</th>
                    	</thead>

                    	<tbody>
                    		@foreach ($trackers as $tracker)
	                    		<tr>
	                    			<td>{{ $loop->iteration }}</td>
	                    			<td>{{ $tracker->model_type }}</td>
                                    <td>{{ $tracker->model_id }}</td>
                                    <td>{{ $tracker->job_class }}</td>
                                    <td>{{ $tracker->status_description }}</td>
	                    			<td>
	                    				{{-- <a href="{{ route('dashboard.regions.edit', ['id' => $region->id]) }}">
						                    <img src="{{ asset('assets/media/svg/icons/General/Edit.svg') }}" />
						                </a>
						                <a href="{{ route('dashboard.regions.confirm_delete', ['id' => $region->id]) }}">
						                    <img src="{{ asset('assets/media/svg/icons/Home/Trash.svg') }}" />
						                </a> --}}
	                    			</td>
	                    		</tr>
	                    	@endforeach
                    	</tbody>
                    </table>
                </div>
            </div>
            @if ($trackers->lastPage() > 1)
                <div class="card-footer text-center">
                    <nav class="d-inline-block fit-block">
                        @include('components.paginations.general', [
                            'collection' => $trackers,
                        ])
                    </nav>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@section('custom_scripts')
    <script type="text/javascript" src="{{ asset('js/app.js') }}"></script>
@endsection