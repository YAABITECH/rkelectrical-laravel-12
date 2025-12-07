@extends('layout.structure')
@section('xmt_tit', '')
@section('xmt_des', '')
@section('xmt_rob', ' index,follow')
@section('xmt_can', '/')

@section('content')
    <section class="py-5">
        <div class="container">
            <div class="row">
                <div class="col-12 col-md-6 offset-md-3">
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
                    <div class="d-flex justify-content-between align-items-center shadow p-3">
                        <a href="{{route('user.profile')}}" class=" text-decoration-none text-info"><i class="fa-solid fa-arrow-left text-info fw-bold"></i> Profile</a>
                        <a href="{{route('user.logout')}}" class=" text-decoration-none text-info">Logout</a>
                    </div>
                    <div class="mt-3">
                        <form action="{{ route('user.update_studentregister') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="shadow p-3" style="background-color:rgb(255, 255, 255,0.8);"><br>
                                <p class="text-center h5 text-primary">Student Details</p>
                                <div class="form-group mb-3 ">
                                    <label for="degree" class="text-primary">DOB:</label>
                                    <input type="date" class="form-control mt-2" id="dob" placeholder="Enter dob" name="dob" value="{{old('dob', $user->dob)}}">
                                </div>
                                <div class="mb-3">
                                    <label for="degree" class="text-primary">Gender:</label><br>
                                    <div class="form-check form-check-inline mt-3">
                                        <input class="form-check-input" type="radio" name="gender" id="male" value="Male"{{$user->gender == 'Male' ? 'checked' : ''}}>
                                        <label class="form-check-label" for="male">Male</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="gender" id="female" value="Female"{{$user->gender == 'Female' ? 'checked' : ''}}>
                                        <label class="form-check-label" for="female">Female</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="gender" id="others" value="Others"{{$user->gender == 'Others' ? 'checked' : ''}}>
                                        <label class="form-check-label" for="others">Others</label>
                                    </div>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="clgname" class="text-primary">College Name:</label>
                                    <input type="text" class="form-control mt-2" placeholder="Enter college Name" id="clgname" name="clgname"   value="{{old('clgname', $user->clgname)}}">
                                </div>
                                <div class="form-group mb-3">
                                    <label for="year" class="text-primary">Year of Complete:</label>
                                    <input type="text" class="form-control mt-2" id="year" placeholder="Enter year of complete" name="year" value="{{old('year', $user->year)}}">
                                </div>
                                <div class="form-group mb-3">
                                    <label for="degree" class="text-primary">Degree:</label>
                                    <input type="text" class="form-control mt-2" placeholder="Enter degree" id="degree" name="degree" value="{{old('degree', $user->degree)}}">
                                </div>
                                <div class="form-group mb-3">
                                    <label for="Department" class="text-primary">Department:</label>
                                    <input type="text" class="form-control mt-2" placeholder="Enter department" id="department" name="department" value="{{old('department', $user->department)}}">
                                </div>
                                <input type="hidden" id="redirect" value="">
                                <div class="text-center">
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

