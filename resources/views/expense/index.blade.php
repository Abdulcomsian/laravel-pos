@extends('layouts.admin')

@section('title', 'Expense List')
@section('content-header', 'Expense List')
@section('content-actions')
<a href="{{route('expense.create')}}" class="btn common__btn">Add Expense</a>
@endsection
@section('css')
<link rel="stylesheet" href="{{ asset('plugins/sweetalert2/sweetalert2.min.css') }}">
@endsection
@section('content')

<div class="common__table table-responsive">
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Amount</th>
                <th>Category Name</th>
                <th>Note</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($expenses as $exp)
            <tr>
                <td>{{$exp->id}}</td>
                <td>{{$exp->amount}}</td>
                <td>{{$exp->category->name}}</td>
                <td>{{$exp->note}}</td>
                <td>
                    <a href="{{ route('expense.edit', $exp) }}" class="btn btn-primary"><i class="fas fa-edit"></i></a>
                    <button class="btn btn-danger btn-delete" data-url="{{route('expense.destroy', $exp)}}"><i class="fas fa-trash"></i></button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    {{ $expenses->render() }}


</div>

@endsection

@section('js')
<script src="{{ asset('plugins/sweetalert2/sweetalert2.min.js') }}"></script>
<script>
    $(document).ready(function() {
        $(document).on('click', '.btn-delete', function() {
            $this = $(this);
            const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: 'btn btn-success',
                    cancelButton: 'btn btn-danger'
                },
                buttonsStyling: false
            })

            swalWithBootstrapButtons.fire({
                title: 'Are you sure?',
                text: "Do you really want to delete this customer?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'No',
                reverseButtons: true
            }).then((result) => {
                if (result.value) {
                    $.post($this.data('url'), {
                        _method: 'DELETE',
                        _token: '{{csrf_token()}}'
                    }, function(res) {
                        $this.closest('tr').fadeOut(500, function() {
                            $(this).remove();
                        })
                    })
                }
            })
        })
    })
</script>
@endsection