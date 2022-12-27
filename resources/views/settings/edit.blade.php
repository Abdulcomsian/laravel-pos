@extends('layouts.admin')

@section('title', 'Update Settings')
@section('content-header', 'Update Settings')

@section('content')

<div class="common__table">
    <form action="{{ route('settings.store') }}" method="post">
        @csrf

        <div class="form-group">
            <label for="app_name">App name</label>
            <input type="text" name="app_name" class="form-control @error('app_name') is-invalid @enderror" id="app_name" placeholder="App name" value="{{ old('app_name', config('settings.app_name')) }}">
            @error('app_name')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror
        </div>

        <div class="form-group">
            <label for="app_description">App description</label>
            <textarea name="app_description" class="form-control @error('app_description') is-invalid @enderror" id="app_description" placeholder="App description">{{ old('app_description', config('settings.app_description')) }}</textarea>
            @error('app_description')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror
        </div>

        <div class="form-group">
            <label for="currency_symbol">Currency symbol</label>
            <input type="text" name="currency_symbol" class="form-control @error('currency_symbol') is-invalid @enderror" id="currency_symbol" placeholder="Currency symbol" value="{{ old('currency_symbol', config('settings.currency_symbol')) }}">
            @error('currency_symbol')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror
        </div>
        <div class="form-group">
            <label for="warning_quantity">Warning quantity</label>
            <input type="text" name="warning_quantity" class="form-control @error('warning_quantity') is-invalid @enderror" id="warning_quantity" placeholder="Warning quantity" value="{{ old('warning_quantity', config('settings.warning_quantity')) }}">
            @error('warning_quantity')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror
        </div>
        <div class="form-group">
            <label for="gst">GST%</label>
            <input type="text" name="gst" class="form-control @error('gst') is-invalid @enderror" id="gst" placeholder="Enter Tax in percentage" value="{{ old('gst', config('settings.gst')) }}">
            @error('gst')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror
        </div>
        <div class="form-group">
            <label for="pos_charges">POS Charges%</label>
            <input type="text" name="pos_charges" class="form-control @error('pos_charges') is-invalid @enderror" id="pos_charges" placeholder="Enter Tax in percentage" value="{{ old('gst', config('settings.pos_charges')) }}">
            @error('pos_charges')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror
        </div>
        <button type="submit" class="btn common__btn">Change Setting</button>
    </form>
</div>

@endsection