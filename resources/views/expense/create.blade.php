@extends('layouts.admin')

@section('title', 'Create Expense')
@section('content-header', 'Create Expense')
@section('content')
<div class="common__table">
    <form action="{{ route('expense.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="amount">Amount:</label>
            <input type="number" name="amount" class="form-control @error('amount') is-invalid @enderror" id="amount" placeholder="Enter Amount" value="{{ old('amount') }}" required>
            @error('amount')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror
        </div>
        <div class="form-group">
            <label for="expense_categorie_id">Expense Cetegory:</label>
            <select name="expense_categorie_id" class="form-control @error('expense_categorie_id') is-invalid @enderror">
                <option value="">Select Type</option>
                @foreach($category as $cat)
                <option value="{{$cat->id}}">{{$cat->name}}</option>
                @endforeach
            </select>
            @error('expense_categorie_id')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror
        </div>
        <div class="form-group">
            <label for="note">Expense Note:</label>
            <textarea name="note" class="form-control @error('note') is-invalid @enderror"></textarea>
            @error('expense_categorie_id')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror
        </div>
        <button class="btn common__btn" type="submit">Create</button>
    </form>
</div>
@endsection