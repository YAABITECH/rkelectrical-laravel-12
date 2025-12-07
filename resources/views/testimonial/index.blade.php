@extends('layout.structure')

@section('xmt_tit', '')
@section('xmt_des', '')
@section('xmt_rob', 'index, follow')
@section('xmt_can', '/')

@push('headcss')
    <style>
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
        </div>
    </section>
@endsection
