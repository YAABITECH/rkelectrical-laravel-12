@extends('layout.structure')

@section('xmt_tit', 'View All Testimonial Admin Page')
@section('xmt_des', 'View testimonials. Testimonial view admin page of RKElectricalGrid website.')
@section('xmt_rob', 'noindex, follow')



@section('content')
<div class="container">
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
    <form  method="POST" action="{{ route('test.banner.update',$banner->id) }}" enctype="multipart/form-data">
    @csrf  
    @method('PUT')
        <div class="py-3">
            <label class="form-label">Previous Image</label><br>
            <img src="{{URL::asset('/image/banner/thumb/'.$banner->photo)}}" class="img-fluid rounded" alt="" style="max-width:150px">
        </div>
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
