@extends('layout.structure')
@section('xmt_tit', 'Edit Profile')
@section('xmt_des', '')
@section('xmt_rob', 'index,follow')
@section('xmt_can')

@section('content')
    <div class="container py-3">
        <div class="row">
            @if ($errors->any())
                {!! implode('', $errors->all('<div class="alert alert-danger" role="alert">:message</div>')) !!}
            @endif
            @if (session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif
            <div class="col-12 col-lg-8 offset-lg-2 col-md-10 offset-md-1">
                <div class="row text-center bg-light p-3 m-1 mb-4 shadow">
                    <div class="col-3 col-md-3">
                        <a href="/user/profile" class=" text-decoration-none fw-bold text-info fs-5"><i class="fa-solid fa-arrow-left text-info"></i> Profile</a>
                    </div>
                    <div class="col-3 col-md-3">
                    </div>
                    <div class="col-3 col-md-3">
                    </div>
                <div class="col-3 col-md-3">
                    <a href="/user/logout" class=" text-decoration-none text-info">Logout</a>
                </div>
            </div>
            <div class="card shadow rounded">
                <div class="card-body bg-info rounded">
                <p class="text-light pl-3 h4"><span class="fa fa-user pr-2"></span> Edit Advanced</p>
                </div>
                <form action="{{ route('user.update-profile')}}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="card-title px-3">
                        <p class="text-success text-center pt-3"></p>
                        <table class="table">
                            <tr>
                                <th scope="row">NAME</th>
                                <td class="text-success pt-3"><input type="text" name="user_name" value="{{old('user_name', $user->name)}}" class="form-control"></td>
                            </tr>
                            <tr>
                                <th scope="row">EMAIL</th>
                                <td class="text-success pt-3"><input type="text" name="user_email" value="{{old('user_email', $user->email)}}" class="form-control"></td></tr>
                            <tr>
                                <th scope="row">PHONE</th>
                                <td class="text-success pt-3"><input type="text" name="user_phone"  value="{{old('user_phone', $user->phone)}}" class="form-control"></td>
                            </tr>
                        </table>
                    </div>
                    <div class="card-action px-4">
                        <button class="btn btn-primary" type="submit">Submit Profile</button>
                    </div><br>
                </form>
            </div>
        </div>
    </div>

@endsection
