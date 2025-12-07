@extends('layout.admin.structure')
@section('xmt_tit', 'Create Practice Question | Admin')

@push('headcss')
<script src="{{URL::asset('/assets/tinymce/tinymce.min.js')}}" referrerpolicy="origin"></script>
<link rel="stylesheet" href="/css/tinymce-editor-v1-0.css">
<style>
    #answer_radio, #answer_check, #answer_input {
        display:none;
    }
    .c-lh-3 {
        line-height: 3rem;
    }
</style>
@endpush
@section('content')
<form action="{{ route('admin.practice.question.submit') }}" method="post" enctype="multipart/form-data" autocomplete="off">
    @csrf
    <div class="section">
        <div class="container py-3">
            <h1 class="text-center text-primary fs-5 lh-lg">Create Practice Question</h1>
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
            <input type="hidden" name="subject_id" id="subject_id" value="{{ old('subject_id',$question->subject_id) }}">
            <input type="hidden" name="topic_id" id="topic_id" value="{{ old('topic_id',$question->topic_id) }}">
            <input type="hidden" name="subtopic_id" id="subtopic_id" value="{{ old('subtopic_id',$question->subtopic_id) }}">
            <input type="hidden" name="priority" id="priority" value="{{ old('priority',$question->priority) }}">
            <div class="d-inline-flex">
                <div class="me-4">
                    <a class="btn btn-primary btn-sm" href="#submit_div">Question {{ $question->priority }}</a>
                </div>
                <div class="align-self-center">
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="question_type" id="inlineRadio1" value="Choice" onclick="questionType('Choice');"{{ old('question_type', $question->question_type) == 'Choice' || (empty(old('question_type')) && empty($question->question_type)) ? ' checked' : '' }}>
                        <label class="form-check-label" for="inlineRadio1">Choice</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="question_type" id="inlineRadio3" value="Numerical" onclick="questionType('Numerical');"{{ old('question_type',$question->question_type) === 'Numerical' ? ' checked' : '' }}>
                        <label class="form-check-label" for="inlineRadio3">Number</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="question_type" id="inlineRadio2" value="Multi-choice" onclick="questionType('Multi-choice');"{{ old('question_type',$question->question_type) === 'Multi-choice' ? ' checked' : '' }}>
                        <label class="form-check-label" for="inlineRadio2">Multi-choice</label>
                    </div>
                </div>
            </div>
            <div>
                <div class="form-group py-3">
                    <label for="question" class="form-label text-primary fw-bold">Question *</label>
                    <textarea name="question" id="question" rows="5" class="zmyeditor" placeholder="Enter your question" novalidate>{{ old('question',$question->question) }}</textarea>
                </div>
                <div id="optionsdiv">
                    <div class="row">
                        <div class="col-12 col-md-6">
                            <div class="form-group py-3">
                                <label for="option1" class="form-label text-secondary fw-bold">Option 1 *</label>
                                <textarea name="option1" id="option1" rows="5" class="zmyeditor" placeholder="Enter your option" novalidate>{{ old('option1',$question->option1) }}</textarea>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="form-group py-3">
                                <label for="option2" class="form-label text-secondary fw-bold">Option 2 *</label>
                                <textarea name="option2" id="option2" rows="5" class="zmyeditor" placeholder="Enter your option" novalidate>{{ old('option2',$question->option2) }}</textarea>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 col-md-6">
                            <div class="form-group py-3">
                                <label for="option3" class="form-label text-secondary fw-bold">Option 3 *</label>
                                <textarea name="option3" id="option3" rows="5" class="zmyeditor" placeholder="Enter your option" novalidate>{{ old('option3',$question->option3) }}</textarea>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="form-group py-3">
                                <label for="option4" class="form-label text-secondary fw-bold">Option 4 *</label>
                                <textarea name="option4" id="option4" rows="5" class="zmyeditor" placeholder="Enter your option" novalidate>{{ old('option4',$question->option4) }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="answer_radio" class="mb-3">
                    <div class="text-primary fw-bold">Answer *</div>
                    @for ($i=1; $i<=4; $i++)
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="choiceAnswer" id="choiceAnswer{{$i}}" value="{{$i}}"{{ old('choiceAnswer',$question->answer1) == $i ? ' checked' : '' }}>
                        <label class="form-check-label" for="choiceAnswer{{$i}}">{{$i}}</label>
                    </div>
                    @endfor
                </div>
                <div id="answer_check" class="mb-3">
                    <div class="text-primary fw-bold">Answer *</div>
                    @php
                        $selectedAnswers = [];
                        if(!empty($question->question_type) && $question->question_type=='Multi-choice')
                        {
                            $selectedAnswers = explode(',', $question->answer1);
                        }
                    @endphp
                    @for ($i = 1; $i <= 4; $i++)
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" name="answerCheck{{$i}}" id="answerCheck{{$i}}" value="{{$i}}"{{ in_array((string)$i, $selectedAnswers) ? ' checked' : '' }}>
                            <label class="form-check-label" for="answerCheck{{$i}}">{{$i}}</label>
                        </div>
                    @endfor
                </div>
                <div id="answer_input">
                    <div class="text-primary fw-bold">Answer *</div>
                    <div class="row">
                        <div class="col form-group mb-3">
                            <label for="min_answer" class="form-label">Min *</label>
                            <input type="text" class="form-control" name="min_answer" id="min_answer" value="{{ old('min_answer',$question->answer1) }}">
                        </div>
                        <div class="col form-group mb-3">
                            <label for="max_answer" class="form-label">Max</label>
                            <input type="text" class="form-control" name="max_answer" id="max_answer" value="{{ old('max_answer',$question->answer2) }}">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group py-3">
                        <label for="solution" class="form-label text-primary fw-bold">Solution</label>
                        <textarea name="solution" id="solution" rows="5" class="zmyeditor" placeholder="Enter your solution" novalidate>{{ old('solution',$question->solution) }}</textarea>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 col-sm-6 form-group mb-3">
                        <label for="mark" class="form-label">Marks *</label>
                        <input type="number" class="form-control" name="mark" id="mark" step="0.01" value="{{ old('mark') ?? $question->mark ?? $defaults['mark'] }}">
                    </div>
                    <div class="col-12 col-sm-6 form-group mb-3">
                        <label for="duration" class="form-label">Duration (Secs) *</label>
                        <input type="number" class="form-control" name="duration" id="duration" value="{{ old('duration') ?? $question->duration ?? $defaults['duration'] }}">
                    </div>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" name="status" id="status" value="1"{{ (old('status') == '1' || $question->status == 'public') ? ' checked' : '' }}>
                    <label class="form-check-label" for="status">Public</label>
                </div>
                <p class="pb-3 text-center c-lh-3" id="submit_div">
                    <button type="submit" name="submit" value="savenext" class="btn btn-block btn-primary">Save &amp; Next</button>
                    <button type="submit" name="submit" value="save" class="btn btn-block btn-success">Save</button>
                    @if($question->id)
                    <input type="hidden" name="question_id" id="question_id" value="{{ $question->id }}">
                    <button type="submit" name="submit" value="delete" class="btn btn-block btn-danger">Delete</button>
                    @endif
                    <button type="button" onclick="location.reload();" class="btn btn-block btn-light">Clear</button>
                </p>
                <div class="pb-1">
                    <nav aria-label="Questions List">
                        <ul class="pagination justify-content-center">
                            <li class="page-item mx-1"><a href="{{ route('admin.practice.question.manage', [$question->subject_id,$question->topic_id,$question->subtopic_id,1]) }}" class="btn btn-outline-primary border-light shadow{{ $question->priority == 1 ? ' disabled' : '' }}" aria-label="First"><i class="fas fa-angle-double-left"></i></a></li>
                            <li class="page-item mx-1"><a href="{{ route('admin.practice.question.manage', [$question->subject_id,$question->topic_id,$question->subtopic_id,($question->priority)-1]) }}" class="btn btn-outline-primary border-light shadow{{ $question->priority == 1 ? ' disabled' : '' }}" aria-label="Previous"><i class="fas fa-chevron-left"></i></a></li>
                            <li class="page-item mx-1 active"><button class="btn btn-primary shadow">{{ $question->priority }}</button></li>
                            <li class="page-item mx-1"><a href="{{ route('admin.practice.question.manage', [$question->subject_id,$question->topic_id,$question->subtopic_id,($question->priority)+1]) }}" class="btn btn-outline-primary border-light shadow{{ ($question->priority) > $maxQuestion ? ' disabled' : '' }}" aria-label="Next"><i class="fas fa-chevron-right"></i></a></li>
                            <li class="page-item mx-1"><a href="{{ route('admin.practice.question.manage', [$question->subject_id,$question->topic_id,$question->subtopic_id,$maxQuestion+1]) }}" class="btn btn-outline-primary border-light shadow{{ ($question->priority) > $maxQuestion ? ' disabled' : '' }}" aria-label="Last"><i class="fas fa-angle-double-right"></i></a></li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    <div class="section pt-3" style="background-color:#eeeeee;">
        <div class="container py-3">
            <div class="text-center c-lh-3">
                <a href="{{ route('admin.practice.subject.index') }}" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" data-bs-title="Subject">{{ $question->practiceSubject->priority.'. '.$question->practiceSubject->name }}</a>
                <a href="{{ route('admin.practice.topic.index') }}?subject={{ $question->subject_id }}" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" data-bs-title="Topic">{{ $question->practiceTopic->priority.'. '.$question->practiceTopic->name }}</a>
                <a href="{{ route('admin.practice.subtopic.index') }}?topic={{ $question->topic_id }}" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" data-bs-title="Subtopic">{{ $question->practiceSubtopic->priority.'. '.$question->practiceSubtopic->name }}</a>
                <a href="{{ route('admin.practice.question.create') }}?subject={{$question->subject_id}}&topic={{$question->topic_id}}&subtopic={{$question->subtopic_id}}" class="btn btn-sm btn-outline-primary">Go Back</a>
            </div>
            <!-- <div class="text-center pt-3">
                <a href="{{ $prevSubtopic ? route('admin.practice.question.manage',[$prevSubtopic->subject_id,$prevSubtopic->topic_id,$prevSubtopic->id,1]) : '' }}" class="btn btn-sm btn-secondary{{ $prevSubtopic ? '' : ' disabled' }}">Prev Subtopic</a>
                <a href="{{ $nextSubtopic ? route('admin.practice.question.manage', [$nextSubtopic->subject_id, $nextSubtopic->topic_id,$nextSubtopic->id,1]) : '' }}"class="btn btn-sm btn-secondary{{ $nextSubtopic ? '' : ' disabled' }}">Next Subtopic</a>
            </div> -->
            <p class="text-center fw-bold mt-3">All Questions</p>
            <div class="text-center c-lh-3">
            @for ($i=1; $i<= ($maxQuestion+1); $i++)
                <a href="{{ route('admin.practice.question.manage', [$question->subject_id,$question->topic_id,$question->subtopic_id,$i]) }}" class="btn {{ !in_array($i, $QuestionList) ? 'btn-danger' : 'btn-dark' }} border-light shadow"{!! !in_array($i, $QuestionList) ? ' data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Not created"' : '' !!}>{{$i}}</a>
            @endfor
            </div>
        <div>
    <div>
</form>
<input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}">
@endsection
@push('endjs')
<script>
    const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
    const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl))
</script>
<script>
    var editor_config = {
        selector: 'textarea.zmyeditor',
        content_css: '/css/tinycustom.css',
        path_absolute : "/",
        Remove_Powered_By: true,
        menubar: 'edit insert view format table',
        plugins: 'advlist autolink lists link image charmap hr anchor searchreplace wordcount code fullscreen insertdatetime media save table contextmenu directionality paste textcolor colorpicker textpattern emoticons autoresize',
        toolbar: 'undo redo | bold italic strikethrough forecolor backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image media | fullscreen code emoticons',
        relative_urls: false,
        image_dimensions: false,

        init_instance_callback: function(instance) {
            myeditor = instance;
            editorContainer = instance.editorContainer;
            header = editorContainer.childNodes[0].childNodes[0];
            editorContainer.style.width = '100%';
            editorContainer.style.height = '100px';
            if (header) {
                header.style.display = 'none';

                myeditor.on('focus', (function(headerRef) {
                    return function() {
                        headerRef.style.display = 'block';
                    };
                })(header));

                myeditor.on('blur', (function(headerRef) {
                    return function() {
                        headerRef.style.display = 'none';
                    };
                })(header));
            }
        },
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
    function questionType(type)
    {
        document.getElementById('answer_check').style.display = 'none';
        document.getElementById('answer_radio').style.display = 'none';
        document.getElementById('answer_input').style.display = 'none';
        document.getElementById('optionsdiv').style.display = 'block';
        if(type=='Numerical') {
            document.getElementById('answer_input').style.display = 'block';
            document.getElementById('optionsdiv').style.display = 'none';
        } else if(type=='Multi-choice') {
            document.getElementById('answer_check').style.display = 'block';
        } else {
            document.getElementById('answer_radio').style.display = 'block';
        }
    }
    document.addEventListener('DOMContentLoaded', function() {
        var radioButtons = document.getElementsByName('question_type');
        var checkedValue = '';
        for (var i = 0; i < radioButtons.length; i++) {
            if (radioButtons[i].checked) {
                checkedValue = radioButtons[i].value;
                break;
            }
        }
        questionType(checkedValue);
    });

</script>
@endpush
