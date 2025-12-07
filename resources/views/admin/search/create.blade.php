@extends('layout.admin.structure')
@section('xmt_tit', 'Create Search | Admin')

@section('content')
<div class="container mt-3">
    <h1 class="text-center text-primary fs-5 lh-lg">Create Search</h1>
    <div class="text-center m-1 mb-3">
        <a class="btn btn-primary btn-sm" href="{{ route('admin.search.index') }}" role="button"><i class="fa-solid fa-list"></i></a>
        <a class="btn btn-primary btn-sm" href="{{ route('admin') }}" role="button"><i class="fa-solid fa-home"></i></a>
    </div>
    @if ($errors->any())
        {!! implode('', $errors->all('<div class="alert alert-danger" role="alert">:message</div>')) !!}
    @endif
    @if (session('error'))
        <div class="alert alert-danger">
        {{ session('error') }}
        </div>
    @endif
    <form method="post" action="{{ route('admin.search.store') }}" enctype="multipart/form-data">
        @csrf
        <p class="text-center">* fields are compulsory</p>
        <div class="form-group py-3">
            <label for="title" class="form-label">Search Title *</label>
            <input type="text" class="form-control" name="title" id="title" value="{{ old('title') }}" required>
        </div>
        <div class="form-group py-3">
            <label for="url" class="form-label">Search URL *</label>
            <input type="text" class="form-control" name="url" id="url" value="{{ old('url') }}" required oninput="showurl()">
            <div id="showmyurl" class="form-text">{{ config('app.url') }}{{ old('url') }}</div>
        </div>
        <div class="form-group py-3">
            <button type="submit" class="btn btn-block btn-primary">Create Search</button>
        </div>
    </form>
<div>
@endsection
@push('endjs')
    <script>
        function showurl() {
            var url = $('#url').val();
            var url = '{{ config('app.url') }}'+ url;
            $('#showmyurl').html(url);
        }
    </script>
@endpush