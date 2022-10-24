@extends('layouts.admin')

@section('title', 'Open POS')

@section('content')
<style>
    @media print {

        html,
        body {
            page-break-after: auto;
        }

        td,
        th,
        tr,
        table {
            border-top: 1px solid black;
            border-collapse: collapse;
        }

        table {
            width: 80%;
            margin: 0 auto;
        }

        td.description,
        th.description {
            width: 75px;
            max-width: 75px;
        }

        td.quantity,
        th.quantity {
            width: 40px;
            max-width: 40px;
            word-break: break-all;
        }

        td.price,
        th.price {
            width: 40px;
            max-width: 40px;
            word-break: break-all;
        }

        .centered {
            text-align: center;
            align-content: center;
        }

        .printdev {
            width: 1000px;
            max-width: 1000px;
        }
    }
</style>
<input type="hidden" id="gst" name="gst" value="{{$gstdata->value}}" />
<div id="cart"></div>

@endsection