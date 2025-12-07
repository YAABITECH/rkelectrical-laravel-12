@extends('layout.admin.structure')
@section('xmt_tit', 'Create Course | Admin')

@push('headcss')
<script src="{{URL::asset('/assets/tinymce/tinymce.min.js')}}" referrerpolicy="origin"></script>
<style>
    .tox-statusbar__branding, .tox-promotion-link
    {
        display:none;
        visibility: hidden;
    }
</style>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.1.2/css/tempusdominus-bootstrap-4.min.css">
@endpush

@section('content')
<div class="container mt-3">
    <h1 class="text-center text-primary fs-5 lh-lg">Course Create</h1>
    <div class="text-center m-1 mb-3">
        <a class="btn btn-primary btn-sm" href="{{ route('admin.course.index') }}" role="button"><i class="fa-solid fa-list"></i></a>
        <a class="btn btn-primary btn-sm" href="{{ route('admin') }}" role="button"><i class="fa-solid fa-home"></i></a>
    </div>
    @if ($errors->any())
        {!! implode('', $errors->all('<div class="alert alert-danger" role="alert">:message</div>')) !!}
    @endif

    <form method="post" action="{{ route('admin.course.store') }}" enctype="multipart/form-data" autocomplete="off">
        @csrf
        <p class="text-center">* fields are compulsory</p>
        <div class="form-group py-3">
            <label for="title" class="form-label">Course Title *</label>
            <input type="text" class="form-control" name="title" id="title" oninput="geturlt()" value="{{ old('title') }}" required>
        </div>
        <div class="form-group py-3">
            <label for="url" class="form-label">Unique Link *</label>
            <input type="text" class="form-control" name="url" id="url" oninput="geturl(this.value)" aria-describedby="helpurl" value="{{ old('url') }}" required>
            <div id="helpurl" class="form-text">{{config('app.url')}}/course/</div>
        </div>
        <div class="form-group py-3">
            <label for="photo" class="form-label">Featured Image *</label>
            <input type="file" class="form-control" name="photo" id="photo" aria-describedby="helpimage" accept="image/*" required>
            <div id="helpimage" class="form-text">Recommended ratio 16:9</div>
        </div>
        <div class="form-group py-3">
            <label for="preview" class="form-label">Preview Image *</label>
            <input type="file" class="form-control" name="preview" id="preview" aria-describedby="previewhelp" accept="image/*" required>
            <div id="previewhelp" class="form-text">Recommended ratio 16:9</div>
        </div>
        <div class="form-group py-3">
            <label for="description" class="form-label">Description *</label>
            <textarea class="form-control" name="description" id="description" rows="3" required>{{ old('description') }}</textarea>
        </div>
        <div class="form-group py-3">
            <label for="fees" class="form-label">Fees *</label>
            <input type="number" class="form-control" name="fees" id="fees" min="0" step="0.01" value="{{ old('fees') }}" required>
        </div>
        <div class="form-group py-3">
            <label for="coursedur" class="form-label">Course Duration *</label>
            <input type="text" class="form-control" name="coursedur" id="coursedur" value="{{ old('coursedur') }}" required>
        </div>
        <div class="form-group py-3">
            <label for="classdur" class="form-label">Class Duration *</label>
            <input type="text" class="form-control" name="classdur" id="classdur" value="{{ old('classdur') }}" required>
        </div>
        <div class="form-group py-3">
            <label for="course_pack" class="form-label">Course Pack *</label>
            <input type="number" class="form-control" name="course_pack" id="course_pack" value="{{ old('course_pack',1) }}" required>
        </div>
        <div class="form-group py-3">
            <label for="metatitle" class="form-label">Meta Title (SEO) *</label>
            <input type="text" class="form-control" name="metatitle" id="metatitle" value="{{ old('metatitle') }}" required>
        </div>
        <div class="form-group py-3">
            <label for="metadesc" class="form-label">Meta Description (SEO) *</label>
            <textarea class="form-control" name="metadesc" id="metadesc" rows="3" required>{{ old('metadesc') }}</textarea>
        </div>
        <div class="form-group py-3">
            <button type="submit" class="btn btn-block btn-primary">Create Course</button>
        </div>
    </form>
