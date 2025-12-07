@extends('layout.structure')
@section('xmt_tit', 'RKElectrical Grid - Learning center')
@section('xmt_des', 'Team of RKELECTRICAL GRID  for GATE /BARC/TRB/TNEB/ISRO & ESE Lectures')
@section('xmt_rob', 'index, follow')
@section('xmt_can', '/')

@section('content')
<section>
<div class="container py-3">
    <h3 class="text-primary text-center fw-semibold" >Contact Us</h3>
    <div class="mt-3 p-3">
        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d62330.412503213374!2d79.19935997677833!3d12.472961562081048!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3bacdb324d8819ff%3A0xf09e3b76faca928a!2sRKELECTRICAL%20GRID!5e0!3m2!1sen!2sin!4v1600077468168!5m2!1sen!2sin" class="rounded-3" width="100%" height="450" frameborder="0" style="border:0;" allowfullscreen="" aria-hidden="false" tabindex="0"></iframe>
    </div>
    @if ($errors->any())
            {!! implode('', $errors->all('<div class="alert alert-danger" role="alert">:message</div>')) !!}
            @endif
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
      <div class="row my-3 px-3">
          <div class="col-12 col-md-5 mt-3">
            <h5 class="text-center text-md-start text-info fw-semibold lh-lg">Contact Details:</h5>
            <p class="lh-lg"><a href="https://goo.gl/maps/6ew7wi7riWUMkTUMA" class="text-dark text-decoration-none" target="_blank"><span class="fa fa-map-marker"></span>&ensp;202, Road Street, Semmiyaamangalam, Polur, Thiruvannamalai, Pin:606904.</a></p>
            <p class="lh-lg"><a href="tel:+91 73582 92951" class="text-dark text-decoration-none"><span class="fa fa-phone"></span>&ensp;+91 73582 92951</a></p>
            <p><a class="text-dark text-decoration-none" href="https://wa.me/917358292951" target="_blank"><span class="fa fa-whatsapp"></span>&ensp;WhatsApp</a></p>
            <p style="word-wrap: break-word;"><a class="text-dark text-decoration-none" href="mailto:rkelectricagrid@gmail.com" ><span class="fa fa-envelope"></span>&ensp;rkelectricagrid@gmail.com</a></p>
          </div>
          <div class="col-12 col-md-7 mt-3">
            <h5 class="text-center text-md-start text-info fw-semibold lh-lg">Get in touch</h5>
            <form method="POST" action="{{ route('contact.store') }}">
                @csrf
                <div class=" mb-3">
                  <label for="username" class="form-label">Name</label>
                  <input type="text" name="username" class="form-control" id="username">
                </div>
                <div class=" mb-3">
                  <label for="phone" class="form-label">Phone number</label>
                  <input type="phone" class="form-control" name="phone" id="phone">
                </div>
                <div class=" mb-3">
                  <label for="comment" class="form-label">Message</label>
                  <textarea class="form-control" rows="5" name="comment" id="comment"></textarea>
                </div><br>
                <p class="text-center"><button type="submit" class="btn bg-primary text-light">Submit</button></p>
            </form>
          </div>
      </div>
      <br>
  </div>
</section>
@include('layout.livechatbtn')

@endsection
