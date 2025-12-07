@extends('layout.admin.structure')
@section('xmt_tit', 'Create Practice Question | Admin')

@push('headcss')
<script src="{{URL::asset('/assets/tinymce/tinymce.min.js')}}" referrerpolicy="origin"></script>
<style>
    .tox-statusbar__branding, .tox-promotion-link
    {
        display:none;
        visibility: hidden;
    }
</style>
@endpush

@section('content')
<div class="container mt-3">
    <h1 class="text-center text-primary fs-5 lh-lg">Create Practice Question</h1>
    <div class="text-center m-1 mb-3">
        <a class="btn btn-primary btn-sm" href="{{ route('admin.practice.question.index') }}" role="button"><i class="fa-solid fa-list"></i></a>
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
    <form method="post" action="{{ route('admin.practice.question.store') }}" enctype="multipart/form-data">
        @csrf
        <p class="text-center">* fields are compulsory</p>
        <div class="form-group py-3">
            <label for="question" class="form-label">Question *</label>
            <textarea name="question" id="question" rows="5" class="zmyeditor" placeholder="Enter your question" novalidate>{{ old('question') }}</textarea>
        </div>
        <div class="row">
            <div class="col-md-12 col-lg-6">
                <div class="form-group py-3">
                    <label for="option1" class="form-label">Option 1 *</label>
                    <textarea name="option1" id="option1" rows="5" class="zmyeditor" placeholder="Enter your option" novalidate>{{ old('option1') }}</textarea>
                </div>
            </div>
            <div class="col-md-12 col-lg-6">
                <div class="form-group py-3">
                    <label for="option2" class="form-label">Option 2 *</label>
                    <textarea name="option2" id="option2" rows="5" class="zmyeditor" placeholder="Enter your option" novalidate>{{ old('option2') }}</textarea>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 col-lg-6">
                <div class="form-group py-3">
                    <label for="option3" class="form-label">Option 3 *</label>
                    <textarea name="option3" id="option3" rows="5" class="zmyeditor" placeholder="Enter your option" novalidate>{{ old('option3') }}</textarea>
                </div>
            </div>
            <div class="col-md-12 col-lg-6">
                <div class="form-group py-3">
                    <label for="option4" class="form-label">Option 4 *</label>
                    <textarea name="option4" id="option4" rows="5" class="zmyeditor" placeholder="Enter your option" novalidate>{{ old('option4') }}</textarea>
                </div>
            </div>
        </div>
        <!-- <div class="form-group py-3">
            <label for="name" class="form-label">Question*</label>
            <input type="text" class="form-control" name="name" id="name" oninput="getulinkt()" value="{{ old('name') }}" required>
        </div>
        <div class="form-group py-3">
            <label for="ulink" class="form-label">Ulink *</label>
            <input type="text" class="form-control" name="ulink" id="ulink" oninput="getulink(this.value)" aria-describedby="helpulink" value="{{ old('ulink') }}" required>
            <div id="helpulink" class="form-text">{{config('app.url')}}/practice/question/</div>
        </div>
        <div class="form-group py-3">
            <label for="image" class="form-label">Image *</label>
            <input type="file" class="form-control" name="image" id="image" aria-describedby="helpimage" accept="image/*">
            <div id="helpimage" class="form-text">Recommended ratio 16:9</div>
        </div>
        <div class="form-group py-3">
            <label for="mtit" class="form-label">Meta Title (SEO) *</label>
            <input type="text" class="form-control" name="mtit" id="mtit" value="{{ old('mtit') }}" required>
        </div>
        <div class="form-group py-3">
            <label for="mdes" class="form-label">Meta Description (SEO) *</label>
            <textarea class="form-control" name="mdes" id="mdes" rows="3" required>{{ old('mdes') }}</textarea>
        </div>
        <div class="form-group py-3">
            <label for="ogimage" class="form-label">OG Image *</label>
            <input type="file" class="form-control" name="ogimage" id="ogimage" aria-describedby="helpogimage" accept="image/*">
            <div id="helpogimage" class="form-text">This image will be shown when you share your links to social media. Recommended ratio 1200:627</div>
        </div>
        <div class="form-group py-3">
            <label for="status" class="form-label">Status</label>
            <select name="status" id="status" class="form-select" data-live-search="true">
                @php
                    $statusarr = array('hidden' => 'Hidden', 'waiting' => 'Launch Soon', 'public' => 'Live');
                @endphp
                @foreach($statusarr as $key => $value)
                    <option value="{{ $key }}" {{ $key == old('status') ? 'selected' : '' }}>{{ $value }}</option>
                @endforeach
            </select>
        </div>
        <div id="launch_dt_div" style="display:none">
            <div class="form-group py-3">
                <label for="launch_datetime" class="form-label">Launch On</label>
                <div class="input-group date datetimepicker-input" id="datetimepicker1" data-target-input="nearest">
                    <input type="text" class="form-control datetimepicker-input xfilter_field" id="launch_datetime" name="launch_datetime" data-target="#datetimepicker1" value="{{ old('launch_datetime') ? old('launch_datetime') : '' }}">
                    <div class="input-group-append" data-target="#datetimepicker1" data-toggle="datetimepicker">
                        <div class="input-group-text" style="height: 100%;"><i class="far fa-calendar-alt text-primary"></i></div>
                    </div>
                </div>
            </div>
        </div> -->
        <div class="form-group py-3">
            <button type="submit" class="btn btn-block btn-primary">Create Question</button>
        </div>
    </form>
<div>
@endsection
@push('endjs')
<script>
    function handleStatusChange() {
        var selectedStatus = $("#status").val();
        if (selectedStatus === "waiting") {
            $("#launch_dt_div").show();
        } else {
            $("#launch_dt_div").hide();
        }
    }
    $(document).ready(function() {
        handleStatusChange();
        $("#status").on("change", function() {
            handleStatusChange();
        });
    });
</script>
<script>
    var editor_config = {
        selector: 'textarea.zmyeditor',
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