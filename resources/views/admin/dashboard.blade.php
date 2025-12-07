@extends('layout.admin.structure')
@section('xmt_tit', 'Admin Home')

@push('headcss')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.1.2/css/tempusdominus-bootstrap-4.min.css" />
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<style>
    #registered_users_container {
    position: relative;
    width: 100%;
    height: 0;
    padding-bottom: 75%; /* adjust this value as needed */
}

#registered_users {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
}
</style>
@endpush
@section('content')
<main>
    <div class="container">
        <h1 class="text-center fs-4 mt-4 text-primary">Admin Panel</h1>
        @if (session('error'))
            <div class="alert alert-danger">
            {{ session('error') }}
            </div>
        @endif
        <div class="text-center py-3" style="line-height:3rem">
            <p class="lh-lg">Users Management</p>
            <a href="/admin/admin" class="btn btn-primary">Admin Managers</a>
            <a href="/admin/user" class="btn btn-primary">Client Users</a>
            <a href="/admin/course/enrol" class="btn btn-primary">Course Enrol</a>
            <a href="/admin/course/student" class="btn btn-primary">Course Student</a>
            
        </div>
        <div class="text-center py-3" style="line-height:3rem">
            <p class="lh-lg">Notification</p>
            <a href="/admin/notification" class="btn btn-primary">Notification</a>
            <a href="/admin/notification/subscriber" class="btn btn-primary">Subscribers</a>
            <a href="/admin/notification/sender" class="btn btn-primary">Sender</a>
        </div>
        <div class="text-center py-3" style="line-height:3rem">
            <p class="lh-lg">Content Management</p>
            <a href="/admin/course" class="btn btn-primary">Course</a>
            <a href="/admin/course/chapter" class="btn btn-primary">Course Chapter</a>
            <a href="/admin/course/section" class="btn btn-primary">Course Section</a>
            <a href="/admin/course/assign" class="btn btn-primary">Course Assign</a>
            <a href="/admin/blog/category" class="btn btn-primary">Blog Category</a>
            <a href="/admin/blog" class="btn btn-primary">Blog</a>
            <a href="/admin/search" class="btn btn-primary">Search</a>
        </div>
        <div class="text-center py-3" style="line-height:3rem">
            <p class="lh-lg">Finance Management</p>
            <a href="/admin/account" class="btn btn-primary">Accounts</a>
            <a href="/admin/payment" class="btn btn-primary">Payments</a>
            <a href="/admin/transaction" class="btn btn-primary">Transactions</a>
            <a href="/admin/pending" class="btn btn-primary">Pending</a>
            <a href="/admin/recurring" class="btn btn-primary">Recurring</a>
        </div>
        <div class="text-center py-3" style="line-height:3rem">
            <p class="lh-lg">File Manager</p>
            <button class="btn btn-primary" onclick="openfilemanager('Images');">Images</button>
            <button class="btn btn-primary" onclick="openfilemanager('Files');">Files</button>
            <a href="/admin/artisan" class="btn btn-primary">Artisan</a>
        </div>
    </div>
    {{-- <div class="container">
        <div class="d-flex justify-content-start align-items-end mt-2">
            <div class="form-group d-inline-flex">
                <label for="date_range" class="form-label m-2">Date:</label>
                <select name="date_range" id="date_range" class="form-select" data-live-search="true" onchange="loadChartData()">
                    @php
                        $date_range = array('today' => 'Today', 'this_week' => 'This Week', 'this_month' => 'This Month', 'this_year' => 'This Year', 'last_7_days' => 'Last 7 Days', 'last_30_days' => 'Last 30 Days', 'last_90_days' => 'Last 90 Days', 'last_365_days' => 'Last 365 Days', 'all_time' => 'All Time', 'custom' => 'Custom');
                    @endphp
                    @foreach($date_range as $key => $value)
                        <option value="{{ $key }}" {{ ($key=='all_time')? 'selected' : '' }}>{{ $value }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="d-flex justify-content-justify align-items-center mt-2">
            <div class="form-group mb-3">
                <label for="date_from" class="form-label">Registered From</label>
                <div class="input-group date datetimepicker-input" id="datetimepicker1" data-target-input="nearest">
                    <input type="text" class="form-control datetimepicker-input xfilter_field" id="date_from" name="date_from" data-target="#datetimepicker1" oninput="loadChartData()">
                    <div class="input-group-append" data-target="#datetimepicker1" data-toggle="datetimepicker">
                        <div class="input-group-text" style="height: 100%;"><i class="far fa-calendar-alt text-primary"></i></div>
                    </div>
                </div>
            </div>
            <div class="form-group mb-3">
                <label for="date_to" class="form-label">Registered To</label>
                <div class="input-group date datetimepicker-input" id="datetimepicker2" data-target-input="nearest">
                    <input type="text" class="form-control datetimepicker-input xfilter_field" id="date_to" name="date_to" data-target="#datetimepicker2" oninput="loadChartData()">
                    <div class="input-group-append" data-target="#datetimepicker2" data-toggle="datetimepicker">
                        <div class="input-group-text" style="height: 100%;"><i class="far fa-calendar-alt text-primary"></i></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-12 col-md-6">
                <div class="card p-2">
                    <p>Registered Users</p>
                    <div id="registered_users_container">
                        <div id="registered_users"></div>
                    </div>
                </div>
            </div>
        </div>
    </div> --}}
    <div class="container">
        <div class="row d-flex justify-content-center align-items-center mt-5">
            <div class="col-12 col-sm-6 col-md-5 col-lg-4 col-xl-3">
                <div class="text-center p-2">
                    <a href="{{ route('admin.course.index') }}" class="text-decoration-none text-secondary">
                        <div class="card border-light shadow p-2">
                            <div class="card-body p-4 text-center">
                                <img class="card-img-top" src="{{ URL::asset('/image/category.png') }}" alt="Title" style="max-width:200px">
                            </div>
                            <div class="card-footer border-light">
                                <h4 class="card-title">Course</h4>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-md-5 col-lg-4 col-xl-3">
                <div class="text-center p-2">
                    <a href="{{ route('admin.course.index') }}" class="text-decoration-none text-secondary">
                        <div class="card border-light shadow p-2">
                            <div class="card-body p-4 text-center">
                                <img class="card-img-top" src="{{ URL::asset('/image/article.png') }}" alt="Title" style="max-width:200px">
                            </div>
                            <div class="card-footer border-light">
                                <h4 class="card-title">User</h4>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-md-5 col-lg-4 col-xl-3">
                <div class="text-center p-2">
                    <a href="{{ route('admin.course.index') }}" class="text-decoration-none text-secondary">
                        <div class="card border-light shadow p-2">
                            <div class="card-body p-4 text-center">
                                <img class="card-img-top" src="{{ URL::asset('/image/author.png') }}" alt="Title" style="max-width:200px">
                            </div>
                            <div class="card-footer border-light">
                                <h4 class="card-title">Test</h4>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection
@push('endjs')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.1.2/js/tempusdominus-bootstrap-4.min.js"></script>
    <script>
        $(function () {
            $('.datetimepicker-input').datetimepicker({
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
        function openfilemanager(type)
        {
            let params = `titlebar=no,scrollbars=no,resizable=no,status=no,location=no,toolbar=no,menubar=no,width=1200,height=600,left=1000,top=1000`;
            window.open('/admin/file-manager?type='+type,'FileManagerWindow',params);
        }
    </script>
    <script>
        google.charts.load('current', {'packages':['corechart']});
        google.charts.setOnLoadCallback(function() {
            loadChartData();
        });

        function loadChartData() {
            var dateRange = $('#date_range').val();
            var mydata = { date_range: dateRange }
            if(dateRange=='custom')
            {
                mydata = {
                    date_range: dateRange,
                    start_date: $('#date_from').val(),
                    end_date: $('#date_to').val(),
                }
            }
            $.ajax({
                url: '/registered-users-chart-data',
                type: 'GET',
                data: mydata,
                dataType: 'json',
                success: function(response) {
                    if (response.length > 1) {
                        var data = google.visualization.arrayToDataTable(response);

                        var options = {
                            title: 'Registered Users',
                            curveType: 'function',
                            legend: { position: 'bottom' }
                        };

                        var chart = new google.visualization.LineChart(document.getElementById('registered_users'));

                        chart.draw(data, options);
                        // Add the resize event listener
                        $(window).on('resize', function() {
                            chart.draw(data, options);
                        });
                    } else {
                        document.getElementById('registered_users').innerHTML = '<p class="alert alert-danger">No data available for the selected date range.</p>';
                    }
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    console.log(xhr.responseText);
                }
            });
        }


        // function loadChartData(dateRange, startDate, endDate) {
        //     $.ajax({
        //         url: '/registered-users-chart-data',
        //         type: 'GET',
        //         data: {
        //             date_range: dateRange,
        //             start_date: startDate,
        //             end_date: endDate
        //         },
        //         dataType: 'json',
        //         success: function(response) {
        //             var data = google.visualization.arrayToDataTable(response);

        //             var options = {
        //                 title: 'User Registrations, Enrollments, and Students',
        //                 curveType: 'function',
        //                 legend: { position: 'bottom' },
        //                 hAxis: { title: 'Day' },
        //                 vAxes: [
        //                     { title: 'User Registrations' },
        //                     { title: 'Course Enrollments' },
        //                     { title: 'Course Students' }
        //                 ],
        //                 series: {
        //                     0: { targetAxisIndex: 0 },
        //                     1: { targetAxisIndex: 1 },
        //                     2: { targetAxisIndex: 2 }
        //                 }
        //             };

        //             var chart = new google.visualization.LineChart(document.getElementById('registered_users'));

        //             chart.draw(data, options);
        //         },
        //         error: function(xhr, ajaxOptions, thrownError) {
        //             console.log(xhr.responseText);
        //         }
        //     });
        // }



        // function loadChartData(dateRange) {
        //     $.ajax({
        //         url: '/registered-users-chart-data',
        //         type: 'GET',
        //         data: { date_range: dateRange },
        //         dataType: 'json',
        //         success: function(response) {
        //             if (response.length > 1) {
        //                 var data = google.visualization.arrayToDataTable(response);

        //                 var options = {
        //                     title: 'Registered Users',
        //                     curveType: 'function',
        //                     legend: { position: 'bottom' }
        //                 };

        //                 var chart = new google.visualization.LineChart(document.getElementById('registered_users'));

        //                 chart.draw(data, options);
        //                 // Add the resize event listener
        //                 $(window).on('resize', function() {
        //                     chart.draw(data, options);
        //                 });
        //             } else {
        //                 document.getElementById('registered_users').innerHTML = '<p class="alert alert-danger">No data available for the selected date range.</p>';
        //             }
        //         },
        //         error: function(xhr, ajaxOptions, thrownError) {
        //             console.log(xhr.responseText);
        //         }
        //     });
        // }
    </script>
@endpush