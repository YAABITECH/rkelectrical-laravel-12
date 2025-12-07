@extends('layout.admin.structure')
@section('xmt_tit', 'Create Banner | Admin')

@section('content')
<div class="container mt-3">
<br>
<p class="text-center" style="line-height:3rem">
    <a href="" onclick="history.back(); return false;" class="btn btn-outline-secondary right">Back</a>
    <a href="/admin/banner/" class="btn btn-info">All Banner</a>
    <a href="/admin/" class="btn btn-dark">Admin Home</a>
</p>
<br>
<h1 style="font-size:1.4rem; text-align:center;" class="text-success">Add Banner</h1>
@if ($errors->any())
        {!! implode('', $errors->all('<div class="alert alert-danger" role="alert">:message</div>')) !!}
    @endif
<form method="POST" action="{{ route('admin.banner.store') }}" enctype="multipart/form-data">
    @csrf
        <div class="form-group py-3">
            <label for="photo" class="form-label">Banner Photo *</label>
            <input type="file" class="form-control" name="photo" id="photo" aria-describedby="helpimage" accept="image/*">
            <div id="helpimage" class="form-text">Keep the ratio same for all banners. Ratio should be 3x1 (Ex: 1200px x 400px)</div>
        </div>
        <div class="form-group py-3">
            <button type="submit" class="btn btn-success">Submit</button>
        </div>
        <p class="text-right font-italic text-secondary">Keep the ratio same for all banners. Ratio should be 3x1 (Ex: 1200px x 400px).</p>
    </form>
    <br>
</div>
@endsection
