@extends('layout.structure')
@section('xmt_tit', 'RKElectrical Grid - Payment Response')
@section('xmt_des', 'Payment status page for course and test of RKElectricalGrid')
@section('xmt_rob', 'noindex,follow')

@section('content')
<section class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-12 col-md-8 offset-md-2">
                <div class="card">
                    <p class="text-center py-3 mb-0">Take a screenshot and whatsapp here <i class="fa-solid fa-arrow-right"></i><a href="https://wa.me/919791508785" class="text-decoration-none">&emsp;<span class="fa fa-whatsapp text-success fs-0-8"> Whatsapp</span></a></p>
                    <hr>
                    <table class="table">
                        <tbody class="px-3">
                            <tr>
                                <th>Payment ID:</th>
                                <td>{{ $paymentStatus->payment_id ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Name</th>
                                <td>{{ $paymentStatus->getUser->name ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Phone</th>
                                <td>{{ $paymentStatus->getUser->phone ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Email</th>
                                <td>{{ $paymentStatus->getUser->email ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Amount</th>
                                <td>{{ $paymentStatus->amount ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Status</th>
                                <td>{{ $paymentStatus->status ?? 'N/A' }}</td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="text-center py-3">
                        <img src="/image/{{$paymentStatus->status == 'Success' ? 'success.png' : 'wrong.jpg'}}" alt="" class="shadow p-3 rounded-circle" style="width:100%; max-width:100px;">
                        <p class="text-primary fw-bold lh-lg">{{$paymentStatus->status == 'Success' ? 'Your Transaction has successfully done' : 'Oops&#33; something went wrong, Your Transaction unsuccessfull'}}</p>
                        <a href="/" class="btn btn-secondary">Back to Home Page</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
