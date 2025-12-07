@extends('layout.admin.structure')
@section('xmt_tit', 'Edit Notification | Admin')

@section('content')
<div class="container mt-3">
    <h1 class="text-center text-primary fs-5 lh-lg">Edit Notification</h1>
    <div class="text-center m-1 mb-3">
        <a class="btn btn-primary btn-sm" href="{{ route('admin.notification.index') }}" role="button"><i class="fa-solid fa-list"></i></a>
        <form action="{{ route('admin.notification.destroy', $notification->id) }}" method="post"
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
    <form method="post" action="{{ route('admin.notification.update',$notification->id) }}" enctype="multipart/form-data">
        @csrf
        @method('PATCH')
        <p class="text-center">* fields are compulsory</p>
        <div class="form-group py-3">
            <label for="title" class="form-label">Notification Title *</label>
            <input type="text" class="form-control" name="title" id="title" value="{{ old('title',$notification->title) }}" required>
        </div>
        <div class="form-group py-3">
            <label for="body" class="form-label">Message / Body *</label>
            <textarea class="form-control" name="body" id="body" rows="3" required>{{ old('body',$notification->body) }}</textarea>
        </div>
        <div class="py-3">
            <label class="form-label">Previous Icon</label><br>
            <img src="{{URL::asset('/image/notification/icon/'.$notification->icon)}}" class="img-fluid rounded" alt="" style="max-width:150px">
        </div>
        <div class="form-group py-3">
            <label for="icon" class="form-label">Icon</label>
            <input type="file" class="form-control" name="icon" id="icon" accept="image/*">
        </div>
        <div class="py-3">
            <label class="form-label">Previous Badge</label><br>
            <img src="{{URL::asset('/image/notification/badge/'.$notification->badge)}}" class="img-fluid rounded" alt="" style="max-width:150px">
        </div>
        <div class="form-group py-3">
            <label for="badge" class="form-label">Badge</label>
            <input type="file" class="form-control" name="badge" id="badge" accept="image/*">
        </div>
        <div class="form-group mb-3">
            <div class="form-check">
                <input type="checkbox" class="form-check-input" name="persistent" id="persistent" value="0"
                    @if(old('persistent',$notification->persistent) == 1)
                        checked
                    @endif
                >
                <label for="persistent" class="form-check-label">Persistent</label>
            </div>
        </div>
        <div class="py-3">
            <label class="form-label">Previous Image</label><br>
            <img src="{{URL::asset('/image/notification/'.$notification->image)}}" class="img-fluid rounded" alt="" style="max-width:150px">
        </div>
        <div class="form-group py-3">
            <label for="image" class="form-label">Image</label>
            <input type="file" class="form-control" name="image" id="image" accept="image/*">
        </div>
        <div class="form-group py-3">
            <button type="submit" class="btn btn-block btn-primary">Update Category</button>
        </div>
    </form>
<div>
@endsection