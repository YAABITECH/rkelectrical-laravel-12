@extends('layout.admin.structure')
@section('xmt_tit', 'Create Test Series | Admin')

@push('headcss')
<script src="{{asset('/assets/tinymce/tinymce.min.js')}}" referrerpolicy="origin"></script>
<link rel="stylesheet" href="/css/tinymce-editor-v1-0.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.1.2/css/tempusdominus-bootstrap-4.min.css">
@endpush

@section('content')
<div class="container mt-3">
    <h1 class="text-center text-primary fs-5 lh-lg">Create Test Series</h1>
    <div class="text-center m-1 mb-3">
        <a class="btn btn-primary btn-sm" href="{{ route('admin.test.series.index') }}" role="button"><i class="fa-solid fa-list"></i></a>
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
    <form method="post" action="{{ route('admin.test.series.store') }}" enctype="multipart/form-data">
        @csrf
        <p class="text-center">* fields are compulsory</p>
        @if(isset($parentSeries))
        <div class="form-group py-3" id="parentDiv">
            <label for="parent_id" class="form-label">Parent Test Series</label>
            <input type="text" id="parent_name" class="form-control" value="{{ $parentSeries->name }}" readonly>
            <input type="hidden" id="parent_id" name="parent_id" value="{{ $parentSeries->id }}">
            <div class="form-check mt-2">
                <input class="form-check-input" type="checkbox" id="remove_parent" onclick="removeParent()">
                <label class="form-check-label" for="remove_parent">Remove Parent</label>
            </div>
        </div>
        @endif
        <div class="form-group py-3">
            <label for="name" class="form-label">Name *</label>
            <input type="text" class="form-control" name="name" id="name" oninput="getulinkt()" value="{{ old('name') }}" required>
        </div>
        <div class="form-group py-3">
            <label for="tagline" class="form-label">Tagline</label>
            <input type="text" class="form-control" name="tagline" id="tagline" value="{{ old('tagline') }}">
        </div>
        <div class="form-group py-3">
            <label for="description" class="form-label">Description</label>
            <textarea name="description" class="yaTinyEditor" id="description" rows="5">{{ old('description') }}</textarea>
        </div>
        <div class="form-group py-3">
            <label for="url_slug" class="form-label">URL Slug *</label>
            <input type="text" class="form-control" name="url_slug" id="url_slug" oninput="geturlslug(this.value)" aria-describedby="helpslug" value="{{ old('url_slug') }}" required>
            <div id="helpslug" class="form-text">{{config('app.url')}}/test/series/</div>
        </div>
        <div class="form-group py-3">
            <label for="ulink" class="form-label">Ulink *</label>
            <input type="text" class="form-control" name="ulink" id="ulink" aria-describedby="helpulink" value="{{ old('ulink') }}" readonly>
            <div id="helpulink" class="form-text">{{ config('app.url') }}/test/series/{{ old('ulink') }}</div>
        </div>
        <div class="form-group py-3">
            <label for="image" class="form-label">Featured Image</label>
            <input type="file" class="form-control" name="image" id="image" aria-describedby="helpimage" accept="image/*">
            <div id="helpimage" class="form-text">Recommended ratio 16:9</div>
        </div>
        <div class="form-group py-3">
            <label class="form-label">Is Paid?</label>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" value="1" id="is_paid"
                    name="is_paid" onchange="toggleFees()" {{ old('is_paid') ? 'checked' : '' }}>
                <label class="form-check-label" for="is_paid">Enable Payment</label>
            </div>
        </div>
        <div class="form-group py-3" id="feesDiv" style="display: {{ old('is_paid') ? 'block' : 'none' }};">
            <label for="fees" class="form-label">Fees (₹)</label>
            <input type="number" class="form-control" name="fees" id="fees" value="{{ old('fees') }}">
        </div>
        <div class="form-group py-3">
            <label class="form-label">Show in Index?</label>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" value="1" id="is_index" name="is_index" {{ old('is_index') ? 'checked' : '' }}>
                <label class="form-check-label" for="is_index">Enable Indexing</label>
            </div>
        </div>
        <div class="form-group py-3">
            <label for="mtit" class="form-label">Meta Title (SEO) <span class="required-asterisk">*</span></label>
            <input type="text" class="form-control conditional-required" name="mtit" id="mtit" value="{{ old('mtit') }}">
        </div>
        <div class="form-group py-3">
            <label for="mdes" class="form-label">Meta Description (SEO) <span class="required-asterisk">*</span></label>
            <textarea class="form-control conditional-required" name="mdes" id="mdes" rows="3">{{ old('mdes') }}</textarea>
        </div>
        <div class="form-group py-3">
            <label for="ogimage" class="form-label">OG Image</label>
            <input type="file" class="form-control" name="ogimage" id="ogimage" aria-describedby="helpogimage" accept="image/*">
            <div id="helpogimage" class="form-text">This image will be shown when you share your links to social media. Recommended ratio 1200:627</div>
        </div>
        <div class="form-group py-3">
            <label for="status" class="form-label">Status</label>
            <select name="status" id="status" class="form-select">
                @php $statusarr = ['draft'=>'Draft','upcoming'=>'Upcoming','active'=>'Active','archive'=>'Archive']; @endphp
                @foreach($statusarr as $key => $value)
                    <option value="{{ $key }}" {{ $key == old('status') ? 'selected' : '' }}>
                        {{ $value }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="form-group py-3 d-none" id="launch_at_div">
            <label for="launch_at" class="form-label">Launch Date & Time</label>
            <div class="input-group date datetimepicker-input" id="launchpicker" data-target-input="nearest">
                <input type="text" class="form-control datetimepicker-input"
                    id="launch_at" name="launch_at"
                    data-target="#launchpicker"
                    value="{{ old('launch_at') }}">
                <div class="input-group-append" data-target="#launchpicker" data-toggle="datetimepicker">
                    <div class="input-group-text" style="height: 100%;"><i class="far fa-calendar-alt text-primary"></i></div>
                </div>
            </div>
        </div>
        <div class="form-group py-3">
            <label for="expire_at" class="form-label">Expire Date & Time</label>
            <div class="input-group date datetimepicker-input" id="expirepicker" data-target-input="nearest">
                <input type="text" class="form-control datetimepicker-input"
                    id="expire_at" name="expire_at"
                    data-target="#expirepicker"
                    value="{{ old('expire_at') }}">
                <div class="input-group-append" data-target="#expirepicker" data-toggle="datetimepicker">
                    <div class="input-group-text" style="height: 100%;"><i class="far fa-calendar-alt text-primary"></i></div>
                </div>
            </div>
        </div>
        <div class="form-group py-3">
            <label class="form-label">Featured?</label>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" value="1" id="featured"
                    name="featured" {{ old('featured') ? 'checked' : '' }}>
                <label class="form-check-label" for="featured">Mark as Featured</label>
            </div>
        </div>
        <div class="form-group py-3">
            <button type="submit" class="btn btn-block btn-primary">Create Test Series</button>
        </div>
    </form>
<div>
<input type="hidden" id="parent_ulink" value="{{ $parentSeries->ulink ?? '' }}">
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
    function getulink(val) {
        let temp = toseourl(val);
        $("#ulink").val(temp);
        $("#helpulink").html(base() + temp);
    }
    function base() {
        return "{{ config('app.url') }}/test/series/";
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
    function toggleFees() {
        let div = document.getElementById('feesDiv');
        div.style.display = document.getElementById('is_paid').checked ? 'block' : 'none';
    }
    function removeParent() {
        if (document.getElementById('remove_parent').checked) {
            $('#parent_id').val('');
            $('#parent_name').val('');
            $('#parentDiv').addClass('d-none');
        }
    }
</script>
@endpush