<div>
@endsection
@push('endjs')
<script>
    function geturlt()
    {
        title = document.getElementById("title").value;
        temp = toseourl(title);
        document.getElementById("url").value=temp;
        document.getElementById("helpurl").innerHTML="{{config('app.url')}}/course/"+temp;
    }
    function geturl(val)
	{
        temp = toseourl(val);
        document.getElementById("url").value=temp;
        document.getElementById("helpurl").innerHTML="{{config('app.url')}}/course/"+temp;
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
<script>
    var editor_config = {
        selector: 'textarea#',
        content_css: '/css/tinycustom.css',
        path_absolute : "/",
        Remove_Powered_By: true,
        menubar: 'edit insert view format table',
        plugins: 'advlist autolink lists link image charmap hr anchor searchreplace wordcount code fullscreen insertdatetime media save table contextmenu directionality paste textcolor colorpicker textpattern emoticons',
        toolbar: 'undo redo | bold italic strikethrough forecolor backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image media | fullscreen code emoticons',
        relative_urls: false,
        image_dimensions: false,
        file_picker_callback : function(callback, value, meta) {
            var x = window.innerWidth || document.documentElement.clientWidth || document.getElementsByTagName('body')[0].clientWidth;
            var y = window.innerHeight|| document.documentElement.clientHeight|| document.getElementsByTagName('body')[0].clientHeight;

            var cmsURL = editor_config.path_absolute + 'admin/file-manager?editor=' + meta.fieldname;
            if (meta.filetype == 'image') {
                cmsURL = cmsURL + "&type=Images";
            } else {
                cmsURL = cmsURL + "&type=Files";
            }

            tinyMCE.activeEditor.windowManager.openUrl({
                url : cmsURL,
                title : 'Filemanager',
                width : x * 0.8,
                height : y * 0.8,
                resizable : "yes",
                close_previous : "no",
                onMessage: (api, message) => {
                callback(message.content);
                }
            });
        },
        setup: function (editor) {
            editor.on('submit', function (e) {
                var content = editor.getContent();
                var regex = /<iframe(.*?)\s+src=["'](https?:\/\/(?:www\.)?youtube\.com\/embed\/([^\s"']+))["'](.*?)>\s*<\/iframe>/gi;
                var modifiedContent = content.replace(regex, function(match, p1, p2, p3) {
                    var title = 'YouTube video';
                    var titleMatch = match.match(/title="([^"]+)"/i);
                    if (titleMatch) {
                        title = titleMatch[1];
                    }
                    return '<div class="ratio ratio-16x9"><iframe src="https://www.youtube.com/embed/' + p3 + '" title="' + title + '" allowfullscreen></iframe></div>';
                });
                editor.setContent(modifiedContent);
            });
        }
    };
    tinymce.init(editor_config);
</script>
<script>
    document.addEventListener('focusin', (e) => {
        if (e.target.closest(".tox-tinymce, .tox-tinymce-aux, .moxman-window, .tam-assetmanager-root") !== null) {
            e.stopImmediatePropagation();
        }
    });
</script>
<script>
    var editor_config = {
        selector: 'textarea#',
        content_css: '/css/tinycustom.css',
        path_absolute : "/",
        Remove_Powered_By: true,
        menubar: 'edit insert view format table',
        plugins: 'advlist autolink lists link image charmap hr anchor searchreplace wordcount code fullscreen insertdatetime media save table contextmenu directionality paste textcolor colorpicker textpattern emoticons',
        toolbar: 'undo redo | bold italic strikethrough forecolor backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image media | fullscreen code emoticons',
        relative_urls: false,
        image_dimensions: false,
        file_picker_callback : function(callback, value, meta) {
            var x = window.innerWidth || document.documentElement.clientWidth || document.getElementsByTagName('body')[0].clientWidth;
            var y = window.innerHeight|| document.documentElement.clientHeight|| document.getElementsByTagName('body')[0].clientHeight;

            var cmsURL = editor_config.path_absolute + 'admin/file-manager?editor=' + meta.fieldname;
            if (meta.filetype == 'image') {
                cmsURL = cmsURL + "&type=Images";
            } else {
                cmsURL = cmsURL + "&type=Files";
            }

            tinyMCE.activeEditor.windowManager.openUrl({
                url : cmsURL,
                title : 'Filemanager',
                width : x * 0.8,
                height : y * 0.8,
                resizable : "yes",
                close_previous : "no",
                onMessage: (api, message) => {
                callback(message.content);
                }
            });
        },
        setup: function (editor) {
            editor.on('submit', function (e) {
                var content = editor.getContent();
                var regex = /<iframe(.*?)\s+src=["'](https?:\/\/(?:www\.)?youtube\.com\/embed\/([^\s"']+))["'](.*?)>\s*<\/iframe>/gi;
                var modifiedContent = content.replace(regex, function(match, p1, p2, p3) {
                    var title = 'YouTube video';
                    var titleMatch = match.match(/title="([^"]+)"/i);
                    if (titleMatch) {
                        title = titleMatch[1];
                    }
                    return '<div class="ratio ratio-16x9"><iframe src="https://www.youtube.com/embed/' + p3 + '" title="' + title + '" allowfullscreen></iframe></div>';
                });
                editor.setContent(modifiedContent);
            });
        }
    };
    tinymce.init(editor_config);
</script>
<script>
    document.addEventListener('focusin', (e) => {
        if (e.target.closest(".tox-tinymce, .tox-tinymce-aux, .moxman-window, .tam-assetmanager-root") !== null) {
            e.stopImmediatePropagation();
        }
    });
</script>
@endpush
