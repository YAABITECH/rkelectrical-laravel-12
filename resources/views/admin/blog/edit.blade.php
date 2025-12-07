@extends('layout.admin.structure')
@section('xmt_tit', 'Edit Blog | Admin')

@push('headcss')
<script src="{{asset('/assets/tinymce/tinymce.min.js')}}" referrerpolicy="origin"></script>
<link rel="stylesheet" href="/css/tinymce-editor-v1-0.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.1.2/css/tempusdominus-bootstrap-4.min.css">
@endpush

@section('content')
<div class="container mt-3">
    <h1 class="text-center text-primary fs-5 lh-lg">Blog Edit</h1>
    <div class="text-center m-1 mb-3">
        <a class="btn btn-primary btn-sm" href="{{ route('admin.blog.index') }}" role="button"><i class="fa-solid fa-list"></i></a>
        <form action="{{ route('admin.blog.destroy', $blog->id) }}" method="post"
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
    <form method="post" action="{{ route('admin.blog.update',$blog->id) }}" enctype="multipart/form-data" id="artform">
        @csrf
        @method('PATCH')
        <p class="text-center">* fields are compulsory</p>
        <div class="form-group py-3">
            <label for="title" class="form-label">Blog Title *</label>
            <input type="text" class="form-control" name="title" id="title" oninput="getulinkt()" value="{{ old('title',$blog->title) }}" required>
        </div>
        <div class="form-group py-3">
            <label for="ulink" class="form-label">Ulink *</label>
            <input type="text" class="form-control" name="ulink" id="ulink" oninput="getulink(this.value)" aria-describedby="helpulink" value="{{ old('ulink',$blog->ulink) }}" required>
            <div class="form-text"><a href="{{config('app.url')}}/blog/{{ old('ulink',$blog->ulink) }}" id="helpulink" class="text-decoration-none" target="_blank">{{config('app.url')}}/blog/{{ old('ulink',$blog->ulink) }}</a></div>
        </div>
        <div class="py-3">
            <label class="form-label">Previous Image</label><br>
            <img src="{{URL::asset('/image/blog/thumb/'.$blog->image)}}" class="img-fluid rounded" alt="" style="max-width:150px">
        </div>
        <div class="form-group py-3">
            <label for="image" class="form-label">Update Image</label>
            <input type="file" class="form-control" name="image" id="image" aria-describedby="helpimage" accept="image/*">
            <div id="helpimage" class="form-text">Recommended ratio 16:9</div>
        </div>
        <div class="form-group py-3">
            <label for="content" class="form-label">Content *</label>
            <textarea class="yaTinyEditor" name="content" id="content" rows="5" placeholder="Enter your blog here" novalidate>{{ old('content',$blog->content) }}</textarea>
        </div>
        <div class="form-group py-3">
            <label for="mtit" class="form-label">Meta Title (SEO) *</label>
            <input type="text" class="form-control" name="mtit" id="mtit" value="{{ old('mtit',$blog->mtit) }}" required>
        </div>
        <div class="form-group py-3">
            <label for="mdes" class="form-label">Meta Description (SEO) *</label>
            <textarea class="form-control" name="mdes" id="mdes" rows="3" required>{{ old('mdes',$blog->mdes) }}</textarea>
        </div>
        <div class="py-3">
            <label class="form-label">Previous OG Image</label><br>
            <img src="{{URL::asset('/image/blog/ogimage/'.$blog->ogimage)}}" class="img-fluid rounded" alt="" style="max-width:150px">
        </div>
        <div class="form-group py-3">
            <label for="ogimage" class="form-label">OG Image</label>
            <input type="file" class="form-control" name="ogimage" id="ogimage" aria-describedby="helpogimage" accept="image/*">
            <div id="helpogimage" class="form-text">This image will be shown when you share your links to social media. Recommended ratio 1200:627</div>
        </div>
        <div class="form-group py-3">
            <label for="post_date" class="form-label">Post Date *</label>
            <div class="input-group date datetimepicker-input" id="postdatepicker" data-target-input="nearest">
                <input type="text" class="form-control datetimepicker-input"
                    id="post_date" name="post_date"
                    data-target="#postdatepicker"
                    value="{{ old('post_date', $blog->post_date ? $blog->post_date->format('d-m-Y') : '') }}">
                <div class="input-group-append" data-target="#postdatepicker" data-toggle="datetimepicker">
                    <div class="input-group-text" style="height: 100%;"><i class="far fa-calendar-alt text-primary"></i></div>
                </div>
            </div>
        </div>
        <div class="form-group py-3">
            <label for="author" class="form-label">Author *</label>
            <input type="text" class="form-control" name="author" id="author" value="{{ old('author',$blog->author) }}" required>
        </div>
        <div class="form-group py-3">
            <label class="form-label">Status</label>
            <select class="form-select" name="status" id="status">
                @foreach(['active'=>'Active','draft'=>'Draft','archive'=>'Archive'] as $k=>$v)
                    <option value="{{ $k }}" {{ old('status', $blog->status) == $k ? 'selected' : '' }}>{{ $v }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group py-3">
            <button type="submit" class="btn btn-block btn-primary">Update Blog</button>
        </div>
    </form>
    <div>
@endsection
@push('endjs')
<script src="/js/ya-tiny-editor-v1-0.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.1.2/js/tempusdominus-bootstrap-4.min.js"></script>
<script>
    $(function () {
        $('.datetimepicker-input').datetimepicker({
            format: 'DD-MM-YYYY',
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

        $('#post_date').on('focus', function () {
            $('#postdatepicker').datetimepicker('show');
        });
    });
</script>
<script>
    function getulinkt()
    {
        title = document.getElementById("title").value;
        temp = toseourl(title);
        document.getElementById("ulink").value=temp;
        document.getElementById("helpulink").innerHTML="{{ config('app.url') }}/blog/"+temp;
    }
    function getulink(val)
	{
        temp = toseourl(val);
        document.getElementById("ulink").value=temp;
        document.getElementById("helpulink").innerHTML="{{ config('app.url') }}/blog/"+temp;
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