@extends('layout.admin.structure')
@section('xmt_tit', 'Admin Home')

@section('content')
<main>
    <div class="container text-center">
        <div class="row pt-5">
            <div class="col-sm-12 col-md-6">
                {{-- <p><a class="btn btn-secondary w-100" href="/admin/course-pack/"><span class="fa fa-graduation-cap"></span>&emsp;Course Packages</a></p>
                <p><a class="btn btn-secondary w-100" href="/admin/course/"><span class="fa fa-graduation-cap"></span>&emsp;Main Courses</a></p>
                <p><a class="btn btn-secondary w-100" href="/admin/course-video/"><span class="fa fa-file-video-o"></span>&emsp;Course Videos</a></p>
                <p><a class="btn btn-secondary w-100" href="/admin/assign/"><span class="fa fa-graduation-cap"></span>&emsp;Assign Course</a></p> --}}
                <!-- <p><a class="btn btn-secondary w-100" href="/admin/tutorial-playlist/"><span class="fa fa-youtube"></span>&emsp;Tutorial Playlist</a></p>
                <p><a class="btn btn-secondary w-100" href="/admin/tutorial/"><span class="fa fa-youtube-play"></span>&emsp;Tutorial Video</a></p> -->
                <p><a class="btn btn-secondary w-100" href="/admin/test/series"><span class="fa fa-wpforms"></span>&emsp;Test Series</a></p>
                <p><a class="btn btn-secondary w-100" href="/admin/test/exam"><span class="fa fa-edit"></span>&emsp;Test Page</a></p>
                {{-- <p><a class="btn btn-secondary w-100" href="/admin/test-report/"><span class="fa fa-wpforms"></span>&emsp;Test Report</a></p> --}}
            </div>
            <div class="col-sm-12 col-md-6">
                {{-- <p><a class="btn btn-secondary w-100" href="/admin/practice/subject"><span class="fa fa-group"></span>&emsp;Practice Subject</a></p> --}}
                {{-- <p><a class="btn btn-secondary w-100" href="/admin/practice/topic"><span class="fa fa-group"></span>&emsp;Practice Topic</a></p> --}}
                {{-- <p><a class="btn btn-secondary w-100" href="/admin/practice/subtopic"><span class="fa fa-group"></span>&emsp;Practice Subtopic</a></p> --}}
                {{-- <p><a class="btn btn-secondary w-100" href="/admin/practice/question"><span class="fa fa-group"></span>&emsp;Practice Question</a></p> --}}
                <p><a class="btn btn-secondary w-100" href="/admin/testimonial/"><span class="fa fa-group"></span>&emsp;Testimonials</a></p>
                {{-- <p><a class="btn btn-secondary w-100" href="/admin/banner/"><span class="fa fa-photo"></span>&emsp;Banner Image</a></p> --}}
                <p><a class="btn btn-secondary w-100" href="/admin/user/"><span class="fa fa-profile"></span>&emsp;Users</a></p>
                <p><a class="btn btn-secondary w-100" href="/admin/blog/"><span class="fa fa-profile"></span>&emsp;Blogs</a></p>
                {{-- <p><a class="btn btn-secondary w-100" href="/admin/announcement/"><span class="	fa fa-bullhorn"></span>&emsp;Announcement</a></p> --}}
                {{-- <p><a class="btn btn-secondary w-100" href="/admin/transaction/"><span class="	fa fa-inr"></span>&emsp;Transactions</a></p>
                <p><a class="btn btn-secondary w-100" href="/admin/student/"><span class="	fa fa-inr"></span>&emsp;Students</a></p> --}}
            </div>
        </div>
    </div>
</main>
@endsection