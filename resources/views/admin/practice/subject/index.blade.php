@extends('layout.admin.structure')
@section('xmt_tit', 'Practice Subject | Admin')

@push('headcss')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.1.2/css/tempusdominus-bootstrap-4.min.css" />
@endpush

@section('content')
    <div class="container mt-3">
        <h1 class="text-center text-primary fs-5 lh-lg">Practice Subject List</h1>
        <div class="text-center m-1 mb-3">
            <a class="btn btn-primary btn-sm" href="{{ route('admin.practice.subject.create') }}" role="button"><i class="fa-solid fa-plus"></i></a>
            <a class="btn btn-primary btn-sm" href="{{ route('admin.practice.subject.arrange') }}" role="button"><i class="fa-solid fa-layer-group"></i></a>
            <a class="btn btn-primary btn-sm" href="{{ route('admin') }}" role="button"><i class="fa-solid fa-home"></i></a>
        </div>
        @if ($errors->any())
            {!! implode('', $errors->all('<div class="alert alert-danger" role="alert">:message</div>')) !!}
        @endif
        @if (session('success'))
            <div class="alert alert-success">
            {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">
            {{ session('error') }}
            </div>
        @endif
        <div class="card shadow border-white rounded">
            <div class="extnd600">
                <table class="table table-light table-striped table-borderless m-0">
                    <thead>
                        <tr class="table-primary fw-bold">
                            <td>S.No</td>
                            <td>Subject Name</td>
                            <td>Status</td>
                            <td class="text-center" style="min-width:100px">Action</td>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $s_no=1;
                        @endphp
                        @foreach ($subjects as $subject)
                            <tr>
                                <td>{{ $s_no++ }}</td>
                                <td>{{ $subject->name }}</td>
                                <td>{{ $subject->status }}</td>
                                <td class="text-center">
                                    <a href="{{ route('admin.practice.subject.edit', $subject->id) }}" class="btn btn-success btn-sm"><i class="fa-solid fa-edit"></i></a>
                                    <form action="{{ route('admin.practice.subject.destroy', $subject->id) }}" method="post"
                                        style="display: inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-danger btn-sm" type="submit"><i class="fa-solid fa-xmark"></i></button>
                                    </form>
                                    <a href="/admin/practice/topic?subject={{$subject->id}}" class="btn btn-primary btn-sm"><i class="fa-regular fa-rectangle-list"></i></a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('endjs')
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.1.2/js/tempusdominus-bootstrap-4.min.js"></script>
<script>
    $(function () {
        $('.datetimepicker-input').datetimepicker({
            icons: {
                time: 'far fa-clock',
                date: 'far fa-calendar-alt',
                up: 'fas fa-arrow-up',
                down: 'fas fa-arrow-down',
                previous: 'fas fa-chevron-left',
                next: 'fas fa-chevron-right',
                today: 'fas fa-calendar-day',
                clear: 'far fa-trash-alt',
                close: 'far fa-times-circle'
            },
            useCurrent: false
        });

        $('#date_from').on('focus', function () {
            $('#datetimepicker1').datetimepicker('show');
        });
        $('#date_to').on('focus', function () {
            $('#datetimepicker2').datetimepicker('show');
        });
    });
</script>
@endpush
