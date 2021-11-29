@extends('layouts.app')

@section('content')
<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
    <div class="d-flex flex-column-fluid">
        <div class="container">
            <div class="card card-custom col-12">
                <div class="card-header flex-wrap border-0 pt-6 pb-0">
                    <div class="card-title">
                        <h3 class="card-label">Edit Order
                            <div class="text-muted pt-2 font-size-sm">
                                Edit Order
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

                    <div id="edit_order"></div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('custom_scripts')
<script type="text/javascript">
	$('input[name=service_name]').val('{{ $order->service_name }}')
	$('select[name=status]').val('{{ $order->status }}')
	$('select[name=customer_id]').val('{{ $order->customer_id }}')
	$('input[name=amount]').val('{{ $order->amount }}')
	$('input[name=vat_size_percentage]').val('{{ $order->vat_size_percentage }}')
	$('select[name=payment_method]').val('{{ $order->payment_method }}')
</script>
@endsection