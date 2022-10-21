@extends('layouts.admin')

@section('title', 'Create Category')
@section('content-header', 'Create Category')
@section('content')
<div class="common__table">
    <form action="{{ route('expensecategory.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="first_name">Name:</label>
            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" id="name" placeholder="Enter Category Name" value="{{ old('name') }}" required>
            @error('name')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror
        </div>
        <button class="btn common__btn" type="submit">Create</button>
    </form>
</div>
@endsection