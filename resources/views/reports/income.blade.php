@extends('layouts.admin')

@section('title', 'Income Report')
@section('content-header', 'Income Report')
@section('content-actions')

@endsection
@section('css')

@endsection
@section('content')
<div class="common__table ">
    <div class="container">
        <div class="row mb-4">
            <div class="col-md-6"></div>
            <div class="col-md-6">
                <form action="{{route('report.income')}}">
                    <div class="row">
                        <div class="col-md-5">
                            <input type="date" name="start_date" class="form-control" value="{{request('start_date')}}" />
                        </div>
                        <div class="col-md-5">
                            <input type="date" name="end_date" class="form-control" value="{{request('end_date')}}" />
                        </div>
                        <div class="col-md-2">
                            <button class="btn common__btn" type="submit">Submit</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="table-responsive">
        <h4>From:{{request('start_date') ?? date('Y-m-d')}} To:{{request('end_date') ?? date('Y-m-d', strtotime('today - 30 days'))}}</h4>
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Product Name</th>
                    <th>Table No</th>
                    <th>Qty</th>
                    <th>Unit price</th>
                    <th>Sub Total</th>
                    <th>Created_by</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                @php $total=0;@endphp
                @foreach ($data as $dt)
                <tr>
                    <td>{{$dt->id}}</td>
                    <td>{{$dt->product->name}}</td>
                    <td>{{$dt->order->customer->table_no}}</td>
                    <td>{{$dt->quantity}}</td>
                    <td>{{ config('settings.currency_symbol') }} {{$dt->product->price}}</td>
                    <td>
                        {{ config('settings.currency_symbol') }} {{$dt->product->price*$dt->quantity}}
                        @php $total=$total+$dt->product->price*$dt->quantity;@endphp
                    </td>
                    <td>{{$dt->order->user->first_name}}</td>
                    <td>{{$dt->created_at}}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <th></th>
                    <th></th>
                    @php $gstamount=0;@endphp
                    @foreach ($data as $dt)
                    @php
                    if($dt->order->payments[0]->gst){
                    $amont=$dt->order->payments[0]->amount;
                    $gstamount=$gstamount+$amont/100*$dt->order->payments[0]->gst;
                    }
                    @endphp
                    @endforeach

                    <th>Gst Amount: {{ config('settings.currency_symbol').$gstamount}}</th>
                    <th>
                    </th>
                    <th>Total</th>
                    <th>{{ config('settings.currency_symbol') }} {{$total+$gstamount}}</th>
                    <th></th>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
@endsection

@section('js')

@endsection