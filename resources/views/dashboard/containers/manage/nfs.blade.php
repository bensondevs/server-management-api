@extends('layouts.app')

@section('content')
<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
    <div class="d-flex flex-column-fluid">
        <div class="container">

            <div class="card card-custom col-12">
                <div class="container mt-3">
                    <a class="btn btn-sm btn-primary" href="{{ route('dashboard.containers.view', ['id' => request()->id]) }}">
                        Back
                    </a>
                </div>

                <div class="card-header flex-wrap border-0 pt-6 pb-0">
                    <div class="card-title">
                        <h3 class="card-label">Manage NFS Container
                            <div class="text-muted pt-2 font-size-sm">
                                Manage NFS Container
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
                            <h3>NFS Exports</h3>
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Target Folder</th>
                                        <th>Permissions</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($exports as $index => $export)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $export->target_folder }}</td>
                                            <td>{{ $export->permissions }}</td>
                                            <td>
                                                <a class="btn btn-sm btn-primary" href="#">
                                                    <i class="fas fa-pen mr-1"></i> Edit
                                                </a>
                                                <a class="btn btn-sm btn-danger" href="#">
                                                    <i class="fas fa-trash-alt mr-1"></i> Delete
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