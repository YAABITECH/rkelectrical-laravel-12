@extends('layout.admin.structure')
@section('xmt_tit', 'Edit Notification Subscriber | Admin')

@section('content')
<div class="container mt-3">
    <h1 class="text-center text-primary fs-5 lh-lg">Edit Notification Subscriber</h1>
    <div class="text-center m-1 mb-3">
        <a class="btn btn-primary btn-sm" href="{{ route('admin.notification.subscriber.index') }}" role="button"><i class="fa-solid fa-list"></i></a>
        <form action="{{ route('admin.notification.subscriber.destroy', $subscriber->id) }}" method="post"
            style="display: inline-block">
            @csrf
            @method('DELETE')
            <button class="btn btn-danger btn-sm" type="submit"><i class="fa-solid fa-xmark"></i></button>
        </form>
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
    <form method="post" action="{{ route('admin.notification.subscriber.update',$subscriber->id) }}" enctype="multipart/form-data">
        @csrf
        @method('PATCH')
        <p class="text-center">* fields are compulsory</p>
        <div class="form-group py-3">
            <label for="name" class="form-label">Subscriber Name *</label>
            <input type="text" class="form-control" name="name" id="name" oninput="getulinkt()" value="{{ old('name',$subscriber->name) }}" required>
        </div>
        <div class="form-group py-3">
            <label for="ulink" class="form-label">Ulink *</label>
            <input type="text" class="form-control" name="ulink" id="ulink" oninput="getulink(this.value)" aria-describedby="helpulink" value="{{ old('ulink',$subscriber->ulink) }}" required>
            <div class="form-text"><a href="{{config('app.url')}}/notification/subscriber/{{ old('ulink',$subscriber->ulink) }}" id="helpulink" class="text-decoration-none" target="_blank">{{config('app.url')}}/notification?subscriber={{ old('ulink',$subscriber->ulink) }}</a></div>
        </div>
        <div class="py-3">
            <label class="form-label">Previous Image</label><br>
            <img src="{{URL::asset('/image/notification/subscriber/thumb/'.$subscriber->image)}}" class="img-fluid rounded" alt="" style="max-width:150px">
        </div>
        <div class="form-group py-3">
            <label for="image" class="form-label">Update Image</label>
            <input type="file" class="form-control" name="image" id="image" aria-describedby="helpimage" accept="image/*">
            <div id="helpimage" class="form-text">Recommended ratio 16:9</div>
        </div>
        <div class="form-group py-3">
            <label for="mtit" class="form-label">Meta Title (SEO) *</label>
            <input type="text" class="form-control" name="mtit" id="mtit" value="{{ old('mtit',$subscriber->mtit) }}" required>
        </div>
        <div class="form-group py-3">
            <label for="mdes" class="form-label">Meta Description (SEO) *</label>
            <textarea class="form-control" name="mdes" id="mdes" rows="3" required>{{ old('mdes',$subscriber->mdes) }}</textarea>
        </div>
        <div class="py-3">
            <label class="form-label">Previous OG Image</label><br>
            <img src="{{URL::asset('/image/notification/subscriber/ogimage/'.$subscriber->ogimage)}}" class="img-fluid rounded" alt="" style="max-width:150px">
        </div>
        <div class="form-group py-3">
            <label for="ogimage" class="form-label">OG Image</label>
            <input type="file" class="form-control" name="ogimage" id="ogimage" aria-describedby="helpogimage" accept="image/*">
            <div id="helpogimage" class="form-text">This image will be shown when you share your links to social media. Recommended ratio 1200:627</div>
        </div>
        <div class="form-group py-3">
            <button type="submit" class="btn btn-block btn-primary">Update Subscriber</button>
        </div>
    </form>
<div>
@endsection
@push('endjs')
<script>
    function getulinkt()
    {
        title = document.getElementById("name").value;
        temp = toseourl(title);
        document.getElementById("ulink").value=temp;
        document.getElementById("helpulink").innerHTML="{{config('app.url')}}/notification/subscriber/"+temp;
    }
    function getulink(val)
	{
        temp = toseourl(val);
        document.getElementById("ulink").value=temp;
        document.getElementById("helpulink").innerHTML="{{config('app.url')}}/notification/subscriber/"+temp;
	}
    function toseourl(url)
    {
        return url.toString()
        .normalize('NFD')
        .replace(/[\u0300-\u036f]/g,'')
        .replace(/\s+/g,'-')
        .toLowerCase()
        .replace(/&/g,'-and-')
        .replace(/[^a-z0-9\-]/g,'')
        .replace(/-+/g,'-')
        .replace(/^-*/,'')
        .replace(/-*$/,'');
    }
</script>
@endpush
