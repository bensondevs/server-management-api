@extends('layouts.app')

@section('content')
<div class="content d-flex flex-column flex-column-fluid">
    <div class="d-flex flex-column-fluid">
    	<div class="container">
    		<div id="payment_view"></div>
    	</div>
    </div>
</div>
@endsection

@section('custom_scripts')
    <script type="text/javascript" src="{{ asset('js/app.js') }}"></script>
@endsection