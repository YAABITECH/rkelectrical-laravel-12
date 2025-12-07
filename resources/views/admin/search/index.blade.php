@extends('layout.admin.structure')
@section('xmt_tit', 'Search | Admin')

@section('content')
    <div class="container mt-3">
        <h1 class="text-center text-primary fs-5 lh-lg">Search List</h1>
        <div class="text-center m-1 mb-3">
            <a class="btn btn-primary btn-sm" href="{{ route('admin.search.create') }}" role="button"><i class="fa-solid fa-plus"></i></a>
            {{-- <a class="btn btn-primary btn-sm" href="{{ route('admin.search.arrange') }}" role="button"><i class="fa-solid fa-layer-group"></i></a> --}}
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
                            <td>Page Title</td>
                            <td class="text-center" style="min-width:100px">Action</td>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $s_no=1;
                        @endphp
                        @foreach ($searches as $search)
                            <tr>
                                <td>{{ $search->title }}</td>
                                <td class="text-center">
                                    <a href="{{ route('admin.search.edit', $search->id) }}" class="btn btn-success btn-sm"><i class="fa-solid fa-edit"></i></a>
                                    <form action="{{ route('admin.search.destroy', $search->id) }}" method="post"
                                        style="display: inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-danger btn-sm" type="submit"><i class="fa-solid fa-xmark"></i></button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
