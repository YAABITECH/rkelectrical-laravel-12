@extends('layout.structure')
@section('xmt_tit', 'RKElectrical Grid - Home')
@section('xmt_des', 'The India\'s Best Institute for Electrical Engineers. RKELECTRICALGRID  provides online courses in GATE, BARC, TRB, TNEB, ISRO & ESE')
@section('xmt_rob', 'index, follow')
@section('xmt_can', '/')

@push('headcss')
    <style>
           .subjectDiv .card {
        position: relative;
        overflow: hidden;
        border-radius: 12px;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        cursor: pointer;
    }

    .subjectDiv .card:hover {
        transform: scale(1.05);
        box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.2);
    }

    .subjectDiv .card img {
        border-radius: 12px;
        transition: opacity 0.3s ease;
    }

    .subjectDiv .card .overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.4);
        opacity: 0;
        transition: opacity 0.3s ease;
        border-radius: 12px;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .subjectDiv .card:hover .overlay {
        opacity: 1;
    }

    .subjectDiv .card p {
        color: #fff;
        font-size: 1.2rem;
        font-weight: 600;
        margin: 0;
    }
        /* #subjectBtn:hover a{
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
        } */
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
                    {{--
                    <div data-aos="fade-up" data-aos-delay="800">
                        <div class="d-flex">
                            <a href="/course/" class="btn-get-started scrollto">Get Started</a>
                            <a href="#mainbanvideo" class="glightbox btn-watch-video"><i class="fa fa-play-circle"></i><span>Watch Video</span></a>
                        </div>
                    </div>
                    --}}
                </div>
                <div class="col-lg-6 order-1 order-lg-2 hero-img" data-aos="fade-left" data-aos-delay="200">
                    <img src="/image/banner/rkelectricalgrid-banner.jpg" class="img-fluid animated" alt="">
                </div>
            </div>
        </div>
    </section>
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
