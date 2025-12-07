@extends('layout.admin.structure')
@section('xmt_tit', 'Practice Subject | Admin')

@push('headcss')
    <style>
        .chorder
        {
            cursor:move;
        }
    </style>
@endpush
@section('content')
    <div class="container mt-3">
        <h1 class="text-center text-primary fs-5 lh-lg">Practice Subject List</h1>
        <div class="text-center m-1 mb-3">
        <a class="btn btn-primary btn-sm" href="{{ route('admin.practice.subject.index') }}" role="button"><i class="fa-solid fa-list"></i></a>
        <a class="btn btn-primary btn-sm" href="{{ route('admin.practice.subject.create') }}" role="button"><i class="fa-solid fa-plus"></i></a>
            <a class="btn btn-primary btn-sm" href="{{ route('admin') }}" role="button"><i class="fa-solid fa-home"></i></a>
        </div>
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
        <div id="ajax_response"></div>
        <ul class="list-group" id="sortable">
            @foreach ($subject as $subject)
                    <li data_id="{{ $subject->id }}" class="chorder list-group-item">{{ $subject->name }}</li>
            @endforeach
        </ul>
    </div>
<input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
@endsection
@push('endjs')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
<script>
    var sortableList = Sortable.create(document.getElementById("sortable"), {
        onEnd: function (evt) {
            var itemEl = evt.item;
            var chorder = $(".chorder");
            $('#ajax_response').html('');
            var dataArray = [];
            for (var i = 0; i < chorder.length; i++) {
                var x = $(chorder[i]).attr('data_id');
                dataArray.push(x);
            }
	        var token = document.getElementById("token").value;
            var formData = new FormData();
            formData.append("dataArray", dataArray);
		    formData.append("_token", token);

            var sorting_overlay = $('<div></div>');
            sorting_overlay.css({
            position: 'fixed',
            top: 0,
            left: 0,
            width: '100%',
            height: '100%',
            backgroundColor: 'rgba(0, 0, 0, 0.8)',
            zIndex: 9999
            });

            var sorting_text = $('<div>Updating...</div>');
            sorting_text.css({
            position: 'absolute',
            top: '50%',
            left: '50%',
            transform: 'translate(-50%, -50%)',
            color: '#fff',
            fontSize: '1.2rem',
            fontWeight: '500'
            });

            sorting_overlay.append(sorting_text);
            $('body').append(sorting_overlay);
            sortableList.option("disabled", true);

            $.ajax({
                url: "/admin/practice/subject/updatePriority",
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response == 'OK') {
                        $('#ajax_response').html('<div class="alert alert-success">Order changed successfully</div>');
                    } else if (response != '') {
                        $('#ajax_response').html('<div class="alert alert-alert_danger">'+response+'</div>');
                    } else {
                        $('#ajax_response').html('<div class="alert alert-alert_danger">Something went wrong.</div>');
                    }
                    sorting_overlay.remove();
                    sortableList.option("disabled", false);
                }
            });
            var priority = Array.from(itemEl.parentNode.children).indexOf(itemEl);
        }
    });
</script>
@endpush