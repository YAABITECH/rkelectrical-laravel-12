@extends('layout.admin.structure')
@section('xmt_tit', 'Edit Test Series | Admin')

@push('headcss')
<script src="{{asset('/assets/tinymce/tinymce.min.js')}}" referrerpolicy="origin"></script>
<link rel="stylesheet" href="/css/tinymce-editor-v1-0.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.1.2/css/tempusdominus-bootstrap-4.min.css">
@endpush

@section('content')
<div class="container mt-3">
    <h1 class="text-center text-primary fs-5 lh-lg">Edit Test Series</h1>

    <div class="text-center m-1 mb-3">
        <a class="btn btn-primary btn-sm" href="{{ route('admin.test.series.index') }}"><i class="fa-solid fa-list"></i></a>
        <a class="btn btn-primary btn-sm" href="{{ route('admin') }}"><i class="fa-solid fa-home"></i></a>
    </div>

    @if ($errors->any())
        {!! implode('', $errors->all('<div class="alert alert-danger">:message</div>')) !!}
    @endif
    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <form method="post" action="{{ route('admin.test.series.update', $series->id) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <p class="text-center">* fields are compulsory</p>
        {{-- Parent --}}
        @if($series->parent)
        <div class="form-group py-3" id="parentDiv">
            <label class="form-label">Parent Test Series</label>
            <input type="text" class="form-control" value="{{ $series->parent->name }}" readonly>
            <input type="hidden" name="parent_id" id="parent_id" value="{{ $series->parent_id }}">

            <div class="form-check mt-2">
                <input class="form-check-input" type="checkbox" id="remove_parent" onclick="removeParentTS()">
                <label class="form-check-label" for="remove_parent">Remove Parent</label>
            </div>
        </div>
        @endif

        <div class="form-group py-3">
            <label for="name" class="form-label">Name *</label>
            <input type="text" class="form-control" name="name" id="name" value="{{ old('name', $series->name) }}" oninput="getulinkt()" required>
        </div>

        <div class="form-group py-3">
            <label class="form-label">Tagline</label>
            <input type="text" class="form-control" name="tagline"
                value="{{ old('tagline', $series->tagline) }}">
        </div>

        <div class="form-group py-3">
            <label class="form-label">Description</label>
            <textarea name="description" class="yaTinyEditor" rows="5">{{ old('description', $series->description) }}</textarea>
        </div>

        <div class="form-group py-3">
            <label class="form-label">URL Slug *</label>
            <input type="text" class="form-control" name="url_slug" id="url_slug"
                value="{{ old('url_slug', $series->url_slug) }}" oninput="geturlslug(this.value)" required>
            <div id="helpslug" class="form-text">{{ config('app.url') }}/test-series/</div>
        </div>

        <div class="form-group py-3">
            <label class="form-label">Ulink *</label>
            <input type="text" class="form-control" name="ulink" id="ulink"
                value="{{ old('ulink', $series->ulink) }}" readonly>
            <div id="helpulink" class="form-text">{{ config('app.url') }}/test-series/{{ $series->ulink }}</div>
        </div>

        {{-- Image --}}
        <div class="form-group py-3">
            <label class="form-label">Featured Image</label>
            <input type="file" class="form-control" name="image">
            @if($series->image)
                <img src="/image/test-series/{{ $series->image }}" class="img-fluid mt-2" style="max-width:250px;">
            @endif
        </div>

        {{-- Paid --}}
        <div class="form-group py-3">
            <label class="form-label">Is Paid?</label>
            <div class="form-check">
                <input type="checkbox" class="form-check-input" id="is_paid" name="is_paid"
                    value="1" onchange="toggleFees()" 
                    {{ old('is_paid', $series->is_paid) ? 'checked' : '' }}>
                <label class="form-check-label">Enable Payment</label>
            </div>
        </div>

        <div class="form-group py-3" id="feesDiv" style="display: {{ old('is_paid', $series->is_paid) ? 'block' : 'none' }}">
            <label class="form-label">Fees (₹)</label>
            <input type="number" class="form-control" name="fees" value="{{ old('fees', $series->fees) }}">
        </div>

        {{-- Index --}}
        <div class="form-group py-3">
            <label class="form-label">Show in Index?</label>
            <div class="form-check">
                <input class="form-check-input" type="checkbox"
                    name="is_index" id="is_index" value="1"
                    {{ old('is_index', $series->is_index) ? 'checked' : '' }}>
                <label class="form-check-label" for="is_index">Enable Indexing</label>
            </div>
        </div>

        <div class="form-group py-3">
            <label class="form-label">Meta Title (SEO)</label>
            <input type="text" class="form-control conditional-required"
                name="mtit" value="{{ old('mtit', $series->mtit) }}">
        </div>

        <div class="form-group py-3">
            <label class="form-label">Meta Description (SEO)</label>
            <textarea class="form-control conditional-required"
                name="mdes" rows="3">{{ old('mdes', $series->mdes) }}</textarea>
        </div>

        {{-- OG Image --}}
        <div class="form-group py-3">
            <label class="form-label">OG Image</label>
            <input type="file" class="form-control" name="ogimage">
            @if($series->ogimage)
                <img src="/image/test-series/ogimage/{{ $series->ogimage }}" class="img-fluid mt-2" style="max-width:250px;">
            @endif
        </div>

        {{-- Status --}}
        <div class="form-group py-3">
            <label class="form-label">Status</label>
            <select class="form-select" name="status" id="status">
                @foreach(['draft'=>'Draft','upcoming'=>'Upcoming','active'=>'Active','archive'=>'Archive'] as $k=>$v)
                    <option value="{{ $k }}" {{ old('status', $series->status) == $k ? 'selected' : '' }}>{{ $v }}</option>
                @endforeach
            </select>
        </div>

        {{-- Launch --}}
        <div class="form-group py-3 {{ old('status', $series->status)=='upcoming' ? '' : 'd-none' }}" id="launch_at_div">
            <label class="form-label">Launch Date & Time</label>
            <div class="input-group date datetimepicker-input" id="launchpicker">
                <input type="text" class="form-control datetimepicker-input"
                    name="launch_at" id="launch_at"
                    value="{{ old('launch_at', $series->launch_at ? $series->launch_at->format('d-m-Y h:i A') : '') }}">
                <div class="input-group-append" data-target="#launchpicker">
                    <div class="input-group-text"><i class="far fa-calendar-alt"></i></div>
                </div>
            </div>
        </div>

        {{-- Expire --}}
        <div class="form-group py-3">
            <label class="form-label">Expire Date & Time</label>
            <div class="input-group date datetimepicker-input" id="expirepicker">
                <input type="text" class="form-control datetimepicker-input"
                    name="expire_at" id="expire_at"
                    value="{{ old('expire_at', $series->expire_at ? $series->expire_at->format('d-m-Y h:i A') : '') }}">
                <div class="input-group-append">
                    <div class="input-group-text"><i class="far fa-calendar-alt"></i></div>
                </div>
            </div>
        </div>

        {{-- Featured --}}
        <div class="form-group py-3">
            <label class="form-label">Featured?</label>
            <div class="form-check">
                <input class="form-check-input" type="checkbox"
                    name="featured" value="1"
                    {{ old('featured', $series->featured) ? 'checked' : '' }}>
                <label class="form-check-label">Mark as Featured</label>
            </div>
        </div>

        <div class="form-group py-3">
            <button type="submit" class="btn btn-primary btn-block">Update Test Series</button>
        </div>

    </form>
</div>

<input type="hidden" id="parent_ulink" value="{{ $series->parent->ulink ?? '' }}">
@endsection
@push('endjs')
<script src="/js/ya-tiny-editor-v1-0.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.1.2/js/tempusdominus-bootstrap-4.min.js"></script>
<script>
    $(function () {
        $('.datetimepicker-input').datetimepicker({
            format: 'DD-MM-YYYY h:mm A',
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

        $('#launch_at').on('focus', function () {
            $('#launchpicker').datetimepicker('show');
        });
        $('#expire_at').on('focus', function () {
            $('#expirepicker').datetimepicker('show');
        });
    });
</script>
<script>
    function statusChange(){
        let status = $('#status').val();
        let indexEnabled = $('#is_index').is(':checked');

        if(status === 'active' && indexEnabled){
            $('.conditional-required').attr('required', true);
            $('.required-asterisk').show();
        } else {
            $('.conditional-required').removeAttr('required');
            $('.required-asterisk').hide();
        }

        if (status === "upcoming") {
            $('#launch_at_div').removeClass('d-none');
        } else {
            $('#launch_at_div').addClass('d-none');
        }
    }
    $(document).ready(function() {
        statusChange();
        $("#status").on("change", function() {
            statusChange();
        });
        $("#is_index").on("change", function() {
            statusChange();
        });
    });
    function toggleFees() {
        let div = document.getElementById('feesDiv');
        div.style.display = document.getElementById('is_paid').checked ? 'block' : 'none';
    }
    function removeParentTS() {
        if (document.getElementById('remove_parent').checked) {
            document.getElementById('parent_id').value = "";
            document.getElementById('parent_name').value = "";
            document.getElementById('parentDiv').style.display = "none";
        }
    }
</script>
<script>
    function getulinkt() {
        let title = $("#name").val();
        let temp = toseourl(title);
        $("#url_slug").val(temp);
        geturlslug(temp);
    }

    function geturlslug(val) {
        let temp = toseourl(val);
        $("#url_slug").val(temp);

        let parent = $("#parent_ulink").val();
        let ulink = parent ? parent + '/' + temp : temp;

        $("#ulink").val(ulink);
        $("#helpslug").html(base() + temp);
        $("#helpulink").html(base() + ulink);
    }

    function base() {
        return "{{ config('app.url') }}/test-series/";
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
