@extends('layout.structure')
@section('xmt_tit', 'RKElectrical Grid - Home')
@section('xmt_des', 'The India\'s Best Institute for Electrical Engineers. RKELECTRICALGRID  provides online courses in GATE, BARC, TRB, TNEB, ISRO & ESE')
@section('xmt_rob', 'index, follow')
@section('xmt_can', '/')

@push('headcss')
    <style>
        #subjectBtn:hover a{
            background-color:#fff !important;
            color:#820098 !important;
            transition:all 0.5s ease-in;
        }
        .subjectDiv:hover .card {
            box-shadow: rgba(0, 0, 0, 0.3) 0px 19px 38px, rgba(0, 0, 0, 0.22) 0px 15px 12px;
            transition:all 0.2s ease-in;
        }
        .subjectText {
            padding:20px 20px;
            transition:all 0.2s ease-in;
        }
        .subjectDiv:hover .subjectCon{
            padding:10px 20px;
            background-color:#810496 !important;
            transition:all 0.2s ease-in;
        }

        .subjectDiv:hover .subjectCon p{
            margin:30px 20px !important;
            color:white !important;
            transition:all 0.2s ease-in;
        }
        .testDiv:hover{
            background-color:#820098 !important;
            color:#fff !important;
            transition:all 0.3s ease-in-out;
        }
        .testDiv:hover h6 span{
            background-color:#fff !important;
            color:#820098 !important;
            transition:all 0.3s ease-in-out;
        }
        .testDiv:hover .review{
            color:#fff !important;
        }
    </style>
@endpush

@section('content')
    <section class="py-5" id="ytban">
        <div class="container">
            <div class="row" class="d-flex align-items-center justify-content-center">
                <div class="col-lg-6 pt-5 pt-lg-0 order-2 order-lg-1 d-flex flex-column justify-content-center">
                    <h1 data-aos="fade-up">The India's Best Institute for Electrical Engineers</h1>
                    <h2 data-aos="fade-up" data-aos-delay="400">Live and recorded class for TNEB, TRB, GATE, SSC, RRB, BARC, etc.</h2>
                    <div data-aos="fade-up" data-aos-delay="800">
                    <div class="d-flex">
                    <a href="/course/" class="btn-get-started scrollto">Get Started</a>
                    <a href="#mainbanvideo" class="glightbox btn-watch-video"><i class="fa fa-play-circle"></i><span>Watch Video</span></a>
                    </div>
                    </div>
                </div>
                <div class="col-lg-6 order-1 order-lg-2 hero-img" data-aos="fade-left" data-aos-delay="200">
                    <img src="/image/banner/rkelectricalgrid-banner.jpg" class="img-fluid animated" alt="">
                </div>
            </div>
        </div>
    </section>
    @if($subjects->count())
        <section class="bg-light py-5">
            <div class="container">
                <h4 class="text-center text-primary">Subjects</h4>
                <div class="row mt-3">
                    @foreach($subjects as $subject)
                        <div class="col-12 col-md-4 mt-3 subjectDiv">
                            <a href="{{route('practice.subject')}}" class="text-decoration-none">
                                <div class="card w-100 border-0 d-flex justify-content-end align-items-center" style="height:300px; background-image:url('/image/practice/subject/{{$subject->image}}'); background-size:cover; background-position:center">
                                    <div class=" position-relative text-center rounded-3 bg-white p-5 w-75 mb-2 subjectText">
                                        <div class="position-absolute bottom-0 text-center subjectCon m-auto start-0 end-0">
                                            <p class="card-text text-center mx-2 text-primary fw-semibold text-center pb-1 mb-1" >{{$subject->name}}</p>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
                <div class="text-center mt-4"  id="subjectBtn">
                    <a href="{{route('practice.subject')}}" class="btn btn-primary">View All Subjects</a>
                </div>
            </div>
        </section>
    @endif
    <!-- <section class="section bg-white py-3 shadow-sm">
        <div class="container">
            <div class="row">
                <h4 class="text-primary h4 text-center">Courses</h4>
                <div class="row">
                    @foreach($courses as $course )
                    <div class="col-12 col-md-4 mt-3">
                        <a href="{{route('course.detail',['url'=>$course->url])}}" class="text-decoration-none">
                            <div class="card rounded-5 " style="box-shadow: rgba(50, 50, 93, 0.25) 0px 2px 5px -1px, rgba(0, 0, 0, 0.3) 0px 1px 3px -1px;">
                                <div class="card-header py-3 bg-white">
                                    <h6 class="card-title text-center mb-0">{{$course->title}} <span ><a href="{{route('course.detail',['url'=>$course->url])}}" class="ps-5 text-end"><i class="fa-solid fa-circle-chevron-right fs-5 "></i></a></span></h6>
                                </div>
                                <div class="card-body p-0">
                                    <img src="/image/course/{{$course->photo}}" class="card-img-bottom rounded-bottom" alt="{{$course->title}}">
                                </div>
                            </div>
                        </a>
                    </div>
                    @endforeach
                </div>
                <div class="text-center mt-4"  id="subjectBtn">
                    <a href="{{route('course.index')}}" class="btn btn-primary">View All Course</a>
                </div>
            </div>
        </div>
    </section> -->
    @if($testimonials->count())
        <section class="bg-white py-5">
            <div class="container">
                <h4 class="text-primary text-center">Students Feedback</h4>
                <div class="row mt-4 p-2 d-flex justify-content-center">
                    @foreach($testimonials as $testimonial)
                        <div class="col-12 col-md-4 mt-4 ">
                            <div class="text-center px-2 border border-2 border-primary rounded-3 py-3 testDiv">
                                <div class="text-center" >
                                    <img src="/image/testimonial/{{ $testimonial->photo }}" class="rounded-circle border border-2 border-primary border-top-0 border-end-0" style="width:100%; max-width:75px; border-top:3px solid #820098;">
                                </div>
                                <div class="mt-2">
                                    @php
                                        $i=1;
                                    @endphp
                                    <p class="text-info review">Ratings:
                                        @for($i; $i<$testimonial->star; $i++)
                                        <span><i class="fa-solid fa-star text-warning"></i></span>
                                        @endfor
                                    </p>
                                </div>
                                <div class="text-start" style="text-align: justify;">
                                    <p>{!! $testimonial->content !!}</p>
                                </div>

                                <h6 class="text-secondary fw-semibold fst-italic text-end mt-2"><span class="bg-primary px-3 py-2 rounded-pill text-white">{{ $testimonial->name }}</span></h6>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="text-center mt-4">
                    <a href="/testimonial/" class="btn btn-outline-secondary">View All Feedbacks</a>
                </div>
            </div>
        </section>
    @endif
@endsection
