@extends('layouts.admin')

@section('title', 'Edit Expense')
@section('content-header', 'Edit Expense')
@section('content')
<div class="common__table">
    <form action="{{ route('expense.update',$expense) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="amount">Amount:</label>
            <input type="number" name="amount" class="form-control @error('amount') is-invalid @enderror" id="amount" placeholder="Enter Amount" value="{{ old('name', $expense->amount) }}" required>
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
                <option value="{{$cat->id}}" {{$expense->expense_categorie_id==$cat->id ? 'selected':''}}>{{$cat->name}}</option>
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
            <textarea name="note" class="form-control @error('note') is-invalid @enderror">{{$expense->note}}</textarea>
            @error('expense_categorie_id')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror
        </div>
        <button class="btn common__btn" type="submit">Update</button>
    </form>
</div>
@endsection