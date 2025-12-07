@extends('layout.admin.structure')
@section('xmt_tit', 'Admin Manager')
@php
    $s_no=1;
@endphp
@section('content')
    <div class="container mt-3">
        <h1 class="text-center text-primary fs-5 lh-lg">Admin List</h1>
        <div class="text-center m-1 mb-3">
            <a class="btn btn-primary btn-sm" href="{{ route('admin.admin.create') }}" role="button"><i class="fa-solid fa-plus"></i></a>
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
        @if(session('admin_credential'))
            <div class="card mb-3">
                <div class="card-header bg-primary-subtle">
                    <p class="card-title mb-0 fs-5 fw-bold">Admin Credential:</p>
                </div>
                <div class="card-body">
                    <textarea class="form-control" id="credential_div" rows="4" readonly style="resize: none;">Login Credential:&#13;&#10;Username: {{ session('admin_credential')['username'] }}&#13;&#10;Password: {{ session('admin_credential')['password'] }}</textarea>
                    <div class="mt-2">
                        <button type="button" class="btn btn-primary btn-sm" onclick="select_credential(this)">Copy</button>
                        <button type="button" class="btn btn-success btn-sm" onclick="send_credential_whatsapp()">Send via WhatsApp</button>
                        <button type="button" class="btn btn-secondary btn-sm" onclick="location.reload();">Reload Page</button>
                    </div>
                </div>
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">
            {{ session('error') }}
            </div>
        @endif
        <div class="card shadow border-white rounded">
            <div class="extnd600">
                <table class="table table-light table-striped table-borderless m-0">
                    <thead>
                        <tr class="table-primary fw-bold">
                            <td>S.No</td>
                            <td>Admin Name</td>
                            <td class="text-center" style="min-width:100px">Action</td>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($admin as $admin)
                            <tr>
                                <td>{{ $s_no++ }}</td>
                                <td>{{ $admin->name }}</td>
                                <td class="text-center">
                                    <a href="{{ route('admin.admin.edit', $admin->id) }}" class="btn btn-success btn-sm"><i class="fa-solid fa-edit"></i></a>
                                    <form action="{{ route('admin.admin.destroy', $admin->id) }}" method="post"
                                        style="display: inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-danger btn-sm" type="submit"><i class="fa-solid fa-xmark"></i></button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
@push('endjs')
<script>
    function select_credential(e) {
        var div = document.getElementById("credential_div");
        div.select();
        document.execCommand("copy");
        e.innerHTML = "Copied!";
    }
    function send_credential_whatsapp() {
        var createdUserDiv = document.getElementById("credential_div");
        var text = createdUserDiv.value.trim();
        var encodedText = encodeURIComponent(text);
        var url = "https://wa.me/?text=" + encodedText;
        window.open(url,'_blank');
    }
</script>
@endpush