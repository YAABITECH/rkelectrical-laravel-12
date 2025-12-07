@extends('layout.structure')

@section('xmt_tit', 'Blogs & Updates')
@section('xmt_des', 'blog articles')
@section('xmt_rob', 'index, follow')
@php
    $sortarr = array('default' => 'Default', 'newest' => 'Newest First', 'oldest' => 'Oldest First');
@endphp
@push('headcss')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.1.2/css/tempusdominus-bootstrap-4.min.css" />
@endpush
@section('content')
<div class="container mt-4">
    <h1 class="text-center text-primary fs-5 lh-lg">Blogs</h1>
    @if ($errors->any())
        {!! implode('', $errors->all('<div class="alert alert-danger" role="alert">:message</div>')) !!}
    @endif
    <div class="d-flex justify-content-between align-items-center mb-2">
        @if (Request::filled('title') || Request::filled('author') || (Request::filled('date_from') || Request::filled('date_to')) || Request::filled('sort'))
        <div>
            <small class="fw-semibold text-primary">Filters:</small>
            @if (Request::filled('title'))
            <span class="badge text-bg-light text-muted">Title: {{ Request::input('title') }}</span>
            @endif
            @if (Request::filled('author'))
            <span class="badge text-bg-light text-muted">Author: {{ Request::input('author') }}</span>
            @endif
            @if (Request::filled('date_from') && Request::filled('date_to'))
            <span class="badge text-bg-light text-muted">Date Range: {{ Request::input('date_from') }} - {{ Request::input('date_to') }}</span>
            @elseif (Request::filled('date_from'))
            <span class="badge text-bg-light text-muted">Date From: {{ Request::input('date_from') }}</span>
            @elseif (Request::filled('date_to'))
            <span class="badge text-bg-light text-muted">Date To: {{ Request::input('date_to') }}</span>
            @endif
            @if (Request::filled('sort'))
                <span class="badge text-bg-light text-muted">Sort By: {{ Arr::get($sortarr, Request::input('sort'), '') }}</span>
            @endif
        </div>
        @else
        <div>
            <small>Filters:</small>
            <span class="badge text-bg-light text-muted">All Blogs</span>
        </div>
        @endif
        <div>
            <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#filtermod">Filter <i class="fa fa-filter"></i></button>
        </div>
    </div>

    @include('layout.admin.partials.paginationtop', ['paginator' => $items])
    @if (count($items)>0)
    @foreach ($items as $item)
    <a href="{{ route('blog.detail',$item->ulink) }}" class="text-decoration-none text-muted">
        <div class="card mb-3 shadow-sm border-0">
            <div class="row">
                <div class="col-md-3 d-flex align-items-center">
                    <div class="p-2">
                        <img src="/image/blog/thumb/{{$item->image}}" class="img-fluid rounded" alt="">
                    </div>
                </div>
                <div class="col-md-9">
                <div class="card-body p-3">
                    <h4 class="card-title text-primary fs-5 lh-base mb-2">{{$item->title}}</h4>
                    <p class="card-text"><small>Author: {{$item->author}}</small></p>
                </div>
                </div>
            </div>
        </div>
    </a>
    @endforeach
    @include('layout.admin.partials.paginationbottom', ['paginator' => $items])
    @else
    <div class="card my-4 d-flex align-items-center py-5 px-2 border-light shadow-sm">
        <p class="fs-4 text-danger fw-semibold">No Blogs Found</p>
        <p>Try search with another keyword or filter</p>
        <p>(OR)</p>
        <p>View all blogs <i class="fas fa-arrow-down"></i></p>
        <a href="/blog" class="btn btn-primary">All Blogs</a>
    </div>
    @endif
</div>
<div class="modal fade" id="filtermod" tabindex="-1" aria-labelledby="filterModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title" id="filterModalLabel">Filter</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
            <form action="{{ url('/blog') }}" method="get" id="filtform">
                <div class="form-group mb-3">
                <label for="sort" class="form-label">Sort By</label>
                <select name="sort" id="sort" class="form-select" data-live-search="true">
                    @foreach($sortarr as $key => $value)
                        <option value="{{ $key }}"{{ $key == Request::input('sort') ? ' selected' : '' }}>{{ $value }}</option>
                    @endforeach
                </select>
                </div>
                <div class="form-group mb-3">
                <label for="page" class="form-label">Page Number</label>
                <input type="number" class="form-control" id="page" name="page" step="1" min="1" value="{{ Request::route('page',1) }}">
                </div>
                <div class="form-group mb-3">
                <label for="title" class="form-label">Blog Title</label>
                <input type="text" class="form-control" id="title" name="title" placeholder="Keyword" value="{{ Request::input('title') }}">
                </div>
                <div class="form-group mb-3">
                <label for="date_from" class="form-label">Date From</label>
                <div class="input-group date datetimepicker-input" id="datetimepicker1" data-target-input="nearest">
                    <input type="text" class="form-control datetimepicker-input xfilter_field" id="date_from" name="date_from" data-target="#datetimepicker1" value="{{ Request::input('date_from') }}">
                    <div class="input-group-append" data-target="#datetimepicker1" data-toggle="datetimepicker">
                        <div class="input-group-text" style="height: 100%;"><i class="far fa-calendar-alt text-primary"></i></div>
                    </div>
                </div>
            </div>
            <div class="form-group mb-3">
                <label for="date_to" class="form-label">Date To</label>
                <div class="input-group date datetimepicker-input" id="datetimepicker2" data-target-input="nearest">
                    <input type="text" class="form-control datetimepicker-input xfilter_field" id="date_to" name="date_to" data-target="#datetimepicker2" value="{{ Request::input('date_to') }}">
                    <div class="input-group-append" data-target="#datetimepicker2" data-toggle="datetimepicker">
                        <div class="input-group-text" style="height: 100%;"><i class="far fa-calendar-alt text-primary"></i></div>
                    </div>
                </div>
            </div>
            <div class="form-group mb-3">
                <label for="perpage" class="form-label">Per Page Count</label>
                <input type="number" class="form-control xfilter_field" id="perpage" name="perpage" min="1" value="{{ Request::input('perpage') }}">
            </div>
                <div class="form-group mb-3">
                <label for="author" class="form-label">Author</label>
                <input type="text" class="form-control" id="author" name="author" value="{{ Request::input('author') }}">
                </div>
                <div class="pt-2">
                    <button type="submit" class="btn btn-primary">Apply Filter</button>
                    <input type="reset" value="Reset" class="btn btn-light" onclick="filtreset();">
                </div>
            </form>
            </div>
        </div>
    </div>
</div>
@endsection
@push('endjs')
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.1.2/js/tempusdominus-bootstrap-4.min.js"></script>
<script>
  document.getElementById('filtform').addEventListener('submit', function(event) {
    event.preventDefault();
    submitfilt();
  });
</script>
<script>
    $(function () {
        $('.datetimepicker-input').datetimepicker({
            format: 'DD-MM-YYYY',
            icons: {
                time: 'far fa-clock',
                date: 'far fa-calendar-alt',
                up: 'fas fa-arrow-up',
                down: 'fas fa-arrow-down',
                previous: 'fas fa-chevron-left',
                next: 'fas fa-chevron-right',
                today: 'fas fa-calendar-day',
                clear: 'far fa-trash-alt',
                close: 'far fa-times-circle'
            },
            useCurrent: false
        });

        $('#date_from').on('focus', function () {
            $('#datetimepicker1').datetimepicker('show');
        });
        $('#date_to').on('focus', function () {
            $('#datetimepicker2').datetimepicker('show');
        });
    });
</script> 
  <script>
    function submitfilt()
    {
        const title = document.getElementById('title').value.trim();
        const author = document.getElementById('author').value.trim();
        const date_from = document.getElementById('date_from').value.trim();
        const date_to = document.getElementById('date_to').value.trim();
        const perpage = document.getElementById('perpage').value.trim();
        const page = document.getElementById('page').value.trim();
        const sort = document.getElementById('sort').value.trim();

        let url = '/blog';

        let queryString = '';
        if (title) {
          queryString += `&title=${encodeURIComponent(title)}`;
        }
        if (author) {
          queryString += `&author=${encodeURIComponent(author)}`;
        }
        if (date_from) {
          queryString += `&date_from=${encodeURIComponent(date_from)}`;
        }
        if (date_to) {
          queryString += `&date_to=${encodeURIComponent(date_to)}`;
        }
        if (perpage) {
          queryString += `&perpage=${encodeURIComponent(perpage)}`;
        }
        if (page && page !== '1') {
          //   url += '/page/' + encodeURIComponent(page);
          queryString += `&page=${encodeURIComponent(page)}`;
        }
        if (sort && sort !== 'default') {
         queryString += `&sort=${encodeURIComponent(sort)}`;
        }
        if (queryString) {
          url += '?' + queryString.slice(1);
        }
        window.location.href = url;
    }
    function filtreset()
    {
        event.preventDefault();
        document.getElementById('title').value='';
        document.getElementById('author').value='';
        document.getElementById("perpage").value='';
        document.getElementById("date_from").value='';
        document.getElementById("date_to").value='';
        document.getElementById('page').value='1';
        document.getElementById("sort").selectedIndex = 0;
        submitfilt();
    }
    function gotopage(page) {
        document.getElementById("page").value = page;
        submitfilt()
    }
  </script>    
@endpush