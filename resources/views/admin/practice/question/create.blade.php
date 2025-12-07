@extends('layout.admin.structure')
@section('xmt_tit', 'Create Practice Question | Admin')

@section('content')
<div class="container mt-3">
    <h1 class="text-center text-primary fs-5 lh-lg">Create Practice Question</h1>
    @if ($errors->any())
        {!! implode('', $errors->all('<div class="alert alert-danger" role="alert">:message</div>')) !!}
    @endif
    <div class="form-group mb-3">
        <label for="subject_id" class="form-label">Subject</label>
        <select name="subject" id="subject_id" class="form-select"></select>
    </div>
    <div class="form-group mb-3">
        <label for="topic_id" class="form-label">Topic</label>
        <select name="topic" id="topic_id" class="form-select"></select>
    </div>
    <div class="form-group mb-3">
        <label for="subtopic_id" class="form-label">Subtopic</label>
        <select name="subtopic" id="subtopic_id" class="form-select"></select>
    </div>
    <input type="hidden" id="sel_subject" value="{{ request('subject') }}">
    <input type="hidden" id="sel_topic" value="{{ request('topic') }}">
    <input type="hidden" id="sel_subtopic" value="{{ request('subtopic') }}">
    <button class="btn btn-primary" onclick="submitSetup()">Submit</button>
<div>
@endsection
@push('endjs')
<script>
    $(document).ready(function() {
        var selSubject = $('#sel_subject').val();
        var selTopic = $('#sel_topic').val();
        var selSubtopic = $('#sel_subtopic').val();
        $.ajax({
            url: "/api/load-subjects",
            type: "GET",
            success: function(response) {
                if (response.error) {
                    $('#alertContainer').html('<div class="alert alert-danger">' + response.error + '</div>');
                } else if (response.subjects) {
                    $('#subject_id').empty().append('<option value="" disabled selected>Select a subject</option>');
                    $.each(response.subjects, function(key, value) {
                        var isSelected = (key == selSubject) ? ' selected' : '';
                        $('#subject_id').append('<option value="' + key + '"' + isSelected + '>' + value + '</option>');
                    });
                } else {
                    $('#alertContainer').html('<div class="alert alert-info">Error Occurred</div>');
                }
                if(selTopic) {
                    subject_change();
                }
            }
        });

        $('#subject_id').on('change', function() {
            subject_change();
        });

        $('#topic_id').on('change', function() {
            topic_change();
        });
        function subject_change() {
            var selectedSubject = $('#subject_id').val();
            $.ajax({
                url: "/api/load-topics",
                type: "GET",
                data: {
                subject: selectedSubject,
                },
                success: function(response) {
                    if (response.error) {
                        $('#alertContainer').html('<div class="alert alert-danger">' + response.error + '</div>');
                    } else if (response.topics) {
                        $('#topic_id').empty().append('<option value="" disabled selected>Select a topic</option>');
                        $.each(response.topics, function(key, value) {
                            var isSelected = (key == selTopic) ? ' selected' : '';
                            $('#topic_id').append('<option value="' + key + '"' + isSelected + '>' + value + '</option>');
                        });
                    } else {
                        $('#alertContainer').html('<div class="alert alert-info">Something went wrong</div>');
                    }
                    if(selSubtopic) {
                        topic_change();
                    }
                }
            });
        }
        function topic_change() {
            var selectedTopic = $('#topic_id').val();
            $.ajax({
                url: "/api/load-subtopics",
                type: "GET",
                data: {
                topic: selectedTopic,
                },
                success: function(response) {
                    if (response.error) {
                        $('#alertContainer').html('<div class="alert alert-danger">' + response.error + '</div>');
                    } else if (response.subtopics) {
                        $('#subtopic_id').empty().append('<option value="" disabled selected>Select a subtopic</option>');
                        $.each(response.subtopics, function(key, value) {
                            var isSelected = (key == selSubtopic) ? ' selected' : '';
                            $('#subtopic_id').append('<option value="' + key + '"' + isSelected + '>' + value + '</option>');
                        });
                    } else {
                        $('#alertContainer').html('<div class="alert alert-info">Something went wrong</div>');
                    }
                }
            });
        }
    });
</script>
<script>
    function submitSetup() {
        var subjectId = $('#subject_id').val();
        var topicId = $('#topic_id').val();
        var subtopicId = $('#subtopic_id').val();
        var createUrl = '/admin/practice/question/manage/' + encodeURIComponent(subjectId) + '/' + encodeURIComponent(topicId) + '/' + encodeURIComponent(subtopicId);

        window.location.href = createUrl;
    }
</script>
@endpush
