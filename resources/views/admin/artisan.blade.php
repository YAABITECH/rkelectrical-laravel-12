@extends('layout.admin.structure')
@section('xmt_tit', 'Admin Artisan')

@section('content')
<form action="/admin/artisan-run" method="post">
    @csrf
<button class="btn btn-primary">Submit</button>
</form>
@endsection