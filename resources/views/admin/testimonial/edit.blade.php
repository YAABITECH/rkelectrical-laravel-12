@extends('layout.admin.structure')
@section('xmt_tit', 'Edit Testimonial | Admin')
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
        <h1 class="text-center text-primary fs-5 lh-lg">Edit Testimonial</h1>
        <div class="text-center m-1 mb-3">
            <a class="btn btn-primary btn-sm" href="{{ route('admin.testimonial.index') }}" role="button"><i class="fa-solid fa-list"></i></a>
            <form action="{{ route('admin.testimonial.destroy', $testimonial->id) }}" method="post"
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
        <form method="post" action="{{ route('admin.testimonial.update',$testimonial->id) }}" enctype="multipart/form-data">
            @csrf
            @method('PATCH')
            <p class="text-center">* fields are compulsory</p>
            <div class="form-group py-3">
                <label for="name" class="form-label">Name *</label>
                <input type="text" class="form-control" name="name" id="name" value="{{ old('name',$testimonial->name) }}" required>
            </div>
            <div class="form-group py-3">
                <label for="star" class="form-label">Star *</label>
                <input type="number" class="form-control" name="star" id="star" max="5" value="{{ old('star',$testimonial->star) }}" required>
            </div>
            <div class="form-group py-3">
                <label for="content" class="form-label">Content *</label>
                <textarea class="form-control" name="content" id="content" rows="3" required>{{ old('content',$testimonial->content) }}</textarea>
            </div>
            <div class="py-3">
                <label class="form-label">Previous Image</label><br>
                <img src="{{URL::asset('/image/testimonial/thumbs/'.$testimonial->photo)}}" class="img-fluid rounded" alt="" style="max-width:150px">
            </div>
             <div class="form-group py-3">
                <label for="photo" class="form-label">Update Image </label>
                <input type="file" class="form-control" name="photo" id="photo" aria-describedby="helpimage" accept="image/*">
                <div id="helpimage" class="form-text">Recommended ratio 16:9</div>
            </div>
            <div class="form-group py-3">
                <button type="submit" class="btn btn-block btn-primary">Update Testimonial</button>
            </div>
        </form>
    <div>
@endsection
@push('endjs')
<script>
    var editor_config = {
        selector: 'textarea#content',
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
