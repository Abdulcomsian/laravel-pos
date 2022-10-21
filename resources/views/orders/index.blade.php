@extends('layouts.admin')

@section('title', 'Orders List')
@section('content-header', 'Order List')
@section('content-actions')
<a href="{{route('cart.index')}}" class="btn common__btn">Open POS</a>
@endsection

@section('content')


<div class="common__table">
    <div class="container">
        <div class="row mb-4">
            <div class="col-md-6"></div>
            <div class="col-md-6">
                <form action="{{route('orders.index')}}">
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
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Table No</th>
                    <th>Sub Total</th>
                    <th>GST%</th>
                    <th>Gst Amount</th>
                    <th>Total</th>
                    <!-- <th>Received Amount</th> -->
                    <th>Status</th>
                    <th>To Pay</th>
                    <th>Created At</th>
                </tr>
            </thead>
            <tbody>
                @php $totalgstamount=0;@endphp
                @foreach ($orders as $order)
                <tr>
                    <td>{{$order->id}}</td>
                    <td>{{$order->custome->table_no}}</td>
                    <td>{{ config('settings.currency_symbol') }} {{$order->formattedTotal()}}</td>
                    <td>{{$order->gst}}</td>
                    <td>
                        @php
                        $gstamount=$order->total()/100*$order->gst;
                        $totalgstamount=$totalgstamount+$gstamount;
                        @endphp
                        {{ config('settings.currency_symbol') }} {{$gstamount}}
                    </td>
                    <td>{{ config('settings.currency_symbol') }} {{ number_format($order->total()+$gstamount, 2);}}</td>
                    <!-- <td>{{ config('settings.currency_symbol') }} {{$order->formattedReceivedAmount()}}</td> -->
                    <td>
                        @if($order->receivedAmount() == 0)
                        <span class="badge badge-danger">Not Paid</span>
                        @elseif($order->receivedAmount() < $order->total())
                            <span class="badge badge-warning">Partial</span>
                            @elseif($order->receivedAmount() == $order->total())
                            <span class="badge badge-success">Paid</span>
                            @elseif($order->receivedAmount() > $order->total())
                            <span class="badge badge-info">Change</span>
                            @endif
                    </td>
                    <td>{{config('settings.currency_symbol')}} {{number_format($order->total() - $order->receivedAmount(), 2)}}</td>
                    <td>{{$order->created_at}}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <th></th>
                    <th></th>
                    <th>{{ config('settings.currency_symbol') }} {{ number_format($total, 2) }}</th>
                    <th></th>
                    <th>{{ config('settings.currency_symbol') }} {{$totalgstamount}}</th>
                    <th>{{ config('settings.currency_symbol') }} {{ number_format($total+$totalgstamount, 2) }}</th>
                    <!-- <th>{{ config('settings.currency_symbol') }} {{ number_format($receivedAmount, 2) }}</th> -->
                    <th></th>
                    <th></th>
                    <th></th>
                </tr>
            </tfoot>
        </table>
    </div>

</div>

{{ $orders->render() }}

@endsection