@extends('layout.admin.structure')
@section('xmt_tit', 'Create Notification | Admin')

@section('content')
<div class="container mt-3">
    <h1 class="text-center text-primary fs-5 lh-lg">Create Notification</h1>
    <div class="text-center m-1 mb-3">
        <a class="btn btn-primary btn-sm" href="{{ route('admin.notification.index') }}" role="button"><i class="fa-solid fa-list"></i></a>
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
    <form method="post" action="{{ route('admin.notification.store') }}" enctype="multipart/form-data">
        @csrf
        <p class="text-center">* fields are compulsory</p>
        <div class="form-group py-3">
            <label for="title" class="form-label">Notification Title *</label>
            <input type="text" class="form-control" name="title" id="title" value="{{ old('title') }}" required>
        </div>
        <div class="form-group py-3">
            <label for="body" class="form-label">Message / Body *</label>
            <textarea class="form-control" name="body" id="body" rows="3" required>{{ old('body') }}</textarea>
        </div>
        <div class="form-group py-3">
            <label for="icon" class="form-label">Icon</label>
            <input type="file" class="form-control" name="icon" id="icon" accept="image/*">
        </div>
        <div class="form-group py-3">
            <label for="badge" class="form-label">Badge</label>
            <input type="file" class="form-control" name="badge" id="badge" accept="image/*">
        </div>
        <div class="form-group mb-3">
            <div class="form-check">
                <input type="checkbox" class="form-check-input" name="persistent" id="persistent" value="0"
                    @if(old('persistent') == 1)
                        checked
                    @endif
                >
                <label for="persistent" class="form-check-label">Persistent</label>
            </div>
        </div>
        <div class="form-group py-3">
            <label for="image" class="form-label">Image</label>
            <input type="file" class="form-control" name="image" id="image" accept="image/*">
        </div>
        <div class="form-group py-3">
            <button type="submit" class="btn btn-block btn-primary">Create Notification</button>
        </div>
    </form>
<div>
@endsection