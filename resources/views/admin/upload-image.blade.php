@extends('layout.admin.structure')
@section('xmt_tit', 'Upload Image | Admin')

@section('content')
<div class="container mt-3">
    <h1 class="text-center text-primary fs-5 lh-lg">Upload Image</h1>
    <div class="text-center m-1 mb-3">
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
    <form method="post" action="{{ route('admin.store-image') }}" enctype="multipart/form-data">
        @csrf
        <p class="text-center">* fields are compulsory</p>
        <div class="form-group py-3">
            <label for="image" class="form-label">Featured Image *</label>
            <input type="file" class="form-control" name="image" id="image" aria-describedby="helpimage" accept="image/*" required>
            <div id="helpimage" class="form-text">Recommended ratio 16:9</div>
        </div>
        <div class="form-group py-3">
            <label for="file_name" class="form-label">File Name*</label>
            <input type="text" class="form-control" name="file_name" id="file_name" value="{{ old('file_name') }}" required>
        </div>
        <div class="form-group py-3">
            <label for="width" class="form-label">Width*</label>
            <input type="text" class="form-control" name="width" id="width" value="{{ old('width') }}" required>
        </div>
        <div class="form-group py-3">
            <label for="folder" class="form-label">Folder Name*</label>
            <input type="text" class="form-control" name="folder" id="folder" value="{{ old('folder') }}" required>
        </div>
        <div class="form-group py-3">
            <button type="submit" class="btn btn-block btn-primary">Upload Image</button>
        </div>
    </form>
<div>
@endsection
