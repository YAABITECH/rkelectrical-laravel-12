@extends('layout.structure')
@section('xmt_tit', 'RKElectrical Grid - Learning center')
@section('xmt_des', 'Team of RKELECTRICAL GRID for GATE /BARC/TRB/TNEB/ISRO & ESE Lectures')
@section('xmt_rob', 'index, follow')
@section('xmt_can', '/')

@section('content')
    <section class="py-5">
        <div class="container text-center">
            <h5 class="text-center text-info fs-4">Manual Payment</h5>
            <div class="btn-group mt-3">
                <button class="btn active btn-info border-info transferBtn" onclick="moneyTransfer('bank')">Bank Transfer</button>
                <button class="btn border-info transferBtn" onclick="moneyTransfer('upi')">Gpay, PhonePay, PayTm, BHIM</button>
            </div>
            <div class="mt-3 transfer" id="bank">
                <div class="row">
                    <div class="col-md-6 offset-md-3">
                        <div class="card shadow p-3">
                            <p><span class="text-primary fw-bold">Name :</span> V RASSUKKUTTI</p>
                            <p><span class="text-primary fw-bold">A/c: </span>6440582028</p>
                            <p><span class="text-primary fw-bold">Bank:</span> INDIAN BANK</p>
                            <p><span class="text-primary fw-bold">IFSC:</span> IDIB000P148</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mt-1 text-center d-none transfer" id="upi">
                <img src="/image/gpay.jpg" style="width:100%;max-width:300px;">
            </div>
            <div class="card mt-4 mx-3 shadow p-4 text-center">
                <p class="text-danger lh-lg">Send your Payment Receipt with the screenshot of the following table to <a href="tel:+91 97915 08785" >+91 97915 08785</a></p>
                <table class="table  table-striped text-center border border-1">
                    <thead>
                        <tr>
                            <th scope="col">S.No</th>
                            <th scope="col">Type</th>
                            <th scope="col">Title</th>
                            <th scope="col">Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $Sno = 1;
                        @endphp
                        @if($coursePacks->isNotEmpty())
                            @foreach($coursePacks as $coursePack)
                                <tr>
                                    <th scope="row">{{$Sno++}}</th>
                                    <td>Course Pack</td>
                                    <td>{{$coursePack->name}}</td>
                                    <td class="text-info">₹{{$coursePack->fees}}</td>
                                </tr>
                            @endforeach
                        @endif

                        @if($courses->isNotEmpty())
                            @foreach($courses as $course)
                                <tr>
                                    <th scope="row">{{$Sno++}}</th>
                                    <td>Course</td>
                                    <td>{{$course->title}}</td>
                                    <td class="text-info">₹{{$course->fees}}</td>
                                </tr>
                            @endforeach
                        @endif
                        <tr>
                            <th colspan="3" class="fw-bold text-primary">Total Amount : </th>
                            <th class="text-primary fw-bold">₹{{$totalAmount}}</th>
                        </tr>
                    </tbody>
                </table>
                <div>
                    <a class="btn btn-success" href="https://wa.me/919791508785" target="_blank">Send WhatsApp</a>
                </div>
                <div class="mt-3">
                    <a class="btn btn-danger" href="mailto:rkelectricagrid@gmail.com" target="_blank">Send Email</a>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('headcss')
    <script>
        function moneyTransfer(money) {
            $('.transferBtn').removeClass('active btn-info');
            $('.transfer').addClass('d-none');
            $('#' + money).removeClass('d-none');
            $('button[onclick="moneyTransfer(\'' + money + '\')"]').addClass('active btn-info');
        }
    </script>
@endpush
