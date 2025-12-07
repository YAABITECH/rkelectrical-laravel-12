@extends('layout.admin.structure')
@section('xmt_tit', 'Notification Sender | Admin')

@section('content')
    <div class="container mt-3">
        <h1 class="text-center text-primary fs-5 lh-lg">Notification Sender</h1>
        <div class="text-center m-1 mb-3">
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
        <form method="post" action="{{ route('admin.notification.sender.send') }}" id="notificationForm">
            @csrf
            <input type="hidden" name="subscriber" id="subscriber" value="{{$subscriber}}">
            <div class="form-group mb-3">
                <label for="notification" class="form-label">Notification</label>
                <select class="form-select" id="notification" name="notification" data-live-search="true">
                    <option value="" {{ !old('notification') ? 'selected' : '' }}>All Notifications</option>
                    @foreach ($notifications as $notification)
                        <option value="{{ $notification->id }}" {{ old('notification') == $notification->id ? 'selected' : '' }}>
                            {{ $notification->title }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div id="formStatus"></div>
            <div class="form-group py-3">
                <button type="button" class="btn btn-block btn-primary" onclick="sendNotification();">Send</button>
            </div>
        </form>
    </div>
@endsection
@push('endjs')
<script>
    function sendNotification()
    {
        $('#formStatus').html('loading');
        var formData = $('#notificationForm').serialize();
        $.ajax({
            url: $('#notificationForm').attr('action'),
            type: 'POST',
            data: formData,
            success: function(response) {
                $('#formStatus').html('Sent successful');
                if(response.success == true)
                {
                    addToast('Notification sent successfully');
                }
            }
        });
    }
</script>
@endpush