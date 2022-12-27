@extends('layouts.admin')

@section('title', 'Open POS')

@section('content')
<style>
    @media print {

        html,
        body {
            page-break-after: auto;
            height: 99%;
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
            width: 100%;
            height: auto;
        }


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
        width: 100%;
        height: auto;
    }
</style>
<input type="hidden" id="gst" name="gst" value="{{$gstdata->value ?? ''}}" />
<input type="hidden" id="posCharges" value="{{$posCharges->value ?? ''}}" />
<div id="cart"></div>

@endsection