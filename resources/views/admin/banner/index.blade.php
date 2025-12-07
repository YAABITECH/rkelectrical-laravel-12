@extends('layout.admin.structure')
@section('xmt_tit', 'Banner | Admin')

@section('content')
<div class="container mt-3">
	<p class="text-center" style="line-height:3rem">
		<a href="" onclick="history.back(); return false;" class="btn btn-outline-secondary right">Back</a>
		<a href="{{ route('admin.banner.create') }}" class="btn btn-success">Add Banner</a>
		<a href="/admin/" class="btn btn-dark">Admin Home</a>
	</p>
	<h1 style="font-size:1.4rem; text-align:center;" class="text-success">List of All Banners</h1>
	<p> {{$count}} Banners</p>
	@if($count > 0)
		<div class="row">
			@foreach($banner as $banner)
				<div class="card col-md-4 shadow-sm mb-2" id="bnr_{{$banner->id}}"><div class="card-body">
					<p class="text-center"><img src="/image/banner/{{$banner->photo}}" class="w-100"></p>
					{{-- <a class="btn btn-sm btn-primary" href="{{ route('admin.banner.edit', $banner->id) }}">Edit</a> --}}
					<form action="{{ route('admin.banner.destroy', $banner->id) }}" method="POST" class="btn btn-sm d-inline">
						@csrf
						@method('DELETE')
						<button type="submit" class="btn btn-sm btn-danger">Delete</button>
					</form>        
				</div>
				</div>
			@endforeach
		</div>
	@else
		<div class='card bg-info p-3'>
			<div class='center line-height-1-6 grey-text text-darken-2'>
				Sorry! You did not add any Banner. Please add a banner photo
				<a href="{{ route('admin.banner.create') }}" class="btn btn-warning mt-3">Add Banner</a>
			</div>
		</div>
	@endif
</div>	
@endsection
