@extends('layouts.admin')

@section('title', 'Open POS')

@section('content')
<style>
    @media print {
        html, body {
        width: 80mm;
        height:100%;
        position:absolute;
       }
       .paper-size {
            width: 76mm !important;
        }
         td,
        th,
        tr,
        table {
            border-top: 1px solid black;
            border-collapse: collapse;
        }



        td.description,
        th.description {
            width: 100px;
            max-width: 100px;
        }

        td.quantity,
        th.quantity {
            width: 40px;
            max-width: 40px;
            word-break: break-all;
        }

        td.price,
        th.price {
            width: 80px;
            max-width: 80px;
            word-break: break-all;
        }

        .centered {
            text-align: center;
            align-content: center;
        }

        .ticket {
            width: 210px;
            max-width: 210px;
        }
      }
    
</style>
<input type="hidden" id="gst" name="gst" value="{{$gstdata->value}}" />
<div id="cart"></div>

@endsection